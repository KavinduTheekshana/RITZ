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
<div class="modal fade" id="documentSignatureModal" tabindex="-1" aria-labelledby="documentSignatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header signature-header">
                <h5 class="modal-title" id="documentSignatureModalLabel">
                    <i class="ph-duotone ph-signature me-2"></i>Sign Document
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="documentSignatureForm">
                <input type="hidden" id="chat_message_id" name="chat_message_id">
                <input type="hidden" id="message_id" name="message_id">
                
                <div class="modal-body">
                    <div class="signature-form">
                        <div class="alert alert-info">
                            <i class="ph-duotone ph-info me-2"></i>
                            You are about to sign this document. Please fill in your details below.
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_full_name" class="form-label">
                                <i class="ph-duotone ph-user me-1"></i>Full Name *
                            </label>
                            <input type="text" class="form-control" id="signer_full_name" name="signer_full_name" 
                                   required placeholder="Enter your full legal name">
                            <small class="form-text text-muted">Enter your name as it appears on legal documents</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_print_name" class="form-label">
                                <i class="ph-duotone ph-signature me-1"></i>Print Name *
                            </label>
                            <input type="text" class="form-control" id="signer_print_name" name="signer_print_name" 
                                   required placeholder="Enter your printed name">
                            <small class="form-text text-muted">How your name should appear in print</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signer_email" class="form-label">
                                <i class="ph-duotone ph-envelope me-1"></i>Email Address *
                            </label>
                            <input type="email" class="form-control" id="signer_email" name="signer_email" 
                                   required placeholder="Enter your email address">
                            <small class="form-text text-muted">We'll send a copy of the signed document to this email</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="signed_date" class="form-label">
                                <i class="ph-duotone ph-calendar me-1"></i>Signature Date *
                            </label>
                            <input type="date" class="form-control" id="signed_date" name="signed_date" 
                                   required value="{{ date('Y-m-d') }}">
                            <small class="form-text text-muted">Date you are signing this document</small>
                        </div>
                        
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="confirmDocSignature" required>
                            <label class="form-check-label" for="confirmDocSignature">
                                I confirm that I have read and understand the document and agree to its terms and conditions.
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer signature-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ph-duotone ph-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning" id="submitDocSignature">
                        <i class="ph-duotone ph-signature me-1"></i>Sign Document
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="docSigningSpinner"></span>
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
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOut" title="Zoom Out">
                        <i class="ti ti-minus"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fitWidth" title="Fit to Width">
                        <i class="ti ti-arrows-horizontal"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="fitPage" title="Fit to Page">
                        <i class="ti ti-arrows-maximize"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomIn" title="Zoom In">
                        <i class="ti ti-plus"></i>
                    </button>
                    <span class="btn btn-sm btn-outline-secondary" id="zoomLevel">100%</span>
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
                    <canvas id="pdfCanvas"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="firstPage" title="First Page">
                            <i class="ti ti-chevrons-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="prevPage" title="Previous Page">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <span class="me-2">Page</span>
                        <input type="number" class="form-control form-control-sm me-2" id="pageInput" style="width: 80px;" min="1">
                        <span class="me-2">of <span id="totalPages">-</span></span>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="nextPage" title="Next Page">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="lastPage" title="Last Page">
                            <i class="ti ti-chevrons-right"></i>
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary" id="downloadPdf">
                            <i class="ti ti-download"></i> Download
                        </button>
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
        background: linear-gradient(135deg, #007bff, #0056b3);
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
    }
</style>
@endpush

