<?php

namespace App\Http\Controllers;

use App\Models\EngagementLetterCompany;
use App\Models\EngagementLetterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EngagementController extends Controller
{
    public function engagement()
    {
        $client = Auth::guard('client')->user();
        // $companyLetter = EngagementLetterCompany::where('company_id', $client->id)->get();
        $companyLetter = $client->companies()
            ->with('engagementLetters') // eager load
            ->get()
            ->pluck('engagementLetters') // get only the engagement letters
            ->flatten(); // convert nested collection to a single list
        $selfLetter = null;
        return view('backend.engagement.index', compact('companyLetter', 'selfLetter'));
    }

    public function viewPdf($id)
    {
        $client = Auth::guard('client')->user();

        // Find the engagement letter and verify it belongs to the current client
        $engagementLetter = EngagementLetterCompany::where('id', $id)
            ->where('company_id', $client->id)
            ->firstOrFail();

        // Check if file exists
        if (!$engagementLetter->file_path || !Storage::exists($engagementLetter->file_path)) {
            abort(404, 'PDF file not found');
        }

        // Return the PDF file
        return Storage::response($engagementLetter->file_path, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="engagement-letter-' . $engagementLetter->id . '.pdf"'
        ]);
    }

    public function downloadPdf($id)
    {
        $client = Auth::guard('client')->user();

        // Find the engagement letter and verify it belongs to the current client
        $engagementLetter = EngagementLetterCompany::where('id', $id)
            ->where('company_id', $client->id)
            ->firstOrFail();

        // Check if file exists
        if (!$engagementLetter->file_path || !Storage::exists($engagementLetter->file_path)) {
            abort(404, 'PDF file not found');
        }

        // Download the PDF file
        return Storage::download($engagementLetter->file_path, 'engagement-letter-' . $engagementLetter->id . '.pdf');
    }
}
