<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Company Info Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $record->company_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $record->company_email }}</p>
                    </div>
                </div>
                
                <!-- Chat Stats -->
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $this->getChatMessages()->count() }} messages</span>
                    </div>
                    @if($this->getUnreadClientMessages() > 0)
                        <div class="flex items-center space-x-1 text-red-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $this->getUnreadClientMessages() }} unread</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div class="bg-white rounded-lg shadow">
            <!-- Chat Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Chat History</h3>
                <p class="text-sm text-gray-500">Two-way communication with this company</p>
            </div>

            <!-- Messages Area -->
            <div class="h-96 overflow-y-auto p-6 space-y-4" id="messages-container">
                @forelse($this->getChatMessages() as $message)
                    <div class="flex items-start space-x-3 {{ $message->isFromClient() ? 'flex-row-reverse space-x-reverse' : '' }}">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $message->sender_avatar_color }} rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-white">
                                    {{ substr($message->sender_display_name, 0, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div class="flex-1 min-w-0 max-w-xs sm:max-w-md">
                            <div class="
                                {{ $message->isFromClient() ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} 
                                rounded-lg p-4 border
                            ">
                                <!-- Message Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium {{ $message->isFromClient() ? 'text-green-900' : 'text-gray-900' }}">
                                            {{ $message->sender_display_name }}
                                        </span>
                                        @if($message->isFromClient())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Client
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                Admin
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $message->sent_at->format('M j, Y g:i A') }}</span>
                                </div>

                                <!-- Message Text -->
                                @if($message->message)
                                    <p class="text-sm {{ $message->isFromClient() ? 'text-green-800' : 'text-gray-700' }} mb-2">
                                        {{ $message->message }}
                                    </p>
                                @endif

                                <!-- File Attachment -->
                                @if($message->hasFile())
                                    <div class="flex items-center space-x-2 p-2 bg-white rounded border">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ $message->file_url }}" 
                                               target="_blank"
                                               class="text-sm font-medium text-primary-600 hover:text-primary-500 truncate block">
                                                {{ $message->file_name }}
                                            </a>
                                            @if($message->formatted_file_size)
                                                <p class="text-xs text-gray-500">{{ $message->formatted_file_size }}</p>
                                            @endif
                                        </div>
                                        <a href="{{ $message->file_url }}" 
                                           download="{{ $message->file_name }}"
                                           class="text-primary-600 hover:text-primary-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                <!-- Read Status for Admin Messages -->
                                @if($message->isFromAdmin())
                                    <div class="mt-2 text-xs text-gray-400">
                                        {{ $message->is_read ? '✓ Read' : 'Sent' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 100px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No messages yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start a conversation by sending a message or document below.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-200 px-6 py-4">
                <form wire:submit="sendMessage" class="space-y-4">
                    {{ $this->sendMessageForm }}
                    
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Sending as: <span class="font-medium text-blue-600">{{ auth()->user()->name }} (Admin)</span>
                        </div>
                        <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                            Send Message
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions for Client Messages -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Client Messages</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Client messages appear on the right side in green. To receive messages from clients, you'll need to:</p>
                        <ul class="mt-1 list-disc list-inside">
                            <li>Provide clients with a way to send messages (web form, email integration, etc.)</li>
                            <li>Set up a webhook or API endpoint to receive client messages</li>
                            <li>Process incoming messages and store them with sender_type='client'</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-scroll to bottom of messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });

        // Auto-scroll after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            setTimeout(() => {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        });
    </script>
</x-filament-panels::page>