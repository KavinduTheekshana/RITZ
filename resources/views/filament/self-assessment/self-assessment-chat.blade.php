<x-filament-panels::page>
    <div class="fi-ta-panel">
        <!-- Chat Container -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <!-- Messages Container -->
            <div id="messagesContainer" class="p-6 max-h-[600px] min-h-[400px] overflow-y-auto space-y-4"
                wire:poll.5s="$refresh">

                @forelse($this->messages as $message)
                    <div
                        class="message-wrapper {{ $message->isFromAdmin() ? 'flex justify-end' : 'flex justify-start' }}">
                        <div
                            class="message max-w-[70%] 
                            {{ $message->isFromAdmin() ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' }} 
                            rounded-lg p-4 border">

                            <!-- Message Header -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="text-sm font-medium {{ $message->isFromAdmin() ? 'text-blue-900 dark:text-blue-100' : 'text-green-900 dark:text-green-100' }}">
                                        {{ $message->sender_display_name }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->sent_at->format('M j, Y g:i A') }}
                                </span>
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
                                    <!-- File requires signature -->
                                    <div
                                        class="mt-3 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start space-x-3 flex-1 min-w-0">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-orange-900 dark:text-orange-100 truncate">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    <div class="flex items-center mt-1">
                                                        <svg class="w-3 h-3 mr-1.5 flex-shrink-0"
                                                            style="color: #9ACD32;" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
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
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    View PDF
                                                </a>
                                                <a href="{{ $message->download_url }}"
                                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($message->is_signed)
                                    <!-- File is signed -->
                                    <div
                                        class="mt-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex items-start space-x-3 flex-1 min-w-0">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-green-900 dark:text-green-100 truncate">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    <div class="flex items-center mt-1">
                                                        <svg class="w-3 h-3 mr-1.5 flex-shrink-0"
                                                            style="color: #9ACD32;" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium" style="color: #9ACD32;">
                                                            Signed by {{ $message->signer_full_name }} on
                                                            {{ $message->signed_at->format('M j, Y') }}
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
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    Original
                                                </a>
                                                <a href="{{ $message->signed_file_url }}" target="_blank"
                                                    class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-green-600 dark:bg-green-700 border border-green-600 dark:border-green-700 rounded-md hover:bg-green-700 dark:hover:bg-green-800 shadow-sm transition-colors">
                                                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Signed PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Regular file -->
                                    <div
                                        class="mt-3 p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                <div class="flex-shrink-0">
                                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
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
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
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
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" style="width: 100px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No messages yet. Send a message to start the
                            conversation.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                <form wire:submit="sendMessage">
                    {{ $this->sendMessageForm }}

                    <!-- File Upload Preview -->
                    @if ($this->uploadedFile)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <x-heroicon-o-paper-clip class="w-5 h-5 text-gray-400" />
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300">{{ $this->uploadedFileName }}</span>
                            </div>
                            <button type="button" wire:click="removeFile"
                                class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>
                        </div>
                    @endif

                    <div class="mt-4 flex justify-end">
                        <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                            Send Message
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        #messagesContainer {
            scroll-behavior: smooth;
        }

        .message-wrapper {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- JavaScript for file upload handling and auto-scroll -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Function to scroll to bottom
            function scrollToBottom() {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }

            // Auto-scroll on initial load
            scrollToBottom();

            // Auto-scroll to bottom on new messages
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    // Check if user is near bottom before auto-scrolling
                    const isNearBottom = container.scrollHeight - container.scrollTop - container
                        .clientHeight < 100;
                    if (isNearBottom) {
                        setTimeout(scrollToBottom, 100);
                    }
                }
            });

            // Scroll on refresh
            Livewire.on('$refresh', () => {
                setTimeout(scrollToBottom, 100);
            });
        });

        // Handle file input clearing
        window.addEventListener('clear-file-input', event => {
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
            }
        });

        // Handle file removal
        window.addEventListener('file-removed', event => {
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
            }
        });
    </script>
</x-filament-panels::page>
