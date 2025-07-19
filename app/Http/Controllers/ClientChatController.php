<?php

namespace App\Http\Controllers;

use App\Models\CompanyChatList;
use App\Models\SelfAssessmentChatList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ClientChatController extends Controller
{
    /**
     * Display the chat interface for the authenticated client
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Get companies associated with this client
        $companies = $client->companies()->get();
        
        // Get self assessment if available
        $selfAssessment = $client->selfAssessment;
        
        return view('backend.chat.index', compact('companies', 'selfAssessment'));
    }

    /**
     * Get chat messages for a specific company and client
     */
    public function getMessages(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $client = Auth::guard('client')->user();
        
        // Verify that the client has access to this company
        if (!$client->companies->contains('id', $request->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this company'
            ], 403);
        }

        // Get chat messages for this company
        $messages = CompanyChatList::where('company_id', $request->company_id)
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
        CompanyChatList::where('company_id', $request->company_id)
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
            'company_id' => 'required|exists:companies,id',
            'message' => 'required_without:file|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,txt|max:10240',
        ]);

        $client = Auth::guard('client')->user();
        
        // Verify that the client has access to this company
        if (!$client->companies->contains('id', $request->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this company'
            ], 403);
        }

        // Prepare chat data
        $chatData = [
            'company_id' => $request->company_id,
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
            $filePath = $file->storeAs('company-chat', $fileName, 'public');

            $chatData['file_path'] = $filePath;
            $chatData['file_name'] = $file->getClientOriginalName();
            $chatData['file_size'] = $file->getSize();
            $chatData['file_type'] = $file->getMimeType();
        }

        // Create the chat message
        $message = CompanyChatList::create($chatData);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'message_id' => $message->id,
                'sent_at' => $message->sent_at->toISOString()
            ]
        ], 201);
    }

    /**
     * Get unread message count for all companies and self assessment
     */
    public function getUnreadCounts()
    {
        $client = Auth::guard('client')->user();
        $unreadCounts = [];
        
        // Get unread counts for companies
        $companies = $client->companies;
        foreach ($companies as $company) {
            $count = CompanyChatList::where('company_id', $company->id)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->count();
                
            if ($count > 0) {
                $unreadCounts['company-' . $company->id] = $count;
            }
        }
        
        // Get unread count for self assessment
        if ($client->selfAssessment) {
            $count = SelfAssessmentChatList::where('self_assessment_id', $client->selfAssessment->id)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->count();
                
            if ($count > 0) {
                $unreadCounts['self-assessment-' . $client->selfAssessment->id] = $count;
            }
        }
        
        return response()->json([
            'success' => true,
            'unread_counts' => $unreadCounts
        ]);
    }

    /**
     * Sign a document in the chat
     */
    public function signDocument(Request $request)
    {
        $request->validate([
            'chat_message_id' => 'required|exists:company_chat_lists,id',
            'signer_full_name' => 'required|string|max:255',
            'signer_print_name' => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
            'signed_date' => 'required|date',
            'browser_data' => 'nullable|string',
        ]);

        $client = Auth::guard('client')->user();
        $message = CompanyChatList::findOrFail($request->chat_message_id);
        
        // Verify access
        if (!$client->companies->contains('id', $message->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }
        
        // Check if already signed
        if ($message->is_signed) {
            return response()->json([
                'success' => false,
                'message' => 'This document has already been signed'
            ]);
        }
        
        // Check if signature is required
        if (!$message->requires_signature) {
            return response()->json([
                'success' => false,
                'message' => 'This document does not require a signature'
            ]);
        }
        
        try {
            // Create signed PDF
            $signedPdfPath = $this->createSignedPdfForChat($message, $request->all());
            
            // Update message record
            $message->update([
                'signer_full_name' => $request->signer_full_name,
                'signer_print_name' => $request->signer_print_name,
                'signer_email' => $request->signer_email,
                'signer_ip' => $request->ip(),
                'signer_browser_data' => $request->browser_data,
                'signed_at' => now(),
                'signed_file_path' => $signedPdfPath,
                'is_signed' => true,
            ]);
            
            // Create a system message to notify about signing
            CompanyChatList::create([
                'company_id' => $message->company_id,
                'sender_type' => 'system',
                'message' => "Document '{$message->file_name}' has been signed by {$request->signer_full_name}",
                'sent_at' => now(),
                'is_read' => false,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document signed successfully',
                'signed_file_url' => Storage::url($signedPdfPath)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error signing chat document', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while signing the document'
            ], 500);
        }
    }

    /**
     * Create a signed PDF for chat document
     */
    private function createSignedPdfForChat($message, $signatureData)
    {
        $originalPdfPath = storage_path('app/public/' . $message->file_path);
        
        if (!file_exists($originalPdfPath)) {
            throw new \Exception('Original PDF file not found');
        }
        
        $pdf = new Fpdi();
        
        try {
            $pageCount = $pdf->setSourceFile($originalPdfPath);
            
            // Copy existing pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplId = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplId);
            }
        } catch (\Exception $e) {
            Log::error('Error processing PDF: ' . $e->getMessage());
            throw new \Exception('Error processing PDF file');
        }
        
        // Add signature page
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 20, 'DIGITAL SIGNATURE', 0, 1, 'C');
        
        // Add signature details
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);
        
        // Document information
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Document Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, 'Document: ' . $message->file_name, 0, 1);
        $pdf->Cell(0, 8, 'Company: ' . $message->company->company_name, 0, 1);
        $pdf->Ln(5);
        
        // Signature information
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Signature Details', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, 'Signed by: ' . $signatureData['signer_full_name'], 0, 1);
        $pdf->Cell(0, 8, 'Print Name: ' . $signatureData['signer_print_name'], 0, 1);
        $pdf->Cell(0, 8, 'Email: ' . $signatureData['signer_email'], 0, 1);
        $pdf->Cell(0, 8, 'Date: ' . $signatureData['signed_date'], 0, 1);
        $pdf->Cell(0, 8, 'IP Address: ' . request()->ip(), 0, 1);
        $pdf->Cell(0, 8, 'Timestamp: ' . now()->format('Y-m-d H:i:s T'), 0, 1);
        
        // Save signed PDF
        $signedFileName = 'signed_' . time() . '_' . $message->file_name;
        $signedFilePath = 'company-chat/signed/' . $signedFileName;
        
        // Ensure directory exists
        Storage::disk('public')->makeDirectory('company-chat/signed');
        
        // Save the PDF
        $pdfContent = $pdf->Output('S');
        Storage::disk('public')->put($signedFilePath, $pdfContent);
        
        return $signedFilePath;
    }
}