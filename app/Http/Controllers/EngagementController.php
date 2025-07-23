<?php

namespace App\Http\Controllers;

use App\Models\EngagementLetterCompany;
use App\Models\EngagementLetterSelfAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;

class EngagementController extends Controller
{
    public function engagement()
    {
        $client = Auth::guard('client')->user();

        // Get company engagement letters
        $companyLetter = $client->companies()
            ->with('engagementLetters')
            ->get()
            ->pluck('engagementLetters')
            ->flatten();

        // Get self assessment engagement letters
        $selfLetter = null;
        if ($client->selfAssessment) {
            $selfLetter = $client->selfAssessment->engagementLetters;
        }

        return view('backend.engagement.index', compact('companyLetter', 'selfLetter'));
    }

    public function sign(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Sign request received', [
            'engagement_id' => $request->engagement_id,
            'engagement_type' => $request->engagement_type,
            'signer_full_name' => $request->signer_full_name,
            'browser_data' => $request->browser_data ? 'Present' : 'Missing',
            'ip' => $request->ip()
        ]);

        $request->validate([
            'engagement_id' => 'required|integer',
            'engagement_type' => 'required|in:company,self_assessment',
            'signer_full_name' => 'required|string|max:255',
            'signer_print_name' => 'required|string|max:255',
            'signer_email' => 'required|email|max:255',
            'signed_date' => 'required|date',
            'browser_data' => 'nullable|string',
        ]);

        try {
            $client = Auth::guard('client')->user();
            
            if ($request->engagement_type === 'company') {
                $engagement = EngagementLetterCompany::findOrFail($request->engagement_id);
                
                // Verify that the engagement belongs to the authenticated client's company
                if (!$client->companies->contains('id', $engagement->company_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to this document.'
                    ]);
                }
            } else {
                $engagement = EngagementLetterSelfAssessment::findOrFail($request->engagement_id);
                
                // Verify that the engagement belongs to the authenticated client's self assessment
                if (!$client->selfAssessment || $client->selfAssessment->id !== $engagement->self_assessment_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to this document.'
                    ]);
                }
            }

            // Check if already signed
            if ($engagement->is_signed) {
                return response()->json([
                    'success' => false,
                    'message' => 'This document has already been signed.'
                ]);
            }

            // Create signed PDF
            $signedPdfPath = $this->createSignedPdf($engagement, $request->all(), $request->engagement_type);

            // Update the engagement record
            $updateData = [
                'signer_full_name' => $request->signer_full_name,
                'signer_print_name' => $request->signer_print_name,
                'signer_email' => $request->signer_email,
                'signed_date' => $request->signed_date,
                'signed_at' => Carbon::now(),
                'signed_file_path' => $signedPdfPath,
                'is_signed' => true,
                'ip' => $request->ip(),
                'browser_data' => $request->browser_data,
            ];

            // Log what we're updating
            Log::info('Updating engagement with data:', $updateData);

            $engagement->update($updateData);

            // Log successful update
            Log::info('Document signed successfully', [
                'engagement_id' => $engagement->id,
                'engagement_type' => $request->engagement_type,
                'ip_saved' => $engagement->ip,
                'browser_data_saved' => $engagement->browser_data ? 'Yes' : 'No'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document signed successfully!',
                'signed_file_url' => asset('storage/' . $signedPdfPath)
            ]);
        } catch (\Exception $e) {
            Log::error('Error signing engagement letter', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while signing the document: ' . $e->getMessage()
            ], 500);
        }
    }

  private function createSignedPdf($engagement, $signatureData, $type)
{
    try {
        // Fix path separator issue
        $originalPdfPath = storage_path('app/public/' . str_replace('\\', '/', $engagement->file_path));
        
        // Log the path for debugging
        Log::info('Looking for PDF at: ' . $originalPdfPath);
        
        if (!file_exists($originalPdfPath)) {
            // Try alternative path format
            $alternativePath = storage_path('app/' . str_replace('\\', '/', $engagement->file_path));
            if (file_exists($alternativePath)) {
                $originalPdfPath = $alternativePath;
            } else {
                throw new \Exception('Original PDF file not found: ' . $originalPdfPath);
            }
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
            $pdf->Cell(0, 8, 'Document: Engagement Letter', 0, 1);
            
            if ($type === 'company') {
                $pdf->Cell(0, 8, 'Company: ' . $engagement->company->company_name, 0, 1);
            } else {
                $pdf->Cell(0, 8, 'Self Assessment: ' . $engagement->selfAssessment->assessment_name, 0, 1);
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
                        // Process user agent to fit in table
                        $userAgent = $browserData['userAgent'];

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
            if ($type === 'company') {
                $signedFileName = 'engagement_letters/signed_company_' . $engagement->company_id . '_' . time() . '.pdf';
            } else {
                $signedFileName = 'engagement_letters/self_assessments/signed_self_' . $engagement->self_assessment_id . '_' . time() . '.pdf';
            }
            
            $signedPdfPath = storage_path('app/public/' . $signedFileName);

            // Ensure directory exists
            $directory = dirname($signedPdfPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save the signed PDF
            $pdf->Output($signedPdfPath, 'F');

            Log::info('Signed PDF created successfully', ['path' => $signedPdfPath]);

            return $signedFileName;
        } catch (\Exception $e) {
            Log::error('Error creating signed PDF: ' . $e->getMessage());
            throw new \Exception('Failed to create signed PDF: ' . $e->getMessage());
        }
    }
}