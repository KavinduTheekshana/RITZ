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
                                data-company-id="self-assessment-{{ $selfAssessment->id }}"
                                data-company-name="Self Assessment - {{ $selfAssessment->assessment_name }}">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $selfAssessment->assessment_name }}</h6>
                                        <small class="text-muted">Personal Assessment</small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="badge bg-success rounded-pill d-none"
                                            id="unreadCount-self-assessment-{{ $selfAssessment->id }}">
                                            0
                                        </span>
                                    </div>
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

<!-- Document Signature Modal -->
<div class="modal fade" id="documentSignatureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sign Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="documentSignatureForm">
                <div class="modal-body">
                    <input type="hidden" id="chat_message_id" name="chat_message_id">
                    <input type="hidden" id="message_id" name="message_id">
                    
                    <div class="alert alert-info">
                        <i class="ph-duotone ph-info me-2"></i>
                        This document requires your signature. Please fill in your details below.
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="signer_full_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Print Name *</label>
                            <input type="text" class="form-control" name="signer_print_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="signer_email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date *</label>
                            <input type="date" class="form-control" name="signed_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmDocSignature" required>
                                <label class="form-check-label" for="confirmDocSignature">
                                    I confirm that I have read and agree to sign this document
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Sign Document</button>
                </div>
            </form>
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

    .message-bubble.system {
        max-width: 100%;
        margin: 1rem auto;
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

    .message-bubble.system .message-content {
        background-color: #e9ecef;
        color: #6c757d;
        font-style: italic;
        text-align: center;
        border-radius: 1rem;
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

    .message-bubble.system .message-time {
        text-align: center;
    }

    .file-attachment {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-top: 0.5rem;
        border: 1px dashed rgba(255, 255, 255, 0.3);
    }

    .message-bubble.received .file-attachment {
        background-color: var(--bs-light);
        border-color: var(--bs-border-color);
    }

    .file-attachment.signature-required {
        border: 2px solid #ffc107;
        background-color: #fff8e1;
    }

    .file-attachment.signed {
        border: 2px solid #28a745;
        background-color: #d4edda;
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
// Global functions that need to be accessible from onclick handlers
function openSignatureModal(messageId) {
    document.getElementById('chat_message_id').value = messageId;
    document.getElementById('message_id').value = messageId;
    const modal = new bootstrap.Modal(document.getElementById('documentSignatureModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    let currentCompanyId = null;
    let isLoading = false;
    let messages = [];

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

    // Make currentCompanyId accessible to other functions
    window.currentCompanyId = currentCompanyId;

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

    // Handle signature form submission
    document.getElementById('documentSignatureForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Add browser data
        formData.append('browser_data', JSON.stringify({
            userAgent: navigator.userAgent,
            language: navigator.language,
            platform: navigator.platform,
            vendor: navigator.vendor
        }));
        
        try {
            let url;
            if (currentCompanyId && currentCompanyId.startsWith('self-assessment-')) {
                url = '{{ route("client.self-assessment.chat.sign-document") }}';
            } else {
                url = '{{ route("client.chat.sign-document") }}';
            }
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('documentSignatureModal')).hide();
                showAlert('Document signed successfully!', 'success');
                await loadMessages();
            } else {
                showAlert(result.message || 'Failed to sign document', 'danger');
            }
        } catch (error) {
            console.error('Error signing document:', error);
            showAlert('An error occurred while signing the document', 'danger');
        }
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
        window.currentCompanyId = companyId; // Update global variable
        selectedCompanyId.value = companyId;
        currentCompanyName.textContent = companyName;

        // Show loading state
        showLoading();

        // Load messages
        loadMessages();
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

    async function loadMessages() {
        if (!currentCompanyId) return;
        
        isLoading = true;
        chatStatus.textContent = 'Loading messages...';

        try {
            let url, params;
            
            if (currentCompanyId.startsWith('self-assessment-')) {
                // Handle self-assessment messages
                const selfAssessmentId = currentCompanyId.replace('self-assessment-', '');
                url = "{{ route('client.self-assessment.chat.messages') }}";
                params = { self_assessment_id: selfAssessmentId };
            } else {
                // Handle company messages
                url = "{{ route('client.chat.messages') }}";
                params = { company_id: currentCompanyId };
            }
            
            const response = await fetch(url + '?' + new URLSearchParams(params), {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                messages = result.data;
                displayMessages(result.data);
                chatStatus.textContent = `${result.data.length} messages loaded`;
                updateUnreadCounts();
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

        messages.forEach(message => {
            const messageElement = createMessageElement(message);
            messagesList.appendChild(messageElement);
        });

        scrollToBottom();
    }

    function createMessageElement(message) {
        const isFromClient = message.sender_type === 'client';
        const isSystemMessage = message.sender_type === 'system';
        const messageDiv = document.createElement('div');
        
        if (isSystemMessage) {
            messageDiv.className = 'message-bubble system';
        } else {
            messageDiv.className = `message-bubble ${isFromClient ? 'sent' : 'received'}`;
        }

        let senderBadge = '';
        if (isSystemMessage) {
            senderBadge = '<span class="sender-badge bg-info text-white">System</span>';
        } else {
            senderBadge = isFromClient ?
                '<span class="sender-badge bg-light text-dark">You</span>' :
                '<span class="sender-badge bg-secondary text-white">Admin</span>';
        }

        let fileAttachment = '';
        if (message.file_name) {
            if (message.requires_signature && !message.is_signed && message.sender_type === 'admin') {
                fileAttachment = `
                    <div class="file-attachment signature-required">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file-text me-2 text-warning"></i>
                                    <div>
                                        <strong>${message.file_name}</strong>
                                        <div class="text-warning small">
                                            <i class="ph-duotone ph-warning-circle me-1"></i>
                                            Signature Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="${message.file_url}" target="_blank" class="btn btn-outline-secondary btn-sm" title="View Original">
                                    <i class="ph-duotone ph-eye"></i>
                                </a>
                                <button class="btn btn-warning btn-sm" onclick="openSignatureModal(${message.id})">
                                    <i class="ph-duotone ph-signature me-1"></i> Sign
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (message.is_signed) {
                const signedDate = message.signed_at ? new Date(message.signed_at).toLocaleDateString() : '';
                fileAttachment = `
                    <div class="file-attachment signed">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file-check me-2 text-success"></i>
                                    <div>
                                        <strong>${message.file_name}</strong>
                                        <div class="text-success small">
                                            <i class="ph-duotone ph-check-circle me-1"></i>
                                            Signed by ${message.signer_full_name}${signedDate ? ' on ' + signedDate : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="${message.file_url}" target="_blank" class="btn btn-outline-secondary btn-sm" title="View Original">
                                    <i class="ph-duotone ph-file"></i> Original
                                </a>
                                <a href="${message.signed_file_url}" target="_blank" class="btn btn-success btn-sm" title="View Signed Document">
                                    <i class="ph-duotone ph-file-check"></i> Signed
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                fileAttachment = `
                    <div class="file-attachment">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file me-2"></i>
                                    <a href="${message.file_url}" target="_blank" class="text-decoration-none ${isFromClient ? 'text-white' : ''}">
                                        <strong>${message.file_name}</strong>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="${message.file_url}" target="_blank" class="btn btn-outline-secondary btn-sm" title="View">
                                    <i class="ph-duotone ph-eye"></i>
                                </a>
                                <a href="${message.file_url}" download class="btn btn-outline-primary btn-sm" title="Download">
                                    <i class="ph-duotone ph-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        messageDiv.innerHTML = `
            <div class="message-content">
                ${senderBadge}
                ${message.message ? `<div class="message-text">${message.message}</div>` : ''}
                ${fileAttachment}
                <div class="message-time">${formatTime(message.sent_at)}</div>
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
            showAlert('Please select a company or self assessment first.', 'warning');
            return;
        }

        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="ph-duotone ph-spinner-gap"></i> Sending...';

        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            if (currentCompanyId.startsWith('self-assessment-')) {
                const selfAssessmentId = currentCompanyId.replace('self-assessment-', '');
                formData.append('self_assessment_id', selfAssessmentId);
            } else {
                formData.append('company_id', currentCompanyId);
            }

            if (message) {
                formData.append('message', message);
            }

            if (file) {
                formData.append('file', file);
            }

            let url = currentCompanyId.startsWith('self-assessment-') 
                ? "{{ route('client.self-assessment.chat.send') }}"
                : "{{ route('client.chat.send') }}";

            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                messageText.value = '';
                messageFile.value = '';
                filePreview.classList.add('d-none');
                messages.push(result.data);
                displayMessages(messages);
                showAlert('Message sent successfully!', 'success');
            } else {
                showAlert(result.message || 'Failed to send message.', 'danger');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            showAlert('Network error. Please try again.', 'danger');
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="ph-duotone ph-paper-plane-tilt me-1"></i> Send';
        }
    }

    async function updateUnreadCounts() {
        try {
            // Update company unread counts
            const companyResponse = await fetch("{{ route('client.chat.unread') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (companyResponse.ok) {
                const companyResult = await companyResponse.json();
                if (companyResult.success) {
                    Object.entries(companyResult.data).forEach(([companyId, count]) => {
                        const badge = document.getElementById(`unreadCount-${companyId}`);
                        if (badge) {
                            badge.textContent = count;
                            badge.classList.toggle('d-none', count === 0);
                        }
                    });
                }
            }

            // Update self-assessment unread count if applicable
            @if($selfAssessment)
            const selfResponse = await fetch("{{ route('client.self-assessment.chat.unread') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (selfResponse.ok) {
                const selfResult = await selfResponse.json();
                if (selfResult.success) {
                    const badge = document.getElementById('unreadCount-self-assessment-{{ $selfAssessment->id }}');
                    if (badge) {
                        badge.textContent = selfResult.data.self_assessment || 0;
                        badge.classList.toggle('d-none', selfResult.data.self_assessment === 0);
                    }
                }
            }
            @endif
        } catch (error) {
            console.error('Error updating unread counts:', error);
        }
    }

    function formatTime(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        
        if (isNaN(date.getTime())) {
            return 'Invalid date';
        }
        
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
            loadMessages();
        }
    }, 30000);

    // Update unread counts every 60 seconds
    setInterval(() => {
        updateUnreadCounts();
    }, 60000);
});
</script>
@endpush