@push('scripts')
{{-- Include PDF.js library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
// Global functions that need to be accessible from onclick handlers
function openSignatureModal(messageId) {
    document.getElementById('chat_message_id').value = messageId;
    document.getElementById('message_id').value = messageId;
    const modal = new bootstrap.Modal(document.getElementById('documentSignatureModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // PDF.js configuration
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    // Global variables
    let pdfDoc = null;
    let currentPage = 1;
    let currentScale = 1;
    let fitMode = 'page'; // 'width', 'page', or 'manual'
    let currentPdfUrl = '';

    // DOM elements
    const canvas = document.getElementById('pdfCanvas');
    const ctx = canvas.getContext('2d');
    const pdfContainer = document.getElementById('pdfContainer');
    const pdfLoading = document.getElementById('pdfLoading');
    const pdfError = document.getElementById('pdfError');
    const pageInput = document.getElementById('pageInput');
    const totalPagesSpan = document.getElementById('totalPages');
    const zoomLevelSpan = document.getElementById('zoomLevel');

    // Button elements
    const zoomInBtn = document.getElementById('zoomIn');
    const zoomOutBtn = document.getElementById('zoomOut');
    const fitWidthBtn = document.getElementById('fitWidth');
    const fitPageBtn = document.getElementById('fitPage');
    const firstPageBtn = document.getElementById('firstPage');
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const lastPageBtn = document.getElementById('lastPage');
    const downloadBtn = document.getElementById('downloadPdf');

    // Global function to open PDF viewer
    window.viewPdfInApp = function(url) {
        currentPdfUrl = url;
        loadPDF(url);
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
    };

    // Load PDF function
    async function loadPDF(url) {
        showLoading();

        try {
            const pdf = await pdfjsLib.getDocument(url).promise;
            pdfDoc = pdf;
            currentPage = 1;

            totalPagesSpan.textContent = pdf.numPages;
            pageInput.max = pdf.numPages;
            pageInput.value = currentPage;

            // Calculate initial scale based on fit mode
            await calculateScale();
            await renderPage(currentPage);

            showPDF();
            updateNavigationButtons();

        } catch (error) {
            console.error('Error loading PDF:', error);
            showError();
        }
    }

    // Calculate scale based on fit mode
    async function calculateScale() {
        if (!pdfDoc) return;

        const page = await pdfDoc.getPage(1);
        const viewport = page.getViewport({ scale: 1 });

        const containerWidth = pdfContainer.clientWidth - 40;
        const containerHeight = pdfContainer.clientHeight - 40;

        if (fitMode === 'width') {
            currentScale = containerWidth / viewport.width;
        } else if (fitMode === 'page') {
            const scaleX = containerWidth / viewport.width;
            const scaleY = containerHeight / viewport.height;
            currentScale = Math.min(scaleX, scaleY);
        }

        updateZoomLevel();
    }

    // Render page
    async function renderPage(pageNum) {
        if (!pdfDoc) return;

        currentPage = pageNum;
        pageInput.value = pageNum;

        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale: currentScale });

        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        await page.render(renderContext).promise;
        updateNavigationButtons();
    }

    // Update navigation button states
    function updateNavigationButtons() {
        firstPageBtn.disabled = currentPage <= 1;
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= pdfDoc?.numPages;
        lastPageBtn.disabled = currentPage >= pdfDoc?.numPages;
    }

    // Update zoom level display
    function updateZoomLevel() {
        zoomLevelSpan.textContent = Math.round(currentScale * 100) + '%';
    }

    // Show loading state
    function showLoading() {
        pdfLoading.classList.remove('d-none');
        pdfContainer.classList.add('d-none');
        pdfError.classList.add('d-none');
    }

    // Show PDF
    function showPDF() {
        pdfLoading.classList.add('d-none');
        pdfContainer.classList.remove('d-none');
        pdfError.classList.add('d-none');
    }

    // Show error state
    function showError() {
        pdfLoading.classList.add('d-none');
        pdfContainer.classList.add('d-none');
        pdfError.classList.remove('d-none');
    }

    // Navigation event listeners
    firstPageBtn.addEventListener('click', () => {
        if (pdfDoc && currentPage > 1) {
            renderPage(1);
        }
    });

    prevPageBtn.addEventListener('click', () => {
        if (pdfDoc && currentPage > 1) {
            renderPage(currentPage - 1);
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (pdfDoc && currentPage < pdfDoc.numPages) {
            renderPage(currentPage + 1);
        }
    });

    lastPageBtn.addEventListener('click', () => {
        if (pdfDoc && currentPage < pdfDoc.numPages) {
            renderPage(pdfDoc.numPages);
        }
    });

    // Page input handler
    pageInput.addEventListener('change', () => {
        if (!pdfDoc) return;

        let pageNum = parseInt(pageInput.value);
        if (pageNum < 1 || pageNum > pdfDoc.numPages) {
            pageInput.value = currentPage;
            return;
        }

        renderPage(pageNum);
    });

    // Zoom event listeners
    zoomInBtn.addEventListener('click', async () => {
        if (!pdfDoc) return;

        fitMode = 'manual';
        currentScale *= 1.25;
        currentScale = Math.min(currentScale, 5);
        updateZoomLevel();
        await renderPage(currentPage);
    });

    zoomOutBtn.addEventListener('click', async () => {
        if (!pdfDoc) return;

        fitMode = 'manual';
        currentScale /= 1.25;
        currentScale = Math.max(currentScale, 0.1);
        updateZoomLevel();
        await renderPage(currentPage);
    });

    fitWidthBtn.addEventListener('click', async () => {
        if (!pdfDoc) return;

        fitMode = 'width';
        await calculateScale();
        await renderPage(currentPage);
    });

    fitPageBtn.addEventListener('click', async () => {
        if (!pdfDoc) return;

        fitMode = 'page';
        await calculateScale();
        await renderPage(currentPage);
    });

    // Download button handler
    downloadBtn.addEventListener('click', () => {
        if (currentPdfUrl) {
            const link = document.createElement('a');
            link.href = currentPdfUrl;
            link.download = '';
            link.click();
        }
    });

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        if (!pdfDoc) return;

        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(async () => {
            if (fitMode !== 'manual') {
                await calculateScale();
                await renderPage(currentPage);
            }
        }, 250);
    });

    // Handle modal close
    const pdfModal = document.getElementById('pdfModal');
    pdfModal.addEventListener('hidden.bs.modal', function() {
        // Reset state
        pdfDoc = null;
        currentPage = 1;
        currentScale = 1;
        fitMode = 'page';
        currentPdfUrl = '';

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Reset UI
        totalPagesSpan.textContent = '-';
        pageInput.value = '';
        updateZoomLevel();

        // Show loading state for next time
        showLoading();
    });

    // Handle modal shown (recalculate scale if needed)
    pdfModal.addEventListener('shown.bs.modal', async function() {
        if (pdfDoc && fitMode !== 'manual') {
            await calculateScale();
            await renderPage(currentPage);
        }
    });

    // Chat functionality
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
    document.getElementById('documentSignatureModal').addEventListener('hidden.bs.modal', function () {
        // Reload messages to show the updated signed status
        if (currentCompanyId) {
            loadMessages();
        }
    });

    // Handle signature form submission
    document.getElementById('documentSignatureForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = document.getElementById('submitDocSignature');
        const signingSpinner = document.getElementById('docSigningSpinner');
        
        // Show loading state
        submitButton.disabled = true;
        signingSpinner.classList.remove('d-none');
        
        // Capture browser data
        const browserData = {
            userAgent: navigator.userAgent,
            platform: navigator.platform,
            language: navigator.language,
            vendor: navigator.vendor,
            screen: `${screen.width}x${screen.height}`,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timestamp: new Date().toISOString()
        };
        
        const formData = new FormData(this);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('browser_data', JSON.stringify(browserData));
        
        try {
            let url;
            if (window.currentCompanyId && window.currentCompanyId.toString().startsWith('self-assessment-')) {
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
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new TypeError("Invalid response format");
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Close modal
                const modalElement = document.getElementById('documentSignatureModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                modal.hide();
                
                // Reset form
                this.reset();
                
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="ph-duotone ph-check-circle me-2"></i>
                    <strong>Success!</strong> Document has been signed successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                const chatContainer = document.querySelector('.pc-content') || document.querySelector('.container-fluid');
                if (chatContainer) {
                    chatContainer.insertBefore(alertDiv, chatContainer.firstChild);
                }
                
                // Reload messages after a short delay
                setTimeout(() => {
                    loadMessages();
                }, 500);
                
                // Auto-dismiss alert after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            } else {
                throw new Error(result.message || 'Failed to sign document');
            }
        } catch (error) {
            console.error('Error signing document:', error);
            
            // Show error alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="ph-duotone ph-warning-circle me-2"></i>
                <strong>Error!</strong> ${error.message || 'An error occurred while signing the document. Please try again.'}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.modal-body');
            container.insertBefore(alertDiv, container.firstChild);
        } finally {
            // Reset button state
            submitButton.disabled = false;
            signingSpinner.classList.add('d-none');
        }
    });

    // Auto-fill print name when full name is entered
    document.getElementById('signer_full_name').addEventListener('input', function() {
        const printNameField = document.getElementById('signer_print_name');
        if (!printNameField.value) {
            printNameField.value = this.value;
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

     updateUnreadCounts();
    
    // Update counts every 30 seconds
    setInterval(() => {
        updateUnreadCounts();
    }, 30000);

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
            
            // Update unread counts after a short delay to ensure server has updated
            setTimeout(() => {
                updateUnreadCounts();
            }, 500);
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
                                <button class="btn btn-outline-secondary btn-sm" onclick="viewPdfInApp('${message.file_url}')" title="View Document">
                                    <i class="ti ti-eye"></i> View
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="openSignatureModal(${message.id})">
                                    <i class="ti ti-writing-sign me-1"></i> Sign
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
                                <button class="btn btn-outline-secondary btn-sm" onclick="viewPdfInApp('${message.file_url}')" title="View Original">
                                    <i class="ti ti-eye"></i> Original
                                </button>
                                <button class="btn btn-success btn-sm" onclick="viewPdfInApp('${message.signed_file_url}')" title="View Signed Document">
                                    <i class="ti ti-file-check"></i> Signed
                                </button>
                                <a href="${message.signed_file_url}" download class="btn btn-outline-success btn-sm" title="Download Signed">
                                    <i class="ti ti-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // For regular files, check if it's a PDF
                const isPdf = message.file_type && message.file_type.includes('pdf');
                fileAttachment = `
                    <div class="file-attachment">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <i class="ph-duotone ph-file me-2"></i>
                                    <span class="text-decoration-none ${isFromClient ? '' : 'text-primary'}">
                                        ${message.file_name}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                ${isPdf ? `
                                    <button class="btn btn-outline-secondary btn-sm" onclick="viewPdfInApp('${message.file_url}')" title="View Document">
                                        <i class="ti ti-eye"></i> View
                                    </button>
                                ` : ''}
                                <a href="${message.file_url}" download class="btn btn-outline-primary btn-sm" title="Download">
                                    <i class="ti ti-download"></i> Download
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
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (companyResponse.ok) {
            const contentType = companyResponse.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                const companyResult = await companyResponse.json();
                if (companyResult.success && companyResult.data) {
                    // Update all badges to 0 first (hide them)
                    document.querySelectorAll('[id^="unreadCount-"]').forEach(badge => {
                        badge.textContent = '0';
                        badge.classList.add('d-none');
                    });
                    
                    // Update badges with actual counts
                    Object.entries(companyResult.data).forEach(([key, count]) => {
                        let badgeId = '';
                        
                        if (key.startsWith('company_')) {
                            // Extract company ID from key like 'company_1'
                            const companyId = key.replace('company_', '');
                            badgeId = `unreadCount-${companyId}`;
                        } else if (key === 'self_assessment') {
                            // For self assessment
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