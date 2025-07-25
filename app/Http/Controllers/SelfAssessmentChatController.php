<?php

namespace App\Http\Controllers;

use App\Models\SelfAssessmentChatList;
use App\Models\SelfAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


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
                    'file_type' => $message->file_type,
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
        'signed_date' => 'required|date', // Add this field
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
            ], 403);
        }

        // Check if document requires signature
        if (!$message->requires_signature || !$message->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'This document does not require a signature.'
            ], 400);
        }

        // Check if already signed
        if ($message->is_signed) {
            return response()->json([
                'success' => false,
                'message' => 'This document has already been signed.'
            ], 400);
        }

        // Create the signature data array with all required fields
        $signatureData = [
            'signer_full_name' => $request->signer_full_name,
            'signer_print_name' => $request->signer_print_name,
            'signer_email' => $request->signer_email,
            'signed_date' => $request->signed_date,
            'browser_data' => $request->browser_data,
        ];

        // Generate signed PDF - use the correct method name
        $signedPdfPath = $this->createSignedPdf($message, $signatureData);

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

        // Create a system message to notify about signing
        SelfAssessmentChatList::create([
            'self_assessment_id' => $message->self_assessment_id,
            'sender_type' => 'system',
            'sender_name' => 'System',
            'sender_email' => 'system@ritzaccounting.com',
            'message' => "Document '{$message->file_name}' has been signed by {$request->signer_full_name}",
            'sent_at' => now(),
            'is_read' => false,
        ]);

        Log::info('Self assessment document signed successfully', [
            'message_id' => $message->id,
            'self_assessment_id' => $message->self_assessment_id
        ]);

        // Refresh the entire message to get all updated fields
        $message->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Document signed successfully!',
            'data' => [
                'signed_file_url' => $message->signed_file_url,
                'signed_at' => $message->signed_at ? $message->signed_at->toISOString() : null,
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


    /**
     * Generate a signed PDF (Legacy method - kept for compatibility)
     */
    private function generateSignedPdf($message, $signatureData)
    {
        try {
            $pdf = new Fpdi();
            
            // Get the full path to the original PDF
            $originalPath = storage_path('app/public/' . $message->file_path);
            
            if (!file_exists($originalPath)) {
                throw new \Exception('Original PDF file not found');
            }

            // Get page count
            $pageCount = $pdf->setSourceFile($originalPath);
            
            // Track the last page size
            $lastPageSize = null;
            
            // Import all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                // Store last page size
                if ($pageNo === $pageCount) {
                    $lastPageSize = $size;
                }
                
                // Add a page with the same orientation and size
                if ($size['width'] > $size['height']) {
                    $pdf->AddPage('L', [$size['width'], $size['height']]);
                } else {
                    $pdf->AddPage('P', [$size['width'], $size['height']]);
                }
                
                $pdf->useTemplate($templateId, 0, 0, null, null, true);
            }
            
            // Add signature page or add to last page if there's space
            if ($lastPageSize) {
                // Check if we have enough space on the last page
                $availableSpace = $lastPageSize['height'] - 100; // Reserve 100 units for signature
                
                if ($availableSpace > 150) {
                    // Add signature to the last page
                    $pdf->setPage($pageCount);
                    $yPosition = $lastPageSize['height'] - 90;
                } else {
                    // Add a new page for signature
                    $pdf->AddPage('P', 'A4');
                    $yPosition = 30;
                }
            } else {
                // Fallback: add new page
                $pdf->AddPage('P', 'A4');
                $yPosition = 30;
            }
            
            // Add signature information
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(30, $yPosition);
            $pdf->Cell(0, 10, 'ELECTRONIC SIGNATURE', 0, 1, 'L');
            
            $pdf->SetFont('Arial', '', 11);
            $pdf->SetX(30);
            $pdf->Cell(0, 6, 'This document has been electronically signed:', 0, 1, 'L');
            $pdf->Ln(5);
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'Signed by:', 0, 0, 'L');
            $pdf->Cell(0, 6, $signatureData['signer_full_name'], 0, 1, 'L');
            
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'Print Name:', 0, 0, 'L');
            $pdf->Cell(0, 6, $signatureData['signer_print_name'], 0, 1, 'L');
            
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'Email:', 0, 0, 'L');
            $pdf->Cell(0, 6, $signatureData['signer_email'], 0, 1, 'L');
            
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'Date:', 0, 0, 'L');
            $pdf->Cell(0, 6, now()->format('d/m/Y'), 0, 1, 'L');
            
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'Time:', 0, 0, 'L');
            $pdf->Cell(0, 6, now()->format('H:i:s T'), 0, 1, 'L');
            
            $pdf->SetX(30);
            $pdf->Cell(40, 6, 'IP Address:', 0, 0, 'L');
            $pdf->Cell(0, 6, request()->ip(), 0, 1, 'L');
            
            // Add a border around signature block
            $pdf->Rect(25, $yPosition - 5, 160, 65);
            
            // Generate filename
            $fileName = 'signed_' . time() . '_' . str_replace('.pdf', '', $message->file_name) . '.pdf';
            $filePath = 'self-assessment-chat/signed/' . $fileName;
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('self-assessment-chat/signed');
            
            // Save the signed PDF
            $pdfContent = $pdf->Output('S');
            Storage::disk('public')->put($filePath, $pdfContent);
            
            // Return just the path without 'storage/' prefix
            return $filePath;
            
        } catch (\Exception $e) {
            Log::error('Error generating signed PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create a signed PDF for chat document
     */
   private function createSignedPdf($message, $signatureData)
{
    try {
        $originalPdfPath = storage_path('app/public/' . $message->file_path);
        
        if (!file_exists($originalPdfPath)) {
            throw new \Exception('Original PDF file not found');
        }
        
        // Initialize FPDI
        $pdf = new Fpdi();
        
        // Set source file and get page count
        $pageCount = $pdf->setSourceFile($originalPdfPath);
        
        // Copy all existing pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $tplId = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplId);
        }
        
        // Add signature page
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        
        // Title
        $pdf->Cell(0, 20, 'SIGNATURE PAGE', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Document info
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Document Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, 'Document: ' . $message->file_name, 0, 1);
        
        // Company or Self Assessment info
        if (isset($message->company)) {
            $pdf->Cell(0, 8, 'Company: ' . $message->company->company_name, 0, 1);
        } else {
            $pdf->Cell(0, 8, 'Self Assessment: ' . $message->selfAssessment->assessment_name, 0, 1);
        }
        
        $pdf->Ln(10);
        
        // Signer Information Table
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'SIGNER INFORMATION', 0, 1);
        $pdf->Ln(3);
        
        // Set table parameters
        $pdf->SetFont('Arial', '', 11);
        $colWidth1 = 50; // Label column width
        $colWidth2 = 130; // Value column width
        $cellHeight = 8;
        
        // Table header style
        $pdf->SetFillColor(230, 230, 230); // Light gray background
        $pdf->SetDrawColor(100, 100, 100); // Dark gray border
        $pdf->SetLineWidth(0.3);
        
        // Signer information rows
        $signerRows = [
            ['Full Name:', $signatureData['signer_full_name']],
            ['Print Name:', $signatureData['signer_print_name']],
            ['Email Address:', $signatureData['signer_email']],
            ['Date Signed:', Carbon::parse($signatureData['signed_date'])->format('F d, Y')],
            ['IP Address:', request()->ip()],
        ];
        
        // Add browser data if available
        if (isset($signatureData['browser_data']) && !empty($signatureData['browser_data'])) {
            $browserData = json_decode($signatureData['browser_data'], true);
            if ($browserData && is_array($browserData)) {
                if (isset($browserData['language'])) {
                    $signerRows[] = ['Language:', $browserData['language']];
                }
                if (isset($browserData['timezone'])) {
                    $signerRows[] = ['Timezone:', $browserData['timezone']];
                }
                if (isset($browserData['userAgent'])) {
                    // Process user agent to extract browser and OS
                    $userAgent = $browserData['userAgent'];
                    
                    // Extract browser info
                    preg_match('/(?P<browser>Chrome|Firefox|Safari|Edge|Opera)\/(?P<version>[\d\.]+)/', $userAgent, $matches);
                    if (!empty($matches)) {
                        $browserInfo = $matches['browser'] . ' ' . $matches['version'];
                        
                        // Extract OS info
                        if (strpos($userAgent, 'Windows') !== false) {
                            $browserInfo .= ' (Windows)';
                        } elseif (strpos($userAgent, 'Mac') !== false) {
                            $browserInfo .= ' (Mac)';
                        } elseif (strpos($userAgent, 'Linux') !== false) {
                            $browserInfo .= ' (Linux)';
                        } elseif (strpos($userAgent, 'Android') !== false) {
                            $browserInfo .= ' (Android)';
                        } elseif (strpos($userAgent, 'iOS') !== false || strpos($userAgent, 'iPhone') !== false) {
                            $browserInfo .= ' (iOS)';
                        }
                        
                        $signerRows[] = ['Browser:', $browserInfo];
                    } else {
                        $signerRows[] = ['Browser:', 'Unknown Browser'];
                    }
                }
            }
        }
        
        // Draw the table
        foreach ($signerRows as $index => $row) {
            // Alternate row colors
            if ($index % 2 == 0) {
                $pdf->SetFillColor(248, 248, 248); // Very light gray
            } else {
                $pdf->SetFillColor(255, 255, 255); // White
            }
            
            // Draw cells with borders
            $pdf->Cell($colWidth1, $cellHeight, $row[0], 1, 0, 'L', true);
            $pdf->Cell($colWidth2, $cellHeight, $row[1], 1, 1, 'L', true);
        }
        
        $pdf->Ln(15);
        
        // Verification Information Table
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'SIGNATURE VERIFICATION', 0, 1);
        $pdf->Ln(3);
        
        $pdf->SetFont('Arial', '', 11);
        
        $verificationRows = [
            ['Signed At:', Carbon::now()->format('F d, Y \a\t g:i A T')],
            ['Digital Signature:', 'Electronic signature applied'],
            ['Document Status:', 'Legally binding electronic signature'],
            ['Verification Method:', 'IP address and browser fingerprint tracking'],
        ];
        
        // Draw verification table
        foreach ($verificationRows as $index => $row) {
            if ($index % 2 == 0) {
                $pdf->SetFillColor(248, 248, 248);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }
            
            $pdf->Cell($colWidth1, $cellHeight, $row[0], 1, 0, 'L', true);
            $pdf->Cell($colWidth2, $cellHeight, $row[1], 1, 1, 'L', true);
        }
        
        $pdf->Ln(20);
        
        // Footer disclaimer
        $pdf->Ln(25);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->MultiCell(0, 4, 'This document has been electronically signed and is legally binding. The signature above was applied electronically and verified through digital means including IP address tracking and browser identification.', 0, 'C');
        
        // Generate unique filename for signed PDF
        if (isset($message->company)) {
            $signedFileName = 'signed_' . time() . '_' . $message->file_name;
            $signedFilePath = 'company-chat/signed/' . $signedFileName;
        } else {
            $signedFileName = 'signed_' . time() . '_' . $message->file_name;
            $signedFilePath = 'self-assessment-chat/signed/' . $signedFileName;
        }
        
        // Ensure directory exists
        if (isset($message->company)) {
            Storage::disk('public')->makeDirectory('company-chat/signed');
        } else {
            Storage::disk('public')->makeDirectory('self-assessment-chat/signed');
        }
        
        // Save the signed PDF
        $pdfContent = $pdf->Output('S');
        Storage::disk('public')->put($signedFilePath, $pdfContent);
        
        Log::info('Signed PDF created successfully', ['path' => $signedFilePath]);
        
        return $signedFilePath;
        
    } catch (\Exception $e) {
        Log::error('Error creating signed PDF: ' . $e->getMessage());
        throw new \Exception('Failed to create signed PDF: ' . $e->getMessage());
    }
}


/**
 * Download attachment with proper filename
 */
public function downloadAttachment(SelfAssessmentChatList $message)
{
    $client = Auth::guard('client')->user();
    
    // Verify access
    if ($client->selfAssessment->id !== $message->self_assessment_id) {
        abort(403);
    }
    
    if (!$message->file_path) {
        abort(404, 'File not found');
    }
    
    $filePath = storage_path('app/public/' . $message->file_path);
    
    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }
    
    return response()->download($filePath, $message->file_name);
}


}