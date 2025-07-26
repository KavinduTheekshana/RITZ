@extends('layouts.backend')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
        @section('page_name', 'Engagement Letter')
        @include('backend.components.breadcrumb')

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p>Companies</p>
                                @if ($companyLetter->count())
                                    <ul class="list-unstyled">
                                        @foreach ($companyLetter as $company)
                                            <li class="pb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar avtar-l bg-light-danger flex-shrink-0">
                                                        <i class="ph-duotone ph-file-pdf f-40"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-4">
                                                        <p class="mb-0 text-muted">Document Name:
                                                            {{ $company->file_name }}</p>
                                                        <p class="mb-0 text-muted">Company:
                                                            {{ $company->company->company_name }}
                                                            ({{ $company->company->company_type }})
                                                        </p>
                                                        @if ($company->is_signed)
                                                            <span class="badge bg-light-success">
                                                                <i class="ti ti-check me-1"></i>Signed by
                                                                {{ $company->signer_full_name }}
                                                            </span>
                                                            <small class="text-muted d-block">Signed on:
                                                                {{ \Carbon\Carbon::parse($company->signed_date)->format('M d, Y') }}</small>
                                                        @else
                                                            <span class="badge bg-light-danger">Signature
                                                                Required</span>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        @if ($company->is_signed)
                                                            {{-- Show signed document --}}
                                                            <button type="button"
                                                                class="btn btn-secondary d-inline-flex view-pdf-btn"
                                                                data-bs-toggle="modal" data-bs-target="#pdfModal"
                                                                data-url="{{ asset('storage/' . $company->signed_file_path) }}">
                                                                <i class="ti ti-eye me-1"></i>View Signed
                                                            </button>

                                                            <a href="{{ asset('storage/' . $company->signed_file_path) }}"
                                                                download type="button"
                                                                class="btn btn-success d-inline-flex">
                                                                <i class="ti ti-arrow-big-down me-1"></i>Download Signed
                                                            </a>
                                                        @else
                                                            {{-- Show unsigned document --}}
                                                            <button type="button"
                                                                class="btn btn-secondary d-inline-flex view-pdf-btn"
                                                                data-bs-toggle="modal" data-bs-target="#pdfModal"
                                                                data-url="{{ asset('storage/' . $company->file_path) }}">
                                                                <i class="ti ti-eye me-1"></i>View
                                                            </button>

                                                            <a href="{{ asset('storage/' . $company->file_path) }}"
                                                                download type="button"
                                                                class="btn btn-success d-inline-flex">
                                                                <i class="ti ti-arrow-big-down me-1"></i>Download
                                                            </a>

                                                            <button type="button"
                                                                class="btn btn-warning d-inline-flex sign-document-btn"
                                                                data-bs-toggle="modal" data-bs-target="#signatureModal"
                                                                data-engagement-id="{{ $company->id }}"
                                                                data-engagement-type="company"
                                                                data-entity-name="{{ $company->company->company_name }}">
                                                                <i class="ti ti-writing-sign me-1"></i>Sign Document
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No companies linked.</p>
                                @endif
                                <hr>

                                <p>Self Assessment</p>
                                @if ($selfLetter && count($selfLetter) > 0)
                                    <ul class="list-unstyled">
                                        @foreach ($selfLetter as $letter)
                                            <li class="pb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar avtar-l bg-light-info flex-shrink-0">
                                                        <i class="ph-duotone ph-file-pdf f-40"></i>
                                                    </div>

                                                    <div class="flex-grow-1 ms-4">
                                                        <p class="mb-0 text-muted">Document Name:
                                                            {{ $letter->file_name }}</p>
                                                        <p class="mb-0 text-muted">Assessment:
                                                            {{ $letter->selfAssessment->assessment_name }}
                                                            <span class="badge bg-light-primary ms-2">Self Assessment</span>
                                                        </p>
                                                        @if ($letter->is_signed)
                                                            <span class="badge bg-light-success">
                                                                <i class="ti ti-check me-1"></i>Signed by
                                                                {{ $letter->signer_full_name }}
                                                            </span>
                                                            <small class="text-muted d-block">Signed on:
                                                                {{ \Carbon\Carbon::parse($letter->signed_date)->format('M d, Y') }}</small>
                                                        @else
                                                            <span class="badge bg-light-danger">Signature
                                                                Required</span>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        @if ($letter->is_signed)
                                                            {{-- Show signed document --}}
                                                            <button type="button"
                                                                class="btn btn-secondary d-inline-flex view-pdf-btn"
                                                                data-bs-toggle="modal" data-bs-target="#pdfModal"
                                                                data-url="{{ asset('storage/' . $letter->signed_file_path) }}">
                                                                <i class="ti ti-eye me-1"></i>View Signed
                                                            </button>

                                                            <a href="{{ asset('storage/' . $letter->signed_file_path) }}"
                                                                download type="button"
                                                                class="btn btn-success d-inline-flex">
                                                                <i class="ti ti-arrow-big-down me-1"></i>Download Signed
                                                            </a>
                                                        @else
                                                            {{-- Show unsigned document --}}
                                                            <button type="button"
                                                                class="btn btn-secondary d-inline-flex view-pdf-btn"
                                                                data-bs-toggle="modal" data-bs-target="#pdfModal"
                                                                data-url="{{ asset('storage/' . $letter->file_path) }}">
                                                                <i class="ti ti-eye me-1"></i>View
                                                            </button>

                                                            <a href="{{ asset('storage/' . $letter->file_path) }}"
                                                                download type="button"
                                                                class="btn btn-success d-inline-flex">
                                                                <i class="ti ti-arrow-big-down me-1"></i>Download
                                                            </a>

                                                            <button type="button"
                                                                class="btn btn-warning d-inline-flex sign-document-btn"
                                                                data-bs-toggle="modal" data-bs-target="#signatureModal"
                                                                data-engagement-id="{{ $letter->id }}"
                                                                data-engagement-type="self_assessment"
                                                                data-entity-name="{{ $letter->selfAssessment->assessment_name }}">
                                                                <i class="ti ti-writing-sign me-1"></i>Sign Document
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No self assessment engagement letters found.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                        <p class="mb-0">Unable to load the PDF file. Please try again or contact support if the
                            problem persists.</p>
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
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="firstPage"
                            title="First Page">
                            <i class="ti ti-chevrons-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="prevPage"
                            title="Previous Page">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <span class="me-2">Page</span>
                        <input type="number" class="form-control form-control-sm me-2" id="pageInput"
                            style="width: 80px;" min="1">
                        <span class="me-2">of <span id="totalPages">-</span></span>
                        <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="nextPage"
                            title="Next Page">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="lastPage"
                            title="Last Page">
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

