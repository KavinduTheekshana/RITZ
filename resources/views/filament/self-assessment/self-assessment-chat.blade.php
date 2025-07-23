<x-filament-panels::page>
    <div class="fi-ta-panel">
        <!-- Chat Container -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <!-- Messages Container -->
            <div id="messagesContainer" 
                 class="p-6 max-h-[600px] min-h-[400px] overflow-y-auto space-y-4"
                 wire:poll.5s="$refresh">
                
                @forelse($this->messages as $message)
                    <div class="message-wrapper {{ $message->isFromAdmin() ? 'flex justify-end' : 'flex justify-start' }}">
                        <div class="message max-w-[70%] 
                            {{ $message->isFromAdmin() ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' }} 
                            rounded-lg p-4 border">
                            
                            <!-- Message Header -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium {{ $message->isFromAdmin() ? 'text-blue-900 dark:text-blue-100' : 'text-green-900 dark:text-green-100' }}">
                                        {{ $message->sender_display_name }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->sent_at->format('M j, Y g:i A') }}
                                </span>
                            </div>

                            <!-- Message Text -->
                            @if($message->message)
                                <p class="text-sm {{ $message->isFromAdmin() ? 'text-blue-800 dark:text-blue-200' : 'text-green-800 dark:text-green-200' }} mb-2">
                                    {{ $message->message }}
                                </p>
                            @endif

                            <!-- File Attachment -->
                            @if($message->hasFile())
                                <div class="mt-2">
                                    @if($message->requires_signature && !$message->is_signed)
                                        <!-- File requires signature -->
                                        <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded border border-orange-200 dark:border-orange-800">
                                            <div class="flex items-center space-x-2">
                                                <x-heroicon-o-document-text class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                                                <div>
                                                    <p class="text-sm font-medium text-orange-900 dark:text-orange-100">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    <p class="text-xs text-orange-600 dark:text-orange-400">
                                                        Signature Required
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="{{ $message->file_url }}" 
                                               target="_blank" 
                                               class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                                View PDF
                                            </a>
                                        </div>
                                    @elseif($message->is_signed)
                                        <!-- File is signed -->
                                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded border border-green-200 dark:border-green-800">
                                            <div class="flex items-center space-x-2">
                                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                                                <div>
                                                    <p class="text-sm font-medium text-green-900 dark:text-green-100">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    <p class="text-xs text-green-600 dark:text-green-400">
                                                        Signed by {{ $message->signer_full_name }} on {{ $message->signed_at->format('M j, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ $message->file_url }}" 
                                                   target="_blank" 
                                                   class="text-xs text-gray-600 dark:text-gray-400 hover:underline">
                                                    Original
                                                </a>
                                                <a href="{{ $message->signed_file_url }}" 
                                                   target="_blank" 
                                                   class="text-sm font-medium text-green-600 dark:text-green-400 hover:underline">
                                                    Signed PDF
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Regular file -->
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center space-x-2">
                                                <x-heroicon-o-paper-clip class="w-5 h-5 text-gray-400" />
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $message->file_name }}
                                                    </p>
                                                    @if($message->formatted_file_size)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $message->formatted_file_size }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ $message->file_url }}" 
                                               target="_blank" 
                                               class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                                Download
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <x-heroicon-o-chat-bubble-bottom-center-text class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                        <p class="text-gray-500 dark:text-gray-400">No messages yet. Send a message to start the conversation.</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                <form wire:submit="sendMessage">
                    {{ $this->sendMessageForm }}
                    
                    <!-- File Upload Preview -->
                    @if($this->uploadedFile)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <x-heroicon-o-paper-clip class="w-5 h-5 text-gray-400" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $this->uploadedFileName }}</span>
                            </div>
                            <button type="button" 
                                    wire:click="removeFile" 
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
            Livewire.hook('morph.updated', ({ el, component }) => {
                const container = document.getElementById('messagesContainer');
                if (container) {
                    // Check if user is near bottom before auto-scrolling
                    const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
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