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
                                        data-company-name="{{ $company->company_name }}"
                                        role="button"
                                        aria-label="Select {{ $company->company_name }} for messaging">
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
                                                    id="unreadCount-{{ $company->id }}"
                                                    aria-label="Unread messages">
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
                                <i class="ph-duotone ph-buildings f-40 text-muted" aria-hidden="true"></i>
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
                                data-company-name="Self Assessment - {{ $selfAssessment->assessment_name }}"
                                role="button"
                                aria-label="Select self assessment for messaging">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $selfAssessment->assessment_name }}</h6>
                                        <small class="text-muted">Personal Assessment</small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="badge bg-success rounded-pill d-none"
                                            id="unreadCount-self-assessment-{{ $selfAssessment->id }}"
                                            aria-label="Unread messages">
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
                                <i class="ph-duotone ph-buildings f-18" aria-hidden="true"></i>
                            </div>
                            <div>
                                <h6 class="mb-0" id="currentCompanyName">Select a company to start messaging</h6>
                                <small class="text-muted" id="chatStatus">Ready to send messages</small>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="card-body p-0">
                        <div id="messagesContainer" class="position-relative" style="height: 400px; overflow-y: auto;" role="log" aria-live="polite" aria-label="Chat messages">
                            <!-- Welcome message when no company selected -->
                            <div id="welcomeMessage" class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="ph-duotone ph-chat-circle f-40 text-muted" aria-hidden="true"></i>
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
                                    <i class="ph-duotone ph-paperclip me-2" aria-hidden="true"></i>
                                    <span id="fileName">No file selected</span>
                                    <button type="button" class="btn-close ms-auto" id="removeFile" aria-label="Remove file"></button>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="messageText" class="visually-hidden">Type your message</label>
                                <input type="text" class="form-control" id="messageText" name="message"
                                    placeholder="Type your message..." maxlength="1000" autocomplete="off">

                                <input type="file" id="messageFile" name="file" class="d-none"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.txt"
                                    aria-label="Attach file">

                                <button type="button" class="btn btn-outline-secondary" id="attachFileBtn"
                                    title="Attach File" aria-label="Attach File">
                                    <i class="ph-duotone ph-paperclip" aria-hidden="true"></i>
                                </button>

                                <button type="submit" class="btn btn-primary" id="sendBtn" aria-label="Send message">
                                    <i class="ph-duotone ph-paper-plane-tilt me-1" aria-hidden="true"></i>
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
<div class="modal fade" id="documentSignatureModal" tabindex="-1" aria-labelledby="documentSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header signature-header">
                <h5 class="modal-title" id="documentSignatureModalLabel">
                    <i class="ph-duotone ph-signature me-2" aria-hidden="true"></i>Sign Document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="documentSignatureForm" novalidate>
                @csrf
                <input type="hidden" id="chat_message_id" name="chat_message_id">
                <input type="hidden" id="message_id" name="message_id">
                
                <div class="modal-body">
                    <div class="signature-form">
                        <div class="alert alert-info" role="alert">
                            <i class="ph-duotone ph-info me-2" aria-hidden="true"></i>
                            You are about to sign this document. Please fill in your details below.
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_full_name" class="form-label">
                                <i class="ph-duotone ph-user me-1" aria-hidden="true"></i>Full Name *
                            </label>
                            <input type="text" class="form-control" id="signer_full_name" name="signer_full_name"
                                   required placeholder="Enter your full legal name" autocomplete="name">
                            <div class="invalid-feedback">Please enter your full legal name.</div>
                            <small class="form-text text-muted">Enter your name as it appears on legal documents</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_print_name" class="form-label">
                                <i class="ph-duotone ph-signature me-1" aria-hidden="true"></i>Print Name *
                            </label>
                            <input type="text" class="form-control" id="signer_print_name" name="signer_print_name"
                                   required placeholder="Enter your printed name" autocomplete="name">
                            <div class="invalid-feedback">Please enter your printed name.</div>
                            <small class="form-text text-muted">How your name should appear in print</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_email" class="form-label">
                                <i class="ph-duotone ph-envelope me-1" aria-hidden="true"></i>Email Address *
                            </label>
                            <input type="email" class="form-control" id="signer_email" name="signer_email"
                                   required placeholder="Enter your email address" autocomplete="email">
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                            <small class="form-text text-muted">We'll send a copy of the signed document to this email</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signed_date" class="form-label">
                                <i class="ph-duotone ph-calendar me-1" aria-hidden="true"></i>Signature Date *
                            </label>
                            <input type="date" class="form-control" id="signed_date" name="signed_date"
                                   required value="{{ date('Y-m-d') }}">
                            <div class="invalid-feedback">Please select a signature date.</div>
                            <small class="form-text text-muted">Date you are signing this document</small>
                        </div>
                        
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="confirmDocSignature" required>
                            <label class="form-check-label" for="confirmDocSignature">
                                I confirm that I have read and understand the document and agree to its terms and conditions.
                            </label>
                            <div class="invalid-feedback">You must confirm that you have read and agree to the document terms.</div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer signature-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ph-duotone ph-x me-1" aria-hidden="true"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning" id="submitDocSignature">
                        <i class="ph-duotone ph-signature me-1" aria-hidden="true"></i>Sign Document
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="docSigningSpinner" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Enhanced PDF Modal with PDF.js --}}
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mr-10" id="pdfModalLabel">PDF Viewer</h5>
                <div class="btn-group" role="group" aria-label="PDF zoom controls">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOut" title="Zoom Out" aria-label="Zoom Out">
                        <i class="ti ti-minus" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fitWidth" title="Fit to Width" aria-label="Fit to Width">
                        <i class="ti ti-arrows-horizontal" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fitPage" title="Fit to Page" aria-label="Fit to Page">
                        <i class="ti ti-arrows-maximize" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomIn" title="Zoom In" aria-label="Zoom In">
                        <i class="ti ti-plus" aria-hidden="true"></i>
                    </button>
                    <span class="btn btn-sm btn-outline-secondary" id="zoomLevel" aria-label="Current zoom level">100%</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                {{-- Loading indicator --}}
                <div id="pdfLoading" class="pdf-loading">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading PDF...</p>
                    </div>
                </div>

                {{-- Error message --}}
                <div id="pdfError" class="pdf-error d-none">
                    <div class="alert alert-danger m-3" role="alert">
                        <h4 class="alert-heading">Error Loading PDF</h4>
                        <p class="mb-0">Unable to load the PDF file. Please try again or contact support if the problem persists.</p>
                    </div>
                </div>

                {{-- PDF Container --}}
                <div id="pdfContainer" class="pdf-container d-none">
                    <canvas id="pdfCanvas" aria-label="PDF document viewer"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex align-items-center" role="group" aria-label="PDF navigation controls">
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="firstPage" title="First Page" aria-label="Go to first page">
                            <i class="ti ti-chevrons-left" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="prevPage" title="Previous Page" aria-label="Go to previous page">
                            <i class="ti ti-chevron-left" aria-hidden="true"></i>
                        </button>
                        <label for="pageInput" class="me-2">Page</label>
                        <input type="number" class="form-control form-control-sm me-2" id="pageInput" style="width: 80px;" min="1" aria-label="Current page number">
                        <span class="me-2">of <span id="totalPages">-</span></span>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="nextPage" title="Next Page" aria-label="Go to next page">
                            <i class="ti ti-chevron-right" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="lastPage" title="Last Page" aria-label="Go to last page">
                            <i class="ti ti-chevrons-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" id="downloadPdf" aria-label="Download PDF">
                            <i class="ti ti-download" aria-hidden="true"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Alert Container -->
