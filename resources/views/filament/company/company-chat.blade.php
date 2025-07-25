<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Company Info Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                            <div
                                class="p-2 bg-white dark:bg-gray-700 rounded-full border border-primary-600 dark:border-primary-400">
                                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $record->company_name }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->company_email }}</p>
                    </div>
                </div>

                <!-- Chat Stats -->
                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <span>{{ $this->getChatMessages()->count() }} messages</span>
                    </div>
                    @if ($this->getUnreadClientMessages() > 0)
                        <div class="flex items-center space-x-1 text-red-600 dark:text-red-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $this->getUnreadClientMessages() }} unread</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Messages Container -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <!-- Chat Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Chat History</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Two-way communication with this company</p>
            </div>

            <!-- Messages Area -->
            <div class="h-48 overflow-y-auto p-6 space-y-4" id="messages-container">
                @forelse($this->getChatMessages() as $message)
                    <div
                        class="flex items-start space-x-3 {{ $message->isFromAdmin() ? 'flex-row-reverse space-x-reverse' : '' }}">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-white">
                                    {{ substr($message->sender_display_name, 0, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div class="flex-1 min-w-0 max-w-md sm:max-w-md">
                            <div
                                class="
                                {{ $message->isFromAdmin() ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' }} 
                                rounded-lg p-4 border
                            ">
                                <!-- Message Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="text-sm font-medium {{ $message->isFromAdmin() ? 'text-blue-900 dark:text-blue-100' : 'text-green-900 dark:text-green-100' }}">
                                            {{ $message->sender_display_name }}
                                        </span>
                                    </div>
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M j, Y g:i A') }}</span>
                                </div>

                                <!-- Message Text -->
                                @if ($message->message)
                                    <p
                                        class="text-sm {{ $message->isFromAdmin() ? 'text-blue-800 dark:text-blue-200' : 'text-green-800 dark:text-green-200' }} mb-2">
                                        {{ $message->message }}
                                    </p>
                                @endif

                                <!-- File Attachment -->
                                @if ($message->hasFile())
                                    @if ($message->requires_signature && !$message->is_signed)
                                        <!-- File requires signature (admin view) -->
                                        <div class="mt-3 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-orange-900 dark:text-orange-100 truncate">
                                                            {{ $message->file_name }}
                                                        </p>
                                                        <div class="flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1.5 flex-shrink-0" style="color: #9ACD32;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-xs font-medium" style="color: #9ACD32;">
                                                                Signature Required - Awaiting Client Signature
                                                            </span>
                                                        </div>
                                                        @if ($message->formatted_file_size)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $message->formatted_file_size }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex flex-col space-y-2 flex-shrink-0">
                                                    <a href="{{ $message->file_url }}" target="_blank" 
                                                       class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View PDF
                                                    </a>
                                                    <a href="{{ $message->download_url }}" 
                                                       class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($message->is_signed)
                                        <!-- File is signed -->
                                        <div class="mt-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-green-900 dark:text-green-100 truncate">
                                                            {{ $message->file_name }}
                                                        </p>
                                                        <div class="flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1.5 flex-shrink-0" style="color: #9ACD32;" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-xs font-medium" style="color: #9ACD32;">
                                                                Signed by {{ $message->signer_full_name }} on {{ $message->signed_at->format('M j, Y') }}
                                                            </span>
                                                        </div>
                                                        @if ($message->formatted_file_size)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $message->formatted_file_size }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex flex-col space-y-2 flex-shrink-0">
                                                    <a href="{{ $message->download_url }}" 
                                                       class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Original
                                                    </a>
                                                    <a href="{{ $message->signed_file_url }}" target="_blank" 
                                                       class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-green-600 dark:bg-green-700 border border-green-600 dark:border-green-700 rounded-md hover:bg-green-700 dark:hover:bg-green-800 shadow-sm transition-colors">
                                                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Signed PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Regular file -->
                                        <div class="mt-3 p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                            {{ $message->file_name }}
                                                        </p>
                                                        @if ($message->formatted_file_size)
                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                                {{ $message->formatted_file_size }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ $message->download_url }}" 
                                                       class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" style="width: 100px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No messages yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start a conversation by sending a
                            message or document
                            below.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                <form wire:submit="sendMessage" class="space-y-4">
                    {{ $this->sendMessageForm }}

                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Sending as: <span
                                class="font-medium text-blue-600 dark:text-blue-400">{{ auth()->user()->name }}
                                (Admin)</span>
                        </div>
                        <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                            Send Message
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions for Client Messages -->
        {{-- <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Client Messages</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Client messages appear on the left side in green. Admin messages appear on the right side in
                            blue. To receive messages from clients, you'll
                            need to:</p>
                        <ul class="mt-1 list-disc list-inside">
                            <li>Provide clients with a way to send messages (web form, email integration, etc.)</li>
                            <li>Set up a webhook or API endpoint to receive client messages</li>
                            <li>Process incoming messages and store them with sender_type='client'</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Auto-scroll to bottom of messages -->
    <script>
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
        });

        // Try multiple Livewire event listeners for different versions
        document.addEventListener('livewire:update', function() {
            setTimeout(scrollToBottom, 100);
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(scrollToBottom, 100);
        });

        // For newer Livewire versions
        document.addEventListener('livewire:navigating', function() {
            setTimeout(scrollToBottom, 100);
        });

        // Additional fallback - listen for any DOM changes in the messages container
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    setTimeout(scrollToBottom, 50);
                }
            });
        });

        // Start observing when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            if (container) {
                observer.observe(container, {
                    childList: true,
                    subtree: true
                });
            }
        });
    </script>

    <script>
        $wire.on('reload-page', () => {
            window.location.reload();
        });
    </script>

</x-filament-panels::page>