{{-- Signature Modal --}}
<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header signature-header">
                <h5 class="modal-title" id="signatureModalLabel">
                    <i class="ti ti-writing-sign me-2"></i>Sign Engagement Letter
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="signatureForm" action="{{ route('client.engagement.sign') }}" method="POST">
                @csrf
                <input type="hidden" id="engagement_id" name="engagement_id" value="">
                <input type="hidden" id="engagement_type" name="engagement_type" value="">

                <div class="modal-body">
                    <div class="signature-form">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            You are about to sign the engagement letter for <strong><span
                                    id="entityNameDisplay"></span></strong>.
                            Please fill in your details below.
                        </div>

                        <div class="form-group">
                            <label for="signer_full_name" class="form-label">
                                <i class="ti ti-user me-1"></i>Full Name *
                            </label>
                            <input type="text" class="form-control" id="signer_full_name" name="signer_full_name"
                                required placeholder="Enter your full legal name">
                            <small class="form-text text-muted">Enter your name as it appears on legal
                                documents</small>
                        </div>

                        <div class="form-group">
                            <label for="signer_print_name" class="form-label">
                                <i class="ti ti-signature me-1"></i>Print Name *
                            </label>
                            <input type="text" class="form-control" id="signer_print_name"
                                name="signer_print_name" required placeholder="Enter your printed name">
                            <small class="form-text text-muted">How your name should appear in print</small>
                        </div>

                        <div class="form-group">
                            <label for="signer_email" class="form-label">
                                <i class="ti ti-mail me-1"></i>Email Address *
                            </label>
                            <input type="email" class="form-control" id="signer_email" name="signer_email"
                                required placeholder="Enter your email address">
                            <small class="form-text text-muted">We'll send a copy of the signed document to this
                                email</small>
                        </div>

                        <div class="form-group">
                            <label for="signed_date" class="form-label">
                                <i class="ti ti-calendar me-1"></i>Signature Date *
                            </label>
                            <input type="date" class="form-control" id="signed_date" name="signed_date" required
                                value="{{ date('Y-m-d') }}">
                            <small class="form-text text-muted">Date you are signing this document</small>
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="confirmSignature" required>
                            <label class="form-check-label" for="confirmSignature">
                                I confirm that I have read and understand the engagement letter and agree to its terms
                                and conditions.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer signature-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning" id="submitSignature">
                        <i class="ti ti-writing-sign me-1"></i>Sign Document
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="signingSpinner"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
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

            #signatureModal .modal-dialog {
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
    document.addEventListener('DOMContentLoaded', function() {
        // PDF.js configuration
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

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

        // Event listeners for view PDF buttons
        document.querySelectorAll('.view-pdf-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const pdfUrl = this.getAttribute('data-url');
                currentPdfUrl = pdfUrl;
                loadPDF(pdfUrl);
            });
        });

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
            const viewport = page.getViewport({
                scale: 1
            });
            const containerWidth = pdfContainer.clientWidth - 40; // Account for padding
            const containerHeight = pdfContainer.clientHeight - 40;

            if (fitMode === 'width') {
                currentScale = containerWidth / viewport.width;
            } else if (fitMode === 'page') {
                const scaleX = containerWidth / viewport.width;
                const scaleY = containerHeight / viewport.height;
                currentScale = Math.min(scaleX, scaleY);
            }

            currentScale = Math.max(0.1, Math.min(currentScale, 5)); // Limit scale between 0.1 and 5
            updateZoomLevel();
        }

        // Render page function
        async function renderPage(pageNum) {
            if (!pdfDoc) return;

            try {
                const page = await pdfDoc.getPage(pageNum);
                const viewport = page.getViewport({
                    scale: currentScale
                });

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                await page.render(renderContext).promise;

                currentPage = pageNum;
                pageInput.value = currentPage;
                updateNavigationButtons();

            } catch (error) {
                console.error('Error rendering page:', error);
                showError();
            }
        }

        // Update navigation buttons state
        function updateNavigationButtons() {
            if (!pdfDoc) return;

            firstPageBtn.disabled = currentPage <= 1;
            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= pdfDoc.numPages;
            lastPageBtn.disabled = currentPage >= pdfDoc.numPages;
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
                if (fitMode !== 'width') {
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
            fitMode = 'width';
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
            if (pdfDoc && fitMode !== 'width') {
                await calculateScale();
                await renderPage(currentPage);
            }
        });
    });