<div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;" role="alert" aria-live="assertive"></div>

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

    /* File attachment buttons */
    .file-attachment .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .file-attachment.signature-required {
        animation: pulse-border 2s infinite;
    }

    @keyframes pulse-border {
        0% { border-color: #ffc107; }
        50% { border-color: #fd7e14; }
        100% { border-color: #ffc107; }
    }

    /* Modal styling */
    #pdfModal .modal-xl {
        max-width: 95vw;
    }

    #pdfModal .modal-content {
        height: 90vh;
        display: flex;
        flex-direction: column;
    }

    #pdfModal .modal-body {
        flex: 1;
        overflow: hidden;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    /* PDF Container styling */
    .pdf-container {
        flex: 1;
        overflow: auto;
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
        min-height: 0;
    }

    #pdfCanvas {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        background-color: white;
        max-width: 100%;
        height: auto;
    }

    /* Loading and error states */
    .pdf-loading {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }

    .pdf-error {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }

    /* Custom scrollbar */
    .pdf-container::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    .pdf-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }

    .pdf-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 6px;
    }

    .pdf-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Page input styling */
    #pageInput {
        text-align: center;
    }

    /* Button states */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Signature Modal Styles */
    .signature-header {
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .signature-footer {
        background-color: #f8f9fa;
        border-radius: 0 0 8px 8px;
    }

    .signature-form .form-group {
        margin-bottom: 1.5rem;
    }

    .signature-form label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .signature-form .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        transition: border-color 0.3s;
    }

    .signature-form .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .signature-form .form-control.is-invalid {
        border-color: #dc3545;
    }

    .signature-form .form-control.is-valid {
        border-color: #28a745;
    }

    /* Focus management */
    .modal:focus {
        outline: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #pdfModal .modal-xl {
            max-width: 98vw;
            margin: 1rem;
        }

        #pdfModal .modal-content {
            height: 95vh;
        }

        .modal-header .btn-group {
            flex-wrap: wrap;
            gap: 2px;
        }

        .modal-footer .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        #documentSignatureModal .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .message-bubble {
            max-width: 85%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .file-attachment.signature-required {
            animation: none;
        }
    }
