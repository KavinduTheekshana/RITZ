<?php

namespace App\Http\Controllers;

use App\Models\SelfAssessmentChatList;
use App\Models\SelfAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SelfAssessmentChatController extends Controller
{
    /**
     * Get chat messages for a specific self assessment
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'self_assessment_id' => 'required|exists:self_assessments,id',
        ]);

        $client = Auth::guard('client')->user();
        
        // Verify that the client owns this self assessment
        if (!$client->selfAssessment || $client->selfAssessment->id != $request->self_assessment_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this self assessment'
            ], 403);
        }

        // Get chat messages for this self assessment
        $messages = SelfAssessmentChatList::where('self_assessment_id', $request->self_assessment_id)
            ->with(['user', 'client'])
            ->orderBy('sent_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender_display_name,
                    'message' => $message->message,
                    'file_name' => $message->file_name,
                    'file_url' => $message->file_url,
                    'sent_at' => $message->sent_at->toISOString(),
                    'is_read' => $message->is_read,
                    // Add signature fields
                    'requires_signature' => $message->requires_signature ?? false,
                    'is_signed' => $message->is_signed ?? false,
                    'signed_file_url' => $message->signed_file_url,
                    'signer_full_name' => $message->signer_full_name,
                    'signed_at' => $message->signed_at ? $message->signed_at->toISOString() : null,
                ];
            });

        // Mark admin messages as read
        SelfAssessmentChatList::where('self_assessment_id', $request->self_assessment_id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => $messages->reverse()->values() // Reverse to show oldest first
        ]);
    }

    /**
     * Send a message from client to admin
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'self_assessment_id' => 'required|exists:self_assessments,id',
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,txt|max:10240',
        ]);

        $client = Auth::guard('client')->user();
        
        // Verify that the client owns this self assessment
        if (!$client->selfAssessment || $client->selfAssessment->id != $request->self_assessment_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this self assessment'
            ], 403);
        }

        // Prepare chat data
        $chatData = [
            'self_assessment_id' => $request->self_assessment_id,
            'client_id' => $client->id,
            'sender_type' => 'client',
            'sender_name' => $client->full_name,
            'sender_email' => $client->email,
            'message' => $request->message,
            'sent_at' => now(),
            'is_read' => false, // Client messages start as unread for admin
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('self-assessment-chat', $fileName, 'public');
            
            $chatData['file_path'] = $filePath;
            $chatData['file_name'] = $file->getClientOriginalName();
            $chatData['file_size'] = $file->getSize();
            $chatData['file_type'] = $file->getMimeType();
        }

        $chat = SelfAssessmentChatList::create($chatData);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $chat->id,
                'sender_type' => $chat->sender_type,
                'sender_name' => $chat->sender_display_name,
                'message' => $chat->message,
                'file_name' => $chat->file_name,
                'file_url' => $chat->file_url,
                'sent_at' => $chat->sent_at->toISOString(),
                'is_read' => $chat->is_read,
            ]
        ]);
    }

    /**
     * Get unread message counts for self assessment
     */
    public function getUnreadCounts(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client->selfAssessment) {
            return response()->json([
                'success' => true,
                'data' => ['self_assessment' => 0]
            ]);
        }

        $unreadCount = SelfAssessmentChatList::where('self_assessment_id', $client->selfAssessment->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'self_assessment' => $unreadCount
            ]
        ]);
    }

    /**
     * Sign a document that requires signature
     */
    public function signDocument(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer',
            'signer_full_name' => 'required|string|max:255',
            'signer_print_name' => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
            'browser_data' => 'nullable|string',
        ]);

        try {
            $client = Auth::guard('client')->user();
            $message = SelfAssessmentChatList::findOrFail($request->message_id);
            
            // Verify ownership
            if (!$client->selfAssessment || $client->selfAssessment->id !== $message->self_assessment_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this document.'
                ]);
            }

            // Check if document requires signature
            if (!$message->requires_signature || !$message->file_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'This document does not require a signature.'
                ]);
            }

            // Check if already signed
            if ($message->is_signed) {
                return response()->json([
                    'success' => false,
                    'message' => 'This document has already been signed.'
                ]);
            }

            // Generate signed PDF
            $signedPdfPath = $this->generateSignedPdf($message, $request->all());

            // Update message record
            $message->update([
                'is_signed' => true,
                'signer_full_name' => $request->signer_full_name,
                'signer_print_name' => $request->signer_print_name,
                'signer_email' => $request->signer_email,
                'signer_ip' => $request->ip(),
                'signer_browser_data' => $request->browser_data,
                'signed_at' => now(),
                'signed_file_path' => $signedPdfPath,
            ]);

            Log::info('Self assessment document signed successfully', [
                'message_id' => $message->id,
                'self_assessment_id' => $message->self_assessment_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document signed successfully!',
                'data' => [
                    'signed_file_url' => $message->signed_file_url,
                    'signed_at' => $message->signed_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error signing self assessment document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while signing the document. Please try again.'
            ], 500);
        }
    }

    private function generateSignedPdf($message, $signatureData)
    {
        try {
            // Similar implementation to company chat signing
            $pdf = new Fpdi();
            
            // Get the full path to the original PDF
            $originalPath = storage_path('app/public/' . $message->file_path);
            
            if (!file_exists($originalPath)) {
                throw new \Exception('Original PDF file not found');
            }

            // Get page count
            $pageCount = $pdf->setSourceFile($originalPath);
            
            // Import all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                // Add a page with the same orientation and size
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', [$size['width'], $size['height']]);
                } else {
                    $pdf->AddPage('P', [$size['width'], $size['height']]);
                }
                
                $pdf->useTemplate($templateId, 0, 0, null, null, true);
            }
            
            // Go to the last page to add signature
            $pdf->setPage($pageCount);
            
            // Add signature information
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);
            
            // Position signature at bottom of last page
            $pdf->SetXY(30, $size['height'] - 60);
            $pdf->Cell(0, 10, 'SIGNED ELECTRONICALLY', 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'Signed by: ' . $signatureData['signer_full_name'], 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'Print Name: ' . $signatureData['signer_print_name'], 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'Email: ' . $signatureData['signer_email'], 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'Date: ' . now()->format('d/m/Y'), 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'Time: ' . now()->format('H:i:s'), 0, 1, 'L');
            $pdf->SetX(30);
            $pdf->Cell(0, 5, 'IP Address: ' . request()->ip(), 0, 1, 'L');
            
            // Generate filename
            $fileName = 'signed_' . time() . '_' . basename($message->file_name, '.pdf') . '.pdf';
            $filePath = 'self-assessment-chat/signed/' . $fileName;
            
            // Save the signed PDF
            $pdfContent = $pdf->Output('S');
            Storage::disk('public')->put($filePath, $pdfContent);
            
            // Return the PUBLIC path (not storage path)
            return 'storage/' . $filePath;
            
        } catch (\Exception $e) {
            Log::error('Error generating signed PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}