</script>

<script>
    // Signature modal elements
    const signatureModal = document.getElementById('signatureModal');
    const signatureForm = document.getElementById('signatureForm');
    const engagementIdInput = document.getElementById('engagement_id');
    const engagementTypeInput = document.getElementById('engagement_type');
    const entityNameDisplay = document.getElementById('entityNameDisplay');
    const submitButton = document.getElementById('submitSignature');
    const signingSpinner = document.getElementById('signingSpinner');

    // Event listeners for sign document buttons
    document.querySelectorAll('.sign-document-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const engagementId = this.getAttribute('data-engagement-id');
            const engagementType = this.getAttribute('data-engagement-type');
            const entityName = this.getAttribute('data-entity-name');
            
            engagementIdInput.value = engagementId;
            engagementTypeInput.value = engagementType;
            entityNameDisplay.textContent = entityName;

            // Reset form
            signatureForm.reset();
            document.getElementById('signed_date').value = new Date().toISOString().split('T')[0];
            document.getElementById('engagement_id').value = engagementId;
            document.getElementById('engagement_type').value = engagementType;
        });
    });

    // Handle signature form submission
    signatureForm.addEventListener('submit', function(e) {
        e.preventDefault();

        console.log('Form submission started');

        // Show loading state
        submitButton.disabled = true;
        signingSpinner.classList.remove('d-none');

        // Capture browser data
        const browserData = {
            userAgent: navigator.userAgent,
            platform: navigator.platform,
            language: navigator.language,
            screen: `${screen.width}x${screen.height}`,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            timestamp: new Date().toISOString()
        };

        // Create form data and add browser info
        const formData = new FormData(this);
        formData.append('browser_data', JSON.stringify(browserData));
        
        // Debug: Log all form data including browser data
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // Check if CSRF token exists
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        // Submit form via fetch
        fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        throw new Error('Invalid JSON response: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Parsed response data:', data);
                if (data.success) {
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(signatureModal);
                    modal.hide();

                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="ti ti-check-circle me-2"></i>
                        <strong>Success!</strong> Engagement letter has been signed successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                    // Insert alert at the top of the page
                    const container = document.querySelector('.pc-content');
                    container.insertBefore(alertDiv, container.firstChild);

                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    console.error('Server returned error:', data.message);
                    alert('Error: ' + (data.message || 'Failed to sign document'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                signingSpinner.classList.add('d-none');
            });
    });

    // Auto-fill print name when full name is entered
    document.getElementById('signer_full_name').addEventListener('input', function() {
        const printNameField = document.getElementById('signer_print_name');
        if (!printNameField.value) {
            printNameField.value = this.value;
        }
    });
</script>
@endpush