</style>
@endpush

@push('scripts')
{{-- Include PDF.js library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
(function() {
    'use strict';

    // Utility functions
    const Utils = {
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        sanitizeHtml: function(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        },

        formatTime: function(dateString) {
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
        },

        validateEmail: function(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },

        showAlert: function(message, type, duration = 5000) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="ph-duotone ph-${type === 'success' ? 'check-circle' : type === 'danger' ? 'x-circle' : 'warning-circle'} me-2" aria-hidden="true"></i>
                    ${Utils.sanitizeHtml(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            }, duration);
        },

        handleApiError: async function(response) {
            let errorMessage = 'Unknown error occurred';
            
            try {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } else {
                    errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                }
            } catch (parseError) {
                console.error('Error parsing response:', parseError);
                errorMessage = `HTTP ${response.status}: ${response.statusText}`;
            }
            
            throw new Error(errorMessage);
        }
    };

    // Badge update integration functions
    function notifyMessagesRead() {
        window.dispatchEvent(new Event('messages-read'));
    }

    function notifyNewMessageSent() {
        window.dispatchEvent(new Event('new-message-sent'));
    }

    // Form validation
    const FormValidator = {
        validateSignatureForm: function(form) {
            const inputs = form.querySelectorAll('input[required]');
            let isValid = true;

            inputs.forEach(input => {
                const value = input.value.trim();
                let inputValid = true;

                // Reset previous validation states
                input.classList.remove('is-valid', 'is-invalid');

                if (!value) {
                    inputValid = false;
                } else if (input.type === 'email' && !Utils.validateEmail(value)) {
                    inputValid = false;
                } else if (input.type === 'text' && value.length < 2) {
                    inputValid = false;
                }

                if (inputValid) {
                    input.classList.add('is-valid');
                } else {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            });

            // Check checkbox
            const checkbox = form.querySelector('#confirmDocSignature');
            if (!checkbox.checked) {
                checkbox.classList.add('is-invalid');
                isValid = false;
            } else {
                checkbox.classList.remove('is-invalid');
            }

            return isValid;
        }
    };

    // PDF Handler
    const PDFHandler = {
        pdfDoc: null,
        currentPage: 1,
        currentScale: 1,
        fitMode: 'page',
        currentPdfUrl: '',
        canvas: null,
        ctx: null,
        pdfContainer: null,
        pdfLoading: null,
        pdfError: null,
        
        init: function() {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            
            this.canvas = document.getElementById('pdfCanvas');
            this.ctx = this.canvas.getContext('2d');
            this.pdfContainer = document.getElementById('pdfContainer');
            this.pdfLoading = document.getElementById('pdfLoading');
            this.pdfError = document.getElementById('pdfError');
            
            this.bindEvents();
        },

        bindEvents: function() {
            const elements = {
                zoomIn: document.getElementById('zoomIn'),
                zoomOut: document.getElementById('zoomOut'),
                fitWidth: document.getElementById('fitWidth'),
                fitPage: document.getElementById('fitPage'),
                firstPage: document.getElementById('firstPage'),
                prevPage: document.getElementById('prevPage'),
                nextPage: document.getElementById('nextPage'),
                lastPage: document.getElementById('lastPage'),
                pageInput: document.getElementById('pageInput'),
                downloadBtn: document.getElementById('downloadPdf'),
                pdfModal: document.getElementById('pdfModal')
            };

            elements.zoomIn.addEventListener('click', () => this.zoomIn());
            elements.zoomOut.addEventListener('click', () => this.zoomOut());
            elements.fitWidth.addEventListener('click', () => this.fitToWidth());
            elements.fitPage.addEventListener('click', () => this.fitToPage());
            elements.firstPage.addEventListener('click', () => this.goToPage(1));
            elements.prevPage.addEventListener('click', () => this.goToPage(this.currentPage - 1));
            elements.nextPage.addEventListener('click', () => this.goToPage(this.currentPage + 1));
            elements.lastPage.addEventListener('click', () => this.goToPage(this.pdfDoc?.numPages));
            elements.pageInput.addEventListener('change', (e) => this.goToPage(parseInt(e.target.value)));
            elements.downloadBtn.addEventListener('click', () => this.downloadPdf());

            elements.pdfModal.addEventListener('hidden.bs.modal', () => this.resetPdf());
            elements.pdfModal.addEventListener('shown.bs.modal', () => this.handleModalShown());

            window.addEventListener('resize', Utils.debounce(() => this.handleResize(), 250));
        },

        async loadPDF(url) {
            this.currentPdfUrl = url;
            this.showLoading();

            try {
                const pdf = await pdfjsLib.getDocument(url).promise;
                this.pdfDoc = pdf;
                this.currentPage = 1;

                document.getElementById('totalPages').textContent = pdf.numPages;
                const pageInput = document.getElementById('pageInput');
                pageInput.max = pdf.numPages;
                pageInput.value = this.currentPage;

                await this.calculateScale();
                await this.renderPage(this.currentPage);
                this.showPDF();
                this.updateNavigationButtons();

            } catch (error) {
                console.error('Error loading PDF:', error);
                this.showError();
                Utils.showAlert('Failed to load PDF document', 'danger');
            }
        },

        async calculateScale() {
            if (!this.pdfDoc) return;

            const page = await this.pdfDoc.getPage(1);
            const viewport = page.getViewport({ scale: 1 });

            const containerWidth = this.pdfContainer.clientWidth - 40;
            const containerHeight = this.pdfContainer.clientHeight - 40;

            if (this.fitMode === 'width') {
                this.currentScale = containerWidth / viewport.width;
            } else if (this.fitMode === 'page') {
                const scaleX = containerWidth / viewport.width;
                const scaleY = containerHeight / viewport.height;
                this.currentScale = Math.min(scaleX, scaleY);
            }

            this.updateZoomLevel();
        },

        async renderPage(pageNum) {
            if (!this.pdfDoc || pageNum < 1 || pageNum > this.pdfDoc.numPages) return;

            this.currentPage = pageNum;
            document.getElementById('pageInput').value = pageNum;

            try {
                const page = await this.pdfDoc.getPage(pageNum);
                const viewport = page.getViewport({ scale: this.currentScale });

                this.canvas.height = viewport.height;
                this.canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: this.ctx,
                    viewport: viewport
                };

                await page.render(renderContext).promise;
                this.updateNavigationButtons();
            } catch (error) {
                console.error('Error rendering page:', error);
                Utils.showAlert('Failed to render PDF page', 'danger');
            }
        },

        goToPage(pageNum) {
            if (!this.pdfDoc || pageNum < 1 || pageNum > this.pdfDoc.numPages) return;
            this.renderPage(pageNum);
        },

        async zoomIn() {
            if (!this.pdfDoc) return;
            this.fitMode = 'manual';
            this.currentScale = Math.min(this.currentScale * 1.25, 5);
            this.updateZoomLevel();
            await this.renderPage(this.currentPage);
        },

        async zoomOut() {
            if (!this.pdfDoc) return;
            this.fitMode = 'manual';
            this.currentScale = Math.max(this.currentScale / 1.25, 0.1);
            this.updateZoomLevel();
            await this.renderPage(this.currentPage);
        },

        async fitToWidth() {
            if (!this.pdfDoc) return;
            this.fitMode = 'width';
            await this.calculateScale();
            await this.renderPage(this.currentPage);
        },

        async fitToPage() {
            if (!this.pdfDoc) return;
            this.fitMode = 'page';
            await this.calculateScale();
            await this.renderPage(this.currentPage);
        },

        updateNavigationButtons() {
            const buttons = {
                first: document.getElementById('firstPage'),
                prev: document.getElementById('prevPage'),
                next: document.getElementById('nextPage'),
                last: document.getElementById('lastPage')
            };

            buttons.first.disabled = this.currentPage <= 1;
            buttons.prev.disabled = this.currentPage <= 1;
            buttons.next.disabled = this.currentPage >= (this.pdfDoc?.numPages || 0);
            buttons.last.disabled = this.currentPage >= (this.pdfDoc?.numPages || 0);
        },

        updateZoomLevel() {
            document.getElementById('zoomLevel').textContent = Math.round(this.currentScale * 100) + '%';
        },

        showLoading() {
            this.pdfLoading.classList.remove('d-none');
            this.pdfContainer.classList.add('d-none');
            this.pdfError.classList.add('d-none');
        },

        showPDF() {
            this.pdfLoading.classList.add('d-none');
            this.pdfContainer.classList.remove('d-none');
            this.pdfError.classList.add('d-none');
        },

        showError() {
            this.pdfLoading.classList.add('d-none');
            this.pdfContainer.classList.add('d-none');
            this.pdfError.classList.remove('d-none');
        },

        downloadPdf() {
            if (this.currentPdfUrl) {
                const link = document.createElement('a');
                link.href = this.currentPdfUrl;
                link.download = '';
                link.click();
            }
        },

        resetPdf() {
            this.pdfDoc = null;
            this.currentPage = 1;
            this.currentScale = 1;
            this.fitMode = 'page';
            this.currentPdfUrl = '';

            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            document.getElementById('totalPages').textContent = '-';
            document.getElementById('pageInput').value = '';
            this.updateZoomLevel();
            this.showLoading();
        },

        async handleModalShown() {
            if (this.pdfDoc && this.fitMode !== 'manual') {
                await this.calculateScale();
                await this.renderPage(this.currentPage);
            }
        },

        async handleResize() {
            if (this.pdfDoc && this.fitMode !== 'manual') {
                await this.calculateScale();
                await this.renderPage(this.currentPage);
            }
        }
    };

    // Chat Manager
    const ChatManager = {
        currentCompanyId: null,
        isLoading: false,
        messages: [],
        intervals: [],

        init: function() {
            this.bindEvents();
            this.updateUnreadCounts();
            this.startPeriodicUpdates();
            this.autoSelectFirstCompany();
        },

        bindEvents: function() {
            const elements = {
                companyLinks: document.querySelectorAll('.company-chat-link'),
                sendMessageForm: document.getElementById('sendMessageForm'),
                messageText: document.getElementById('messageText'),
                messageFile: document.getElementById('messageFile'),
                attachFileBtn: document.getElementById('attachFileBtn'),
                removeFileBtn: document.getElementById('removeFile'),
                filePreview: document.getElementById('filePreview'),
                signatureModal: document.getElementById('documentSignatureModal'),
                signatureForm: document.getElementById('documentSignatureForm')
            };

            elements.companyLinks.forEach(link => {
                link.addEventListener('click', (e) => this.handleCompanySelect(e));
            });

            elements.sendMessageForm.addEventListener('submit', (e) => this.handleSendMessage(e));
            elements.attachFileBtn.addEventListener('click', () => elements.messageFile.click());
            elements.messageFile.addEventListener('change', (e) => this.handleFileSelect(e));
            elements.removeFileBtn.addEventListener('click', () => this.removeFile());
            elements.signatureModal.addEventListener('hidden.bs.modal', () => this.handleSignatureModalClose());
            elements.signatureForm.addEventListener('submit', (e) => this.handleSignatureSubmit(e));

            // Auto-fill print name when full name is entered
            document.getElementById('signer_full_name').addEventListener('input', function() {
                const printNameField = document.getElementById('signer_print_name');
                if (!printNameField.value) {
                    printNameField.value = this.value;
                }
            });

            // Add keyboard support
            elements.messageText.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    elements.sendMessageForm.dispatchEvent(new Event('submit'));
                }
            });
        },

        handleCompanySelect(e) {
            e.preventDefault();
            const link = e.currentTarget;
            const companyId = link.getAttribute('data-company-id');
            const companyName = link.getAttribute('data-company-name');
            this.selectCompany(companyId, companyName, link);
        },

        selectCompany(companyId, companyName, linkElement) {
            if (this.isLoading || this.currentCompanyId === companyId) return;

            // Update UI
            document.querySelectorAll('.company-chat-link').forEach(link => link.classList.remove('active'));
            linkElement.classList.add('active');

            this.currentCompanyId = companyId;
            window.currentCompanyId = companyId;
            document.getElementById('selectedCompanyId').value = companyId;
            document.getElementById('currentCompanyName').textContent = companyName;

            this.showLoading();
            this.loadMessages();
        },

        showLoading() {
            document.getElementById('welcomeMessage').classList.add('d-none');
            document.getElementById('messagesList').classList.add('d-none');
            document.getElementById('loadingMessages').classList.remove('d-none');
            document.getElementById('messageInput').style.display = 'none';
        },

        showMessages() {
            document.getElementById('welcomeMessage').classList.add('d-none');
            document.getElementById('loadingMessages').classList.add('d-none');
            document.getElementById('messagesList').classList.remove('d-none');
            document.getElementById('messageInput').style.display = 'block';
        },

        async loadMessages() {
            if (!this.currentCompanyId) return;
            
            this.isLoading = true;
            document.getElementById('chatStatus').textContent = 'Loading messages...';

            try {
                let url, params;
                
                if (this.currentCompanyId.startsWith('self-assessment-')) {
                    const selfAssessmentId = this.currentCompanyId.replace('self-assessment-', '');
                    url = "{{ route('client.self-assessment.chat.messages') }}";
                    params = { self_assessment_id: selfAssessmentId };
                } else {
                    url = "{{ route('client.chat.messages') }}";
                    params = { company_id: this.currentCompanyId };
                }
                
                const response = await fetch(url + '?' + new URLSearchParams(params), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    await Utils.handleApiError(response);
                }

                const result = await response.json();

                if (result.success) {
                    this.messages = result.data;
                    this.displayMessages(result.data);
                    document.getElementById('chatStatus').textContent = `${result.data.length} messages loaded`;
                    
                    notifyMessagesRead();
                    
                    setTimeout(() => {
                        this.updateUnreadCounts();
                    }, 500);
                } else {
                    this.displayMessages([]);
                    document.getElementById('chatStatus').textContent = 'No messages found';
                }
            } catch (error) {
                console.error('Error loading messages:', error);
                Utils.showAlert('Error loading messages. Please try again.', 'danger');
                document.getElementById('chatStatus').textContent = 'Error loading messages';
            } finally {
                this.isLoading = false;
                this.showMessages();
            }
        },

        displayMessages(messages) {
            const messagesList = document.getElementById('messagesList');
            messagesList.innerHTML = '';

            if (messages.length === 0) {
                messagesList.innerHTML = `
                    <div class="text-center py-4">
                        <i class="ph-duotone ph-chat-circle f-40 text-muted" aria-hidden="true"></i>
                        <p class="text-muted mt-2">No messages yet. Start the conversation!</p>
                    </div>
                `;
                return;
            }

            messages.forEach(message => {
                const messageElement = this.createMessageElement(message);
                messagesList.appendChild(messageElement);
            });

            this.scrollToBottom();
        },

        createMessageElement(message) {
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
                fileAttachment = this.createFileAttachment(message);
            }

            const sanitizedMessage = message.message ? Utils.sanitizeHtml(message.message) : '';

            messageDiv.innerHTML = `
                <div class="message-content">
                    ${senderBadge}
                    ${sanitizedMessage ? `<div class="message-text">${sanitizedMessage}</div>` : ''}
                    ${fileAttachment}
                    <div class="message-time">${Utils.formatTime(message.sent_at)}</div>
                </div>
            `;

            return messageDiv;
        },

        createFileAttachment(message) {
            if (message.requires_signature && !message.is_signed && message.sender_type === 'admin') {
                return `
                    <div class="file-attachment signature-required">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file-text me-2 text-warning" aria-hidden="true"></i>
                                    <div>
                                        <strong>${Utils.sanitizeHtml(message.file_name)}</strong>
                                        <div class="text-warning small">
                                            <i class="ph-duotone ph-warning-circle me-1" aria-hidden="true"></i>
                                            Signature Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="ChatManager.viewPdf('${message.file_url}')" title="View Document" aria-label="View Document">
                                    <i class="ti ti-eye" aria-hidden="true"></i> View
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="ChatManager.openSignatureModal(${message.id})" aria-label="Sign Document">
                                    <i class="ti ti-writing-sign me-1" aria-hidden="true"></i> Sign
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (message.is_signed) {
                const signedDate = message.signed_at ? new Date(message.signed_at).toLocaleDateString() : '';
                const signerName = Utils.sanitizeHtml(message.signer_full_name || '');
                return `
                    <div class="file-attachment signed">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file-check me-2 text-success" aria-hidden="true"></i>
                                    <div>
                                        <strong>${Utils.sanitizeHtml(message.file_name)}</strong>
                                        <div class="text-success small">
                                            <i class="ph-duotone ph-check-circle me-1" aria-hidden="true"></i>
                                            Signed by ${signerName}${signedDate ? ' on ' + signedDate : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="ChatManager.viewPdf('${message.file_url}')" title="View Original" aria-label="View Original Document">
                                    <i class="ti ti-eye" aria-hidden="true"></i> Original
                                </button>
                                <button class="btn btn-success btn-sm" onclick="ChatManager.viewPdf('${message.signed_file_url}')" title="View Signed Document" aria-label="View Signed Document">
                                    <i class="ti ti-file-check" aria-hidden="true"></i> Signed
                                </button>
                                <a href="${message.signed_file_url}" download class="btn btn-outline-success btn-sm" title="Download Signed" aria-label="Download Signed Document">
                                    <i class="ti ti-download" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                const isPdf = message.file_type && message.file_type.includes('pdf');
                let downloadUrl;
                if (this.currentCompanyId.startsWith('self-assessment-')) {
                    downloadUrl = `/client/self-assessment/chat/download/${message.id}`;
                } else {
                    downloadUrl = `/client/chat/download/${message.id}`;
                }
                
                return `
                    <div class="file-attachment">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file me-2" aria-hidden="true"></i>
                                    <span class="text-decoration-none">${Utils.sanitizeHtml(message.file_name)}</span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                ${isPdf ? `
                                    <button class="btn btn-outline-secondary btn-sm" onclick="ChatManager.viewPdf('${message.file_url}')" title="View Document" aria-label="View Document">
                                        <i class="ti ti-eye" aria-hidden="true"></i> View
                                    </button>
                                ` : ''}
                                <a href="${downloadUrl}" class="btn btn-outline-primary btn-sm" title="Download" aria-label="Download File">
                                    <i class="ti ti-download" aria-hidden="true"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }
        },

        viewPdf(url) {
            PDFHandler.loadPDF(url);
            const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
            modal.show();
        },

        openSignatureModal(messageId) {
            document.getElementById('chat_message_id').value = messageId;
            document.getElementById('message_id').value = messageId;
            
            // Reset form validation
            const form = document.getElementById('documentSignatureForm');
            form.querySelectorAll('.form-control').forEach(input => {
                input.classList.remove('is-valid', 'is-invalid');
            });
            
            const modal = new bootstrap.Modal(document.getElementById('documentSignatureModal'));
            modal.show();
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    Utils.showAlert('File size must be less than 10MB', 'warning');
                    e.target.value = '';
                    return;
                }

                document.getElementById('fileName').textContent = file.name;
                document.getElementById('filePreview').classList.remove('d-none');
            }
        },

        removeFile() {
            document.getElementById('messageFile').value = '';
            document.getElementById('filePreview').classList.add('d-none');
        },

        async handleSendMessage(e) {
            e.preventDefault();
            
            const messageText = document.getElementById('messageText');
            const messageFile = document.getElementById('messageFile');
            const message = messageText.value.trim();
            const file = messageFile.files[0];

            if (!message && !file) {
                Utils.showAlert('Please enter a message or select a file to send.', 'warning');
                return;
            }

            if (!this.currentCompanyId) {
                Utils.showAlert('Please select a company or self assessment first.', 'warning');
                return;
            }

            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="ph-duotone ph-spinner-gap" aria-hidden="true"></i> Sending...';

            try {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                if (this.currentCompanyId.startsWith('self-assessment-')) {
                    const selfAssessmentId = this.currentCompanyId.replace('self-assessment-', '');
                    formData.append('self_assessment_id', selfAssessmentId);
                } else {
                    formData.append('company_id', this.currentCompanyId);
                }

                if (message) {
                    formData.append('message', message);
                }

                if (file) {
                    formData.append('file', file);
                }

                let url = this.currentCompanyId.startsWith('self-assessment-') 
                    ? "{{ route('client.self-assessment.chat.send') }}"
                    : "{{ route('client.chat.send') }}";

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    await Utils.handleApiError(response);
                }

                const result = await response.json();

                if (result.success) {
                    messageText.value = '';
                    messageFile.value = '';
                    document.getElementById('filePreview').classList.add('d-none');
                    
                    const newMessage = {
                        id: result.data.message_id,
                        sender_type: 'client',
                        message: message,
                        file_name: file ? file.name : null,
                        sent_at: result.data.sent_at,
                        is_read: false
                    };
                    
                    this.messages.push(newMessage);
                    this.displayMessages(this.messages);
                    Utils.showAlert('Message sent successfully!', 'success');
                    
                    notifyNewMessageSent();
                } else {
                    Utils.showAlert(result.message || 'Failed to send message.', 'danger');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                Utils.showAlert(error.message || 'Network error. Please try again.', 'danger');
            } finally {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="ph-duotone ph-paper-plane-tilt me-1" aria-hidden="true"></i> Send';
            }
        },

        async handleSignatureSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            
            // Validate form
            if (!FormValidator.validateSignatureForm(form)) {
                Utils.showAlert('Please fill in all required fields correctly.', 'warning');
                return;
            }
            
            const submitButton = document.getElementById('submitDocSignature');
            const signingSpinner = document.getElementById('docSigningSpinner');
            
            submitButton.disabled = true;
            signingSpinner.classList.remove('d-none');
            
            try {
                const browserData = {
                    userAgent: navigator.userAgent,
                    platform: navigator.platform,
                    language: navigator.language,
                    vendor: navigator.vendor,
                    screen: `${screen.width}x${screen.height}`,
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                    timestamp: new Date().toISOString()
                };
                
                const formData = new FormData(form);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('browser_data', JSON.stringify(browserData));
                
                let url;
                if (this.currentCompanyId && this.currentCompanyId.toString().startsWith('self-assessment-')) {
                    url = '{{ route("client.self-assessment.chat.sign-document") }}';
                } else {
                    url = '{{ route("client.chat.sign-document") }}';
                }
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    await Utils.handleApiError(response);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Close modal
                    const modalElement = document.getElementById('documentSignatureModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    form.querySelectorAll('.form-control').forEach(input => {
                        input.classList.remove('is-valid', 'is-invalid');
                    });
                    
                    Utils.showAlert('Document has been signed successfully.', 'success');
                    
                    // Reload messages after a short delay
                    setTimeout(() => {
                        this.loadMessages();
                    }, 500);
                } else {
                    throw new Error(result.message || 'Failed to sign document');
                }
            } catch (error) {
                console.error('Error signing document:', error);
                Utils.showAlert(error.message || 'An error occurred while signing the document. Please try again.', 'danger');
            } finally {
                submitButton.disabled = false;
                signingSpinner.classList.add('d-none');
            }
        },

        handleSignatureModalClose() {
            if (this.currentCompanyId) {
                this.loadMessages();
            }
        },

        async updateUnreadCounts() {
            try {
                const response = await fetch("{{ route('client.chat.unread') }}", {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.includes("application/json")) {
                        const result = await response.json();
                        if (result.success && result.data) {
                            // Update all badges to 0 first (hide them)
                            document.querySelectorAll('[id^="unreadCount-"]').forEach(badge => {
                                badge.textContent = '0';
                                badge.classList.add('d-none');
                            });
                            
                            // Update badges with actual counts
                            Object.entries(result.data).forEach(([key, count]) => {
                                let badgeId = '';
                                
                                if (key.startsWith('company_')) {
                                    const companyId = key.replace('company_', '');
                                    badgeId = `unreadCount-${companyId}`;
                                } else if (key === 'self_assessment') {
                                    @if($selfAssessment)
                                    badgeId = 'unreadCount-self-assessment-{{ $selfAssessment->id }}';
                                    @endif
                                }
                                
                                if (badgeId) {
                                    const badge = document.getElementById(badgeId);
                                    if (badge) {
                                        badge.textContent = count;
                                        badge.classList.toggle('d-none', count === 0);
                                    }
                                }
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error updating unread counts:', error);
            }
        },

        scrollToBottom() {
            const messagesContainer = document.getElementById('messagesContainer');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        },

        autoSelectFirstCompany() {
            const companyLinks = document.querySelectorAll('.company-chat-link');
            if (companyLinks.length > 0) {
                const firstCompany = companyLinks[0];
                const companyId = firstCompany.getAttribute('data-company-id');
                const companyName = firstCompany.getAttribute('data-company-name');
                this.selectCompany(companyId, companyName, firstCompany);
            }
        },

        startPeriodicUpdates() {
            // Refresh messages every 30 seconds
            this.intervals.push(setInterval(() => {
                if (this.currentCompanyId && !this.isLoading) {
                    this.loadMessages();
                }
            }, 30000));

            // Update unread counts every 30 seconds
            this.intervals.push(setInterval(() => {
                this.updateUnreadCounts();
            }, 30000));
        },

        cleanup() {
            this.intervals.forEach(interval => clearInterval(interval));
            this.intervals = [];
        }
    };

    // Global functions for onclick handlers
    window.ChatManager = ChatManager;
    window.openSignatureModal = function(messageId) {
        ChatManager.openSignatureModal(messageId);
    };
    window.viewPdfInApp = function(url) {
        ChatManager.viewPdf(url);
    };

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        try {
            PDFHandler.init();
            ChatManager.init();
        } catch (error) {
            console.error('Initialization error:', error);
            Utils.showAlert('Failed to initialize chat application. Please refresh the page.', 'danger');
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        ChatManager.cleanup();
    });

    // Handle visibility change to update counts when user returns to tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && ChatManager.currentCompanyId) {
            ChatManager.updateUnreadCounts();
        }
    });

})();
</script>
@endpush