@extends('layouts.student_create')

@section('title', 'Request Files')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Request Documents</h4>
                </div>
                <div class="card-body">
                    <!-- Reference Request Notification -->
                    @if(isset($referenceRequest))
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-lg mt-1"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="alert-heading">Addressing Previous Request</h5>
                                    <p>Please address the following issues from your previous submission:</p>
                                    <div class="p-3 bg-white rounded border border-info">
                                        <p class="mb-0"><strong>Required Actions:</strong> {{ $referenceRequest->remarks }}</p>
                                    </div>
                                    
                                </div>
                            </div>
                                @if($referenceRequest->file)
                                <p class="mt-2">Original Request: {{ $referenceRequest->file->file_name }}</p>
                                @endif
                            </div>
                    @endif
                    <!-- Multi-step form -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 33.33%" id="progress-bar">
                                    Step 1: Select Documents
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="fileRequestForm" action="{{ route('student.file_requests.store') }}" method="POST">
                        @csrf
                        @if(isset($referenceRequest))
        <input type="hidden" name="reference_id" value="{{ $referenceRequest->id }}">
    @endif
                        <!-- Step 1: Select Documents -->
                        <div class="step-container" id="step1">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>Regular Documents</h5>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
                                @foreach($regularFiles as $file)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">{{ $file->file_name }}</h5>
                                                <button type="button" class="btn btn-primary btn-sm add-document" 
                                                        data-file-id="{{ $file->id }}" 
                                                        data-file-name="{{ $file->file_name }}">
                                                    Add
                                                </button>
                                            </div>
                                            <p class="card-text">
                                                @if($file->requirements->count() > 0)
                                                    {{ $file->requirements->count() }} requirement(s)
                                                @else
                                                    No special requirements
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>Certifications</h5>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="certificateSelect" class="form-label">Select Certificate Type:</label>
                                                <select class="form-select" id="certificateSelect">
                                                    <option value="">-- Select Certificate --</option>
                                                    @foreach($certificationCategories as $category => $types)
                                                        @foreach($types as $type)
                                                            @if($certificateFile = $certificateFiles->firstWhere('file_name', $type))
                                                                <option value="{{ $certificateFile->id }}" data-file-name="{{ $type }}">{{ $type }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-primary add-certificate" disabled>
                                                    Add Certificate
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Selected Certificates</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="selected-certificates-list">
                                                <p class="text-muted text-center" id="no-certificates-message">No certificates selected yet</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Selected Documents Summary</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="selected-documents-list">
                                                <p class="text-muted text-center" id="no-documents-message">No documents selected yet</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-primary btn-lg next-step" id="step1Next" disabled>
                                    Next: Schedule Pickup
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Schedule Pickup -->
                        <div class="step-container" id="step2" style="display: none;">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Schedule Document Pickup</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> Documents will be available for pickup between 
                                                {{ Carbon\Carbon::parse($minDate)->format('M d, Y') }} and 
                                                {{ Carbon\Carbon::parse($maxDate)->format('M d, Y') }} (working days only).
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="preferred_date" class="form-label">Preferred Pickup Date:</label>
                                                <input type="date" class="form-control" id="preferred_date" name="preferred_date"
                                                       min="{{ $minDate }}" max="{{ $maxDate }}" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Time:</label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="preferred_time" 
                                                               id="timeAM" value="AM" checked>
                                                        <label class="form-check-label" for="timeAM">
                                                            Morning (8:00 AM - 12:00 PM)
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="preferred_time" 
                                                               id="timePM" value="PM">
                                                        <label class="form-check-label" for="timePM">
                                                            Afternoon (1:00 PM - 5:00 PM)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-secondary btn-lg prev-step">
                                    Back: Select Documents
                                </button>
                                <button type="button" class="btn btn-primary btn-lg next-step" id="step2Next">
                                    Next: Review & Submit
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Review & Submit -->
                        <div class="step-container" id="step3" style="display: none;">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Review Your Request</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6>Pickup Details:</h6>
                                                    <p><strong>Date:</strong> <span id="summary-date"></span></p>
                                                    <p><strong>Time:</strong> <span id="summary-time"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Request Summary:</h6>
                                                    <p><strong>Total Documents:</strong> <span id="summary-count"></span></p>
                                                    <p><strong>Total Copies:</strong> <span id="summary-copies"></span></p>
                                                </div>
                                            </div>
                                            
                                            <h6>Requested Documents:</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Document</th>
                                                            <th>Copies</th>
                                                            <th>Purpose</th>
                                                            <th>Requirements</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="summary-documents">
                                                        <!-- Will be populated by JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                                <label class="form-check-label" for="agreeTerms">
                                                    I confirm that all information provided is correct and I will bring all required 
                                                    documents on the scheduled pickup date.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-secondary btn-lg prev-step">
                                    Back: Schedule Pickup
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="submitButton" disabled>
                                    Submit Request
                                </button>
                            </div>
                        </div>
                        
                        <!-- Hidden fields for selected files -->
                        <div id="hiddenFields"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Add Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-file-id">
                
                <div class="mb-3">
                    <label for="modal-copies" class="form-label">Number of Copies:</label>
                    <input type="number" class="form-control" id="modal-copies" min="1" max="5" value="1">
                    <div class="form-text">Maximum of 5 copies per request.</div>
                </div>
                
                <div class="mb-3">
                    <label for="modal-reason" class="form-label">Purpose/Reason:</label>
                    <textarea class="form-control" id="modal-reason" rows="3" placeholder="Please specify your purpose for requesting this document"></textarea>
                </div>
                
                <div id="requirements-container">
                    <!-- Requirements will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-add-document">Add to Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Certificate Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificateModalLabel">Add Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-certificate-id">
                
                <div class="mb-3">
                    <label for="certificate-copies" class="form-label">Number of Copies:</label>
                    <input type="number" class="form-control" id="certificate-copies" min="1" max="5" value="1">
                    <div class="form-text">Maximum of 5 copies per request.</div>
                </div>
                
                <div class="mb-3">
                    <label for="certificate-reason" class="form-label">Purpose/Reason:</label>
                    <textarea class="form-control" id="certificate-reason" rows="3" placeholder="Please specify your purpose for requesting this certificate"></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Select School Year/Semester (if applicable):</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select" id="certificate-school-year">
                                <option value="">-- Select School Year --</option>
                                @foreach($schoolYears as $schoolYear)
                                    <option value="{{ $schoolYear->id }}">{{ $schoolYear->year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="certificate-semester">
                                <option value="">-- Select Semester --</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div id="certificate-requirements-container">
                    <!-- Requirements will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-add-certificate">Add to Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- Isolate Bootstrap CSS to this page only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Isolate Bootstrap JS to this page only -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Store selected documents
    let selectedDocuments = {};
    let selectedCertificates = {}; 
    let currentStep = 1;
    
    // Update UI to reflect current step
    function updateStepUI(step) {
        $('.step-container').hide();
        $(`#step${step}`).show();
        
        // Update progress bar
        const progressPercentage = step * 33.33;
        $('#progress-bar').css('width', `${progressPercentage}%`);
        
        switch(step) {
            case 1:
                $('#progress-bar').text('Step 1: Select Documents');
                break;
            case 2:
                $('#progress-bar').text('Step 2: Schedule Pickup');
                break;
            case 3:
                $('#progress-bar').text('Step 3: Review & Submit');
                updateSummary();
                break;
        }
    }
    
    // Navigate to next step
    $('.next-step').on('click', function() {
        currentStep++;
        updateStepUI(currentStep);
    });
    
    // Navigate to previous step
    $('.prev-step').on('click', function() {
        currentStep--;
        updateStepUI(currentStep);
    });
    
    // Enable/disable the Next button based on selection
    function updateNextButton() {
        const totalDocs = Object.keys(selectedDocuments).length + Object.keys(selectedCertificates).length;
        $('#step1Next').prop('disabled', totalDocs === 0);
        
        // Update documents summary section
        updateDocumentsSummary();
    }
    
    // Update the documents summary section
    function updateDocumentsSummary() {
        const $list = $('#selected-documents-list');
        const $noDocs = $('#no-documents-message');
        const $certs = $('#selected-certificates-list');
        const $noCerts = $('#no-certificates-message');
        
        // Clear the current lists
        $list.find('.selected-doc-item').remove();
        $certs.find('.selected-cert-item').remove();
        
        // Check if we have any documents
        const hasDocuments = Object.keys(selectedDocuments).length > 0;
        const hasCertificates = Object.keys(selectedCertificates).length > 0;
        
        $noDocs.toggle(!hasDocuments);
        $noCerts.toggle(!hasCertificates);
        
        // Add regular documents to the list
        if (hasDocuments) {
            Object.values(selectedDocuments).forEach(function(doc) {
                const $item = $(`
                    <div class="selected-doc-item d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
                        <div>
                            <strong>${doc.name}</strong> (${doc.copies} ${doc.copies > 1 ? 'copies' : 'copy'})
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-document" data-id="${doc.id}">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                `);
                $list.append($item);
            });
        }
        
        // Add certificates to the certificates list
        if (hasCertificates) {
            Object.values(selectedCertificates).forEach(function(cert) {
                const $item = $(`
                    <div class="selected-cert-item d-flex justify-content-between align-items-center p-2 mb-2 bg-light rounded">
                        <div>
                            <strong>${cert.name}</strong> (${cert.copies} ${cert.copies > 1 ? 'copies' : 'copy'})
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-certificate" data-id="${cert.id}">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                `);
                $certs.append($item);
            });
        }
        
        // Generate hidden fields for the form
        generateHiddenFields();
    }
    
    // Generate hidden form fields
    function generateHiddenFields() {
        const $container = $('#hiddenFields');
        $container.empty();
        
        // Add regular documents
        Object.values(selectedDocuments).forEach(function(doc) {
            $container.append(`<input type="hidden" name="files[${doc.id}][selected]" value="1">`);
            $container.append(`<input type="hidden" name="files[${doc.id}][copies]" value="${doc.copies}">`);
            $container.append(`<input type="hidden" name="files[${doc.id}][reason]" value="${doc.reason}">`);
            
            // Add requirements if any
            if (doc.requirements && doc.requirements.length > 0) {
                doc.requirements.forEach(function(reqId) {
                    $container.append(`<input type="hidden" name="files[${doc.id}][requirements][]" value="${reqId}">`);
                });
            }
        });
        
        // Add certificates
        Object.values(selectedCertificates).forEach(function(cert) {
            $container.append(`<input type="hidden" name="files[${cert.id}][selected]" value="1">`);
            $container.append(`<input type="hidden" name="files[${cert.id}][copies]" value="${cert.copies}">`);
            $container.append(`<input type="hidden" name="files[${cert.id}][reason]" value="${cert.reason}">`);
            
            // Add school year and semester if selected
            if (cert.schoolYear) {
                $container.append(`<input type="hidden" name="files[${cert.id}][school_year_id]" value="${cert.schoolYear}">`);
            }
            
            if (cert.semester) {
                $container.append(`<input type="hidden" name="files[${cert.id}][semester_id]" value="${cert.semester}">`);
            }
            
            // Add requirements if any
            if (cert.requirements && cert.requirements.length > 0) {
                cert.requirements.forEach(function(reqId) {
                    $container.append(`<input type="hidden" name="files[${cert.id}][requirements][]" value="${reqId}">`);
                });
            }
        });
    }
    
    // Update the final summary page
    function updateSummary() {
        // Update date and time
        const date = $('#preferred_date').val();
        const time = $('input[name="preferred_time"]:checked').val();
        
        $('#summary-date').text(date ? new Date(date).toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}) : '(Not selected)');
        $('#summary-time').text(time === 'AM' ? 'Morning (8:00 AM - 12:00 PM)' : 'Afternoon (1:00 PM - 4:00 PM)');
        
        // Count documents and copies
        const totalDocs = Object.keys(selectedDocuments).length + Object.keys(selectedCertificates).length;
        let totalCopies = 0;
        
        Object.values(selectedDocuments).forEach(doc => totalCopies += parseInt(doc.copies || 1));
        Object.values(selectedCertificates).forEach(cert => totalCopies += parseInt(cert.copies || 1));
        
        $('#summary-count').text(totalDocs);
        $('#summary-copies').text(totalCopies);
        
        // Build documents table
        const $table = $('#summary-documents');
        $table.empty();
        
        // Add regular documents
        Object.values(selectedDocuments).forEach(function(doc) {
            const requirementsHTML = doc.requirementNames && doc.requirementNames.length > 0 
                ? `<ul class="mb-0"><li>${doc.requirementNames.join('</li><li>')}</li></ul>` 
                : 'No special requirements';
            
            $table.append(`
                <tr>
                    <td>${doc.name}</td>
                    <td>${doc.copies}</td>
                    <td>${doc.reason || 'N/A'}</td>
                    <td>${requirementsHTML}</td>
                </tr>
            `);
        });
        
        // Add certificates
        Object.values(selectedCertificates).forEach(function(cert) {
            const requirementsHTML = cert.requirementNames && cert.requirementNames.length > 0 
                ? `<ul class="mb-0"><li>${cert.requirementNames.join('</li><li>')}</li></ul>` 
                : 'No special requirements';
            
            $table.append(`
                <tr>
                    <td>${cert.name}</td>
                    <td>${cert.copies}</td>
                    <td>${cert.reason || 'N/A'}</td>
                    <td>${requirementsHTML}</td>
                </tr>
            `);
        });
    }
    
    // Handle adding regular documents
    $('.add-document').on('click', function() {
        const fileId = $(this).data('file-id');
        const fileName = $(this).data('file-name');
        
        $('#modal-file-id').val(fileId);
        $('#documentModalLabel').text(`Add ${fileName}`);
        
        // Reset modal fields
        $('#modal-copies').val(1);
        $('#modal-reason').val('');
        
        // Load requirements for this file
        loadRequirements(fileId, 'regular');
        
        // Show the modal
        $('#documentModal').modal('show');
    });
    
    // Handle certificate dropdown change
    $('#certificateSelect').on('change', function() {
        $('.add-certificate').prop('disabled', !$(this).val());
    });
    
    // Handle adding certificates
    $('.add-certificate').on('click', function() {
        const fileId = $('#certificateSelect').val();
        const fileName = $('#certificateSelect option:selected').data('file-name');
        
        if (!fileId) return;
        
        $('#modal-certificate-id').val(fileId);
        $('#certificateModalLabel').text(`Add ${fileName} Certificate`);
        
        // Reset modal fields
        $('#certificate-copies').val(1);
        $('#certificate-reason').val('');
        $('#certificate-school-year').val('');
        $('#certificate-semester').val('');
        
        // Load requirements for this certificate
        loadRequirements(fileId, 'certificate');
        
        // Show the modal
        $('#certificateModal').modal('show');
    });
    
    // Load requirements for a file - FIXED VERSION
    function loadRequirements(fileId, type) {
        const containerId = type === 'regular' ? '#requirements-container' : '#certificate-requirements-container';
        const $container = $(containerId);
        $container.empty();
        
        // Show loading indicator
        $container.html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading requirements...</p></div>');
        
        $.ajax({
            url: `/student/file-requirements/${fileId}`,
            method: 'GET',
            success: function(response) {
                $container.empty();
                
                // Handle successful response
                if (response.success && response.requirements && response.requirements.length > 0) {
                    $container.append('<div class="mt-4"><h6>Requirements:</h6></div>');
                    
                    // Create a requirements list
                    const $list = $('<div class="form-requirements"></div>');
                    
                    response.requirements.forEach(function(req) {
                        $list.append(`
                            <div class="form-check mb-2">
                                <input class="form-check-input req-checkbox" type="checkbox" 
                                       id="req-${type}-${req.id}" value="${req.id}" 
                                       data-req-name="${req.name}">
                                <label class="form-check-label" for="req-${type}-${req.id}">
                                    ${req.name}
                                </label>
                            </div>
                        `);
                    });
                    
                    $container.append($list);
                    
                    // Add validation message
                    $container.append(`
                        <div class="alert alert-warning mt-3" id="${type}-requirement-warning">
                            <i class="fas fa-exclamation-triangle"></i> Please check all requirements to continue.
                        </div>
                    `);
                    
                    // Setup validation for confirm button
                    setupRequirementValidation(type);
                } else {
                    $container.append('<div class="alert alert-info">No special requirements for this document.</div>');
                }
            },
            error: function(xhr, status, error) {
                $container.empty();
                
                // Display friendly error message
                $container.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Unable to load requirements. Please try again or contact support.
                    </div>
                    <div class="mt-3">
                        <p>This document might still be available without requirements.</p>
                    </div>
                `);
                
                console.error("Error loading requirements:", error);
            }
        });
    }
    
    // Setup validation for requirements
    function setupRequirementValidation(type) {
        const containerId = type === 'regular' ? '#requirements-container' : '#certificate-requirements-container';
        const confirmBtnId = type === 'regular' ? '#confirm-add-document' : '#confirm-add-certificate';
        const warningId = `#${type}-requirement-warning`;
        
        // Initial check
        validateRequirements();
        
        // Check when requirements are clicked
        $(`${containerId} .req-checkbox`).on('change', validateRequirements);
        
        function validateRequirements() {
            const $checkboxes = $(`${containerId} .req-checkbox`);
            const totalReqs = $checkboxes.length;
            const checkedReqs = $checkboxes.filter(':checked').length;
            
            // If there are requirements, ensure all are checked
            if (totalReqs > 0) {
                const allChecked = totalReqs === checkedReqs;
                $(confirmBtnId).prop('disabled', !allChecked);
                $(warningId).toggle(!allChecked);
            } else {
                // No requirements to check
                $(confirmBtnId).prop('disabled', false);
                $(warningId).hide();
            }
        }
    }
    
    // Confirm adding a document
    $('#confirm-add-document').on('click', function() {
        const fileId = $('#modal-file-id').val();
        const fileName = $('#documentModalLabel').text().replace('Add ', '');
        const copies = $('#modal-copies').val();
        const reason = $('#modal-reason').val();
        
        // Get selected requirements
        const requirements = [];
        const requirementNames = [];
        
        $('#requirements-container .req-checkbox:checked').each(function() {
            requirements.push($(this).val());
            requirementNames.push($(this).data('req-name'));
        });
        
        // Store the document info
        selectedDocuments[fileId] = {
            id: fileId,
            name: fileName,
            copies: copies,
            reason: reason,
            requirements: requirements,
            requirementNames: requirementNames
        };
        
        // Update UI
        updateNextButton();
        
        // Close the modal
        $('#documentModal').modal('hide');
    });
    
    // Confirm adding a certificate
    $('#confirm-add-certificate').on('click', function() {
        const fileId = $('#modal-certificate-id').val();
        const fileName = $('#certificateModalLabel').text().replace('Add ', '').replace(' Certificate', '');
        const copies = $('#certificate-copies').val();
        const reason = $('#certificate-reason').val();
        const schoolYear = $('#certificate-school-year').val();
        const semester = $('#certificate-semester').val();
        
        // Get selected requirements
        const requirements = [];
        const requirementNames = [];
        
        $('#certificate-requirements-container .req-checkbox:checked').each(function() {
            requirements.push($(this).val());
            requirementNames.push($(this).data('req-name'));
        });
        
        // Store the certificate info
        selectedCertificates[fileId] = {
            id: fileId,
            name: fileName,
            copies: copies,
            reason: reason,
            schoolYear: schoolYear,
            semester: semester,
            requirements: requirements,
            requirementNames: requirementNames
        };
        
        // Update UI
        updateNextButton();
        
        // Reset certificate dropdown
        $('#certificateSelect').val('');
        $('.add-certificate').prop('disabled', true);
        
        // Close the modal
        $('#certificateModal').modal('hide');
    });
    
    // Remove document
    $(document).on('click', '.remove-document', function() {
        const id = $(this).data('id');
        delete selectedDocuments[id];
        updateNextButton();
    });
    
    // Remove certificate
    $(document).on('click', '.remove-certificate', function() {
        const id = $(this).data('id');
        delete selectedCertificates[id];
        updateNextButton();
    });
    
    // Date picker validation for weekends
    $('#preferred_date').on('change', function() {
        const selectedDate = new Date($(this).val());
        const day = selectedDate.getDay();
        
        // 0 is Sunday, 6 is Saturday
        if (day === 0 || day === 6) {
            alert('Please select a weekday. Weekends are not available for document pickup.');
            $(this).val('');
        }
    });
    
    // Enable/disable submit button based on terms agreement
    $('#agreeTerms').on('change', function() {
        $('#submitButton').prop('disabled', !$(this).is(':checked'));
    });
    
    // Initialize the form
    updateStepUI(1);
});
</script>
@endpush

