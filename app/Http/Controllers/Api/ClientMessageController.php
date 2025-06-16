<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyChat;
use App\Models\Client;
use App\Models\CompanyChatList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientMessageController extends Controller
{
    /**
     * Receive a message from a client
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'client_email' => 'required|email',
            'client_name' => 'required|string|max:255',
            'message' => 'required_without:file|string|max:2000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the company
            $company = Company::findOrFail($request->company_id);

            // Try to find the client by email
            $client = Client::where('email', $request->client_email)->first();

            // Prepare chat data
            $chatData = [
                'company_id' => $company->id,
                'client_id' => $client?->id,
                'sender_type' => 'client',
                'sender_name' => $request->client_name,
                'sender_email' => $request->client_email,
                'message' => $request->message,
                'sent_at' => now(),
                'is_read' => false, // Client messages start as unread
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Generate unique filename
                $fileName = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();
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
                    'company_name' => $company->company_name,
                    'sent_at' => $message->sent_at->toISOString()
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error sending client message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the message'
            ], 500);
        }
    }

    /**
     * Get chat history for a client (optional - for client portal)
     */
    public function getChatHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'client_email' => 'required|email',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $limit = $request->get('limit', 50);

            // Get the LATEST messages first (desc order) with limit
            $messages = CompanyChatList::where('company_id', $request->company_id)
                ->where(function ($query) use ($request) {
                    $query->where('sender_email', $request->client_email)
                        ->orWhere('sender_type', 'admin');
                })
                ->with(['user', 'client'])
                ->orderBy('created_at', 'desc') // Get latest messages first
                ->limit($limit)
                ->get()
                ->reverse() // Then reverse to show chronological order (oldest first)
                ->values() // Reset array keys
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_type' => $message->sender_type,
                        'sender_name' => $message->sender_display_name,
                        'message' => $message->message,
                        'file_name' => $message->file_name,
                        'file_url' => $message->file_url,
                        'file_size' => $message->formatted_file_size,
                        'created_at' => $message->created_at,
                        'is_read' => $message->is_read,
                    ];
                });

            // Mark admin messages as read when client views them
            CompanyChatList::where('company_id', $request->company_id)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting chat history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving chat history'
            ], 500);
        }
    }

    /**
     * Mark client messages as read (when admin views them)
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'message_ids' => 'nullable|array',
            'message_ids.*' => 'exists:company_chats,id',
            'sender_type' => 'nullable|in:admin,client'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = CompanyChatList::where('company_id', $request->company_id)
                ->where('is_read', false);

            // If specific message IDs provided
            if ($request->has('message_ids') && !empty($request->message_ids)) {
                $query->whereIn('id', $request->message_ids);
            }

            // If sender type specified
            if ($request->has('sender_type')) {
                $query->where('sender_type', $request->sender_type);
            }

            $updated = $query->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => "Marked {$updated} messages as read"
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking messages as read: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking messages as read'
            ], 500);
        }
    }

    /**
     * Get recent notifications for header dropdown
     */
    public function getRecentNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $limit = $request->get('limit', 10);

            // Find client by email
            $client = Client::where('email', $request->client_email)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            // Get company IDs for this client
            $companyIds = $client->companies->pluck('id');

            if ($companyIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Get recent messages (last 10, within last 30 days)
            $recentMessages = CompanyChatList::whereIn('company_id', $companyIds)
                ->with(['company'])
                ->where('created_at', '>=', now()->subDays(30))
                ->orderBy('sent_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'company_name' => $message->company->company_name,
                        'sender_type' => $message->sender_type,
                        'sender_name' => $message->sender_display_name,
                        'message' => $message->message ? Str::limit($message->message, 50) : null,
                        'file_name' => $message->file_name,
                        'sent_at' => $message->sent_at->toISOString(),
                        'is_read' => $message->is_read,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $recentMessages
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting recent notifications: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving notifications'
            ], 500);
        }
    }

    /**
     * Mark all messages as read for a client
     */
    public function markAllAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find client by email
            $client = Client::where('email', $request->client_email)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            // Get company IDs for this client
            $companyIds = $client->companies->pluck('id');

            if ($companyIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No companies found for this client'
                ]);
            }

            // Mark all admin messages as read for this client's companies
            $updated = CompanyChatList::whereIn('company_id', $companyIds)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => "Marked {$updated} messages as read"
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all messages as read: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking all messages as read'
            ], 500);
        }
    }

    /**
     * Get chat statistics for a client
     */
    public function getChatStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find client by email
            $client = Client::where('email', $request->client_email)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            // Get company IDs for this client
            $companyIds = $client->companies->pluck('id');

            if ($companyIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'total_messages' => 0,
                        'unread_messages' => 0,
                        'messages_sent' => 0,
                        'messages_received' => 0,
                        'files_shared' => 0,
                        'active_conversations' => 0,
                        'last_activity' => null,
                    ]
                ]);
            }

            // Calculate statistics
            $stats = [
                'total_messages' => CompanyChatList::whereIn('company_id', $companyIds)->count(),
                'unread_messages' => CompanyChatList::whereIn('company_id', $companyIds)
                    ->where('sender_type', 'admin')
                    ->where('is_read', false)
                    ->count(),
                'messages_sent' => CompanyChatList::whereIn('company_id', $companyIds)
                    ->where('sender_type', 'client')
                    ->where('sender_email', $request->client_email)
                    ->count(),
                'messages_received' => CompanyChatList::whereIn('company_id', $companyIds)
                    ->where('sender_type', 'admin')
                    ->count(),
                'files_shared' => CompanyChatList::whereIn('company_id', $companyIds)
                    ->whereNotNull('file_path')
                    ->count(),
                'active_conversations' => $companyIds->count(),
                'last_activity' => CompanyChatList::whereIn('company_id', $companyIds)
                    ->max('sent_at'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting chat statistics: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving chat statistics'
            ], 500);
        }
    }

    /**
     * Delete a message (optional feature)
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $validator = Validator::make(['message_id' => $messageId], [
            'message_id' => 'required|exists:company_chats,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid message ID'
            ], 422);
        }

        try {
            $message = CompanyChatList::findOrFail($messageId);

            // Only allow deletion of client's own messages
            if ($message->sender_type !== 'client' || $message->sender_email !== $request->get('client_email')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this message'
                ], 403);
            }

            // Delete file if exists
            if ($message->file_path && Storage::disk('public')->exists($message->file_path)) {
                Storage::disk('public')->delete($message->file_path);
            }

            // Delete the message
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the message'
            ], 500);
        }
    }

    /**
     * Get unread message counts per company for a client
     */
    public function getUnreadCounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find client by email
            $client = Client::where('email', $request->client_email)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ], 404);
            }

            // Get company IDs for this client
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
        } catch (\Exception $e) {
            Log::error('Error getting unread counts: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving unread counts'
            ], 500);
        }
    }
}
