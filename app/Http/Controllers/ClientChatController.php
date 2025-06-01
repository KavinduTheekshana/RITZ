<?php

namespace App\Http\Controllers;

use App\Models\CompanyChatList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Get unread message count for all companies
     */
    public function getUnreadCounts()
    {
        $client = Auth::guard('client')->user();
        $companyIds = $client->companies->pluck('id');

        $unreadCounts = [];
        foreach ($companyIds as $companyId) {
            $count = CompanyChatList::where('company_id', $companyId)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->count();
            
            if ($count > 0) {
                $unreadCounts[$companyId] = $count;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $unreadCounts
        ]);
    }
}