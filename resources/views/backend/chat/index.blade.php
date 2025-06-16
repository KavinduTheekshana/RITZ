@extends('layouts.backend')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
        @section('page_name', 'Messages')
        @include('backend.components.breadcrumb')

        <div class="row">
            <!-- Company Selection Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Your Companies</h5>
                        <p class="text-muted text-sm mb-0">Select a company to view messages</p>
                    </div>
                    <div class="card-body p-0">
                        @if ($companies->count())
                            <div class="list-group list-group-flush">
                                @foreach ($companies as $company)
                                    <a href="#"
                                        class="list-group-item list-group-item-action company-chat-link {{ $loop->first ? 'active' : '' }}"
                                        data-company-id="{{ $company->id }}"
                                        data-company-name="{{ $company->company_name }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $company->company_name }}</h6>
                                                <p class="mb-1 text-muted small">{{ $company->company_type }}</p>
                                                <small class="text-muted" id="lastMessage-{{ $company->id }}">
                                                    Click to load messages...
                                                </small>
                                            </div>
                                            <div class="d-flex flex-column align-items-end">
                                                <span class="badge bg-primary rounded-pill d-none"
                                                    id="unreadCount-{{ $company->id }}">
                                                    0
                                                </span>
                                                <small class="text-muted" id="lastTime-{{ $company->id }}"></small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-4">
                                <i class="ph-duotone ph-buildings f-40 text-muted"></i>
                                <p class="text-muted mt-2">No companies linked to your account.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Self Assessment Messages (if available) -->
                @if ($selfAssessment)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Self Assessment</h6>
                        </div>
                        <div class="card-body p-0">
                            <a href="#" class="list-group-item list-group-item-action company-chat-link card-padding"
                                data-company-id="self-assessment"
                                data-company-name="Self Assessment - {{ $selfAssessment->assessment_name }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $selfAssessment->assessment_name }}</h6>
                                        <small class="text-muted">Personal Assessment</small>
                                    </div>
                                    <span class="badge bg-success">SA</span>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Chat Interface -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <!-- Chat Header -->
                    <div class="card-header" id="chatHeader">
                        <div class="d-flex align-items-center">
                            <div class="avtar avtar-s bg-primary me-3">
                                <i class="ph-duotone ph-buildings f-18"></i>
                            </div>
                            <div>
                                <h6 class="mb-0" id="currentCompanyName">Select a company to start messaging</h6>
                                <small class="text-muted" id="chatStatus">Ready to send messages</small>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="card-body p-0">
                        <div id="messagesContainer" class="position-relative" style="height: 400px; overflow-y: auto;">
                            <!-- Welcome message when no company selected -->
                            <div id="welcomeMessage" class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="ph-duotone ph-chat-circle f-40 text-muted"></i>
                                    <h6 class="mt-3 text-muted">Welcome to Messages</h6>
                                    <p class="text-muted">Select a company from the sidebar to start a conversation</p>
                                </div>
                            </div>

                            <!-- Loading state -->
                            <div id="loadingMessages"
                                class="d-none d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading messages...</p>
                                </div>
                            </div>

                            <!-- Messages will be loaded here -->
                            <div id="messagesList" class="p-3 d-none">
                                <!-- Messages will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="card-footer" id="messageInput" style="display: none;">
                        <form id="sendMessageForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="selectedCompanyId" name="company_id" value="">

                            <!-- File Preview Area -->
                            <div id="filePreview" class="mb-2 d-none">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="ph-duotone ph-paperclip me-2"></i>
                                    <span id="fileName">No file selected</span>
                                    <button type="button" class="btn-close ms-auto" id="removeFile"></button>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="text" class="form-control" id="messageText" name="message"
                                    placeholder="Type your message..." maxlength="1000">

                                <input type="file" id="messageFile" name="file" class="d-none"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt">

                                <button type="button" class="btn btn-outline-secondary" id="attachFileBtn"
                                    title="Attach File">
                                    <i class="ph-duotone ph-paperclip"></i>
                                </button>

                                <button type="submit" class="btn btn-primary" id="sendBtn">
                                    <i class="ph-duotone ph-paper-plane-tilt me-1"></i>
                                    Send
                                </button>
                            </div>

                            <small class="text-muted">
                                Supported files: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, GIF, TXT (Max: 10MB)
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Alert Container -->
<div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
@endsection

@push('styles')
<style>
    .company-chat-link.active {
        background-color: var(--bs-primary);
        color: white;
    }

    .company-chat-link.active .text-muted {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    #messagesContainer {
        background-color: #f8f9fa;
    }

    .message-bubble {
        max-width: 70%;
        margin-bottom: 1rem;
    }

    .message-bubble.sent {
        margin-left: auto;
    }

    .message-bubble.received {
        margin-right: auto;
    }

    .message-content {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        position: relative;
    }

    .message-bubble.sent .message-content {
        background-color: var(--bs-primary);
        color: white;
        border-bottom-right-radius: 0.25rem;
    }

    .message-bubble.received .message-content {
        background-color: white;
        color: var(--bs-dark);
        border: 1px solid var(--bs-border-color);
        border-bottom-left-radius: 0.25rem;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 0.25rem;
    }

    .message-bubble.sent .message-time {
        text-align: right;
    }

    .message-bubble.received .message-time {
        text-align: left;
    }

    .file-attachment {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        padding: 0.5rem;
        margin-top: 0.5rem;
        border: 1px dashed rgba(255, 255, 255, 0.3);
    }

    .message-bubble.received .file-attachment {
        background-color: var(--bs-light);
        border-color: var(--bs-border-color);
    }

    .sender-badge {
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
        display: inline-block;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    #messagesContainer {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentCompanyId = null;
        let isLoading = false;

        // DOM Elements
        const companyLinks = document.querySelectorAll('.company-chat-link');
        const messagesContainer = document.getElementById('messagesContainer');
        const welcomeMessage = document.getElementById('welcomeMessage');
        const loadingMessages = document.getElementById('loadingMessages');
        const messagesList = document.getElementById('messagesList');
        const messageInput = document.getElementById('messageInput');
        const sendMessageForm = document.getElementById('sendMessageForm');
        const messageText = document.getElementById('messageText');
        const messageFile = document.getElementById('messageFile');
        const attachFileBtn = document.getElementById('attachFileBtn');
        const removeFileBtn = document.getElementById('removeFile');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const currentCompanyName = document.getElementById('currentCompanyName');
        const chatStatus = document.getElementById('chatStatus');
        const selectedCompanyId = document.getElementById('selectedCompanyId');

        // Event Listeners
        companyLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const companyId = this.getAttribute('data-company-id');
                const companyName = this.getAttribute('data-company-name');
                selectCompany(companyId, companyName, this);
            });
        });

        attachFileBtn.addEventListener('click', function() {
            messageFile.click();
        });

        messageFile.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                fileName.textContent = file.name;
                filePreview.classList.remove('d-none');
            }
        });

        removeFileBtn.addEventListener('click', function() {
            messageFile.value = '';
            filePreview.classList.add('d-none');
        });

        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });

        // Auto-select first company if available
        if (companyLinks.length > 0) {
            const firstCompany = companyLinks[0];
            const companyId = firstCompany.getAttribute('data-company-id');
            const companyName = firstCompany.getAttribute('data-company-name');
            selectCompany(companyId, companyName, firstCompany);
        }

        // Functions
        function selectCompany(companyId, companyName, linkElement) {
            if (isLoading || currentCompanyId === companyId) return;

            // Update UI
            companyLinks.forEach(link => link.classList.remove('active'));
            linkElement.classList.add('active');

            currentCompanyId = companyId;
            selectedCompanyId.value = companyId;
            currentCompanyName.textContent = companyName;

            // Show loading state
            showLoading();

            // Load messages
            loadMessages(companyId);
        }

        function showLoading() {
            welcomeMessage.classList.add('d-none');
            messagesList.classList.add('d-none');
            loadingMessages.classList.remove('d-none');
            messageInput.style.display = 'none';
        }

        function showMessages() {
            welcomeMessage.classList.add('d-none');
            loadingMessages.classList.add('d-none');
            messagesList.classList.remove('d-none');
            messageInput.style.display = 'block';
        }

        async function loadMessages(companyId) {
            isLoading = true;
            chatStatus.textContent = 'Loading messages...';

            try {
                const response = await fetch(
                    `/api/client-messages/history?company_id=${companyId}&client_email={{ Auth::guard('client')->user()->email }}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });

                const result = await response.json();

                if (result.success) {
                    displayMessages(result.data);
                    chatStatus.textContent = `${result.data.length} messages loaded`;
                } else {
                    displayMessages([]);
                    chatStatus.textContent = 'No messages found';
                }
            } catch (error) {
                console.error('Error loading messages:', error);
                showAlert('Error loading messages. Please try again.', 'danger');
                chatStatus.textContent = 'Error loading messages';
            } finally {
                isLoading = false;
                showMessages();
            }
        }

        function displayMessages(messages) {
            messagesList.innerHTML = '';

            if (messages.length === 0) {
                messagesList.innerHTML = `
            <div class="text-center py-4">
                <i class="ph-duotone ph-chat-circle f-40 text-muted"></i>
                <p class="text-muted mt-2">No messages yet. Start the conversation!</p>
            </div>
        `;
                return;
            }

            // The messages are already sorted correctly from the API
            // API returns them in chronological order (oldest first)
            messages.forEach(message => {
                const messageElement = createMessageElement(message);
                messagesList.appendChild(messageElement);
            });

            // Scroll to bottom
            scrollToBottom();
        }

        function createMessageElement(message) {
            const isFromClient = message.sender_type === 'client';
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-bubble ${isFromClient ? 'sent' : 'received'}`;

            const senderBadge = isFromClient ?
                '<span class="sender-badge bg-light text-dark">You</span>' :
                '<span class="sender-badge bg-secondary text-white">Admin</span>';

            const fileAttachment = message.file_name ? `
            <div class="file-attachment">
                <div class="d-flex align-items-center">
                    <i class="ph-duotone ph-file me-2"></i>
                    <div class="flex-grow-1">
                        <a href="${message.file_url}" target="_blank" class="text-decoration-none ${isFromClient ? 'text-white' : ''}">
                            <strong>${message.file_name}</strong>
                        </a>
                    </div>
                    <a href="${message.file_url}" download class="btn btn-link btn-sm p-0 ms-2 ${isFromClient ? 'text-white' : ''}">
                        <i class="ph-duotone ph-download"></i>
                    </a>
                </div>
            </div>
        ` : '';

            messageDiv.innerHTML = `
            <div class="message-content">
                ${senderBadge}
                ${message.message ? `<div class="message-text">${message.message}</div>` : ''}
                ${fileAttachment}
                <div class="message-time">${formatTime(message.created_at)}</div>
            </div>
        `;

            return messageDiv;
        }

        async function sendMessage() {
            const message = messageText.value.trim();
            const file = messageFile.files[0];

            if (!message && !file) {
                showAlert('Please enter a message or select a file to send.', 'warning');
                return;
            }

            if (!currentCompanyId) {
                showAlert('Please select a company first.', 'warning');
                return;
            }

            // Disable form
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="ph-duotone ph-spinner-gap"></i> Sending...';

            try {
                const formData = new FormData();
                formData.append('company_id', currentCompanyId);
                formData.append('client_email', '{{ Auth::guard('client')->user()->email }}');
                formData.append('client_name', '{{ Auth::guard('client')->user()->full_name }}');

                if (message) {
                    formData.append('message', message);
                }

                if (file) {
                    formData.append('file', file);
                }

                const response = await fetch('/api/client-messages', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Clear form
                    messageText.value = '';
                    messageFile.value = '';
                    filePreview.classList.add('d-none');

                    // Reload messages
                    loadMessages(currentCompanyId);

                    showAlert('Message sent successfully!', 'success');
                } else {
                    showAlert(result.message || 'Failed to send message.', 'danger');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showAlert('Network error. Please try again.', 'danger');
            } finally {
                // Re-enable form
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="ph-duotone ph-paper-plane-tilt me-1"></i> Send';
            }
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));

            if (diffInMinutes < 1) {
                return 'Just now';
            } else if (diffInMinutes < 60) {
                return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
            } else if (diffInMinutes < 1440) {
                const hours = Math.floor(diffInMinutes / 60);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else {
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function showAlert(message, type) {
            const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-${type === 'success' ? 'check-circle' : type === 'danger' ? 'x-circle' : 'warning-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = alertHtml;

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }

        // Refresh messages every 30 seconds
        setInterval(() => {
            if (currentCompanyId && !isLoading) {
                loadMessages(currentCompanyId);
            }
        }, 30000);
    });
</script>
@endpush
