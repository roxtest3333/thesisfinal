@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Pending Requests</h2>
        <div>
             
            <a href="{{ route('admin.reports.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-archive"></i> View Report Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger fade show">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Filter Form -->
    <div class="filter-box bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4 mb-4">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Search Student Field -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-search mr-1"></i> Search Student (Name or ID)
                </label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Enter student name or ID" 
                           class="form-control pl-10 w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        
                    </div>
                </div>
            </div>
    
            
    
            <!-- Status Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-tag mr-1"></i> Status
                </label>
                <select name="status" class="form-control">
                    <option value="">All Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
    
            <!-- Date Range Filters -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar-day mr-1"></i> Start Preferred Date
                </label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
    
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar-week mr-1"></i> End Preferred Date
                </label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>

            <!-- File Type Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-file-alt mr-1"></i> File Type
                </label>
                <select name="file_id" class="form-control">
                    <option value="">All Files</option>
                    @foreach($files as $file)
                        <option value="{{ $file->id }}" {{ request('file_id') == $file->id ? 'selected' : '' }}>
                            {{ $file->file_name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- School Year Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar-alt mr-1"></i> School Year
                </label>
                <select name="school_year_id" class="form-control">
                    <option value="">All Years</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('school_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->year }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Semester Filter -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar mr-1"></i> Semester
                </label>
                <select name="semester_id" class="form-control">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ request('semester_id') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Action Buttons -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="btn btn-primary">
                     Apply Filters
                </button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), [
                                'sort' => 'student_id', 
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ])) }}" 
                            class="sortable {{ request('sort') == 'student_id' ? 'active' : '' }}"
                            data-direction="{{ request('sort') == 'student_id' ? request('direction', 'asc') : 'asc' }}">
                                Student ID
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), [
                                'sort' => 'student_id', 
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ])) }}" 
                            class="sortable {{ request('sort') == 'student_id' ? 'active' : '' }}"
                            data-direction="{{ request('sort') == 'student_id' ? request('direction', 'asc') : 'asc' }}">
                                Student Name
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), [
                                'sort' => 'file_id', 
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ])) }}" 
                            class="sortable {{ request('sort') == 'file_id' ? 'active' : '' }}"
                            data-direction="{{ request('sort') == 'file_id' ? request('direction', 'asc') : 'asc' }}">
                                File
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), [
                                'sort' => 'preferred_date', 
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ])) }}" 
                            class="sortable {{ request('sort') == 'preferred_date' ? 'active' : '' }}"
                            data-direction="{{ request('sort') == 'preferred_date' ? request('direction', 'asc') : 'asc' }}">
                                Preferred Date
                            </a>
                        </th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Copies</th>
                        <th>
                            <a href="{{ route('admin.schedules.index', array_merge(request()->query(), [
                                'sort' => 'status', 
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ])) }}" 
                            class="sortable {{ request('sort') == 'status' ? 'active' : '' }}"
                            data-direction="{{ request('sort') == 'status' ? request('direction', 'asc') : 'asc' }}">
                                Status
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.schedules.index', [
                                'sort' => 'created_at',
                                'direction' => request('direction', 'asc') == 'asc' ? 'desc' : 'asc'
                            ]) }}" 
                            class="sortable {{ request('sort') == 'created_at' ? 'active' : '' }}">
                                Date Requested
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="fade-in">
                            <td>{{ $schedule->student->student_id ?? 'N/A' }}</td>
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="student-link">
                                        {{ $schedule->student->last_name ?? 'N/A' }}, {{ $schedule->student->first_name ?? '' }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @if($schedule->file && in_array($schedule->file->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
                                    <br>
                                    <small class="text-gray-500 text-sm">
                                        {{ $schedule->manual_school_year }} - {{ $schedule->manual_semester }}
                                    </small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>                        
                            <td>{{ $schedule->copies }}</td>
                            <td class="status-cell">
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : ($schedule->status == 'completed' ? 'primary' : 'warning')) }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $schedule->created_at->format('M d, Y') }} 
                            </td>
                            <td>
                                <div class="button-container">
                                    @if($schedule->status == 'pending')
                                        <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm reject-btn"
                                            data-schedule-id="{{ $schedule->id }}" 
                                            data-student-name="{{ optional($schedule->student)->last_name ?? 'Unknown' }}, {{ optional($schedule->student)->first_name ?? '' }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @elseif($schedule->status == 'approved')
                                        <button type="button" class="btn btn-primary btn-sm complete-btn"
                                            data-schedule-id="{{ $schedule->id }}" 
                                            data-student-name="{{ optional($schedule->student)->last_name ?? 'Unknown' }}, {{ optional($schedule->student)->first_name ?? '' }}">
                                            <i class="fas fa-check-double"></i> Complete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($schedules->hasPages())
            <div class="pagination-container">
                {{ $schedules->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="modal-backdrop" style="display: none;">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4">Reject Schedule Request</h3>
        <p id="rejectStudentName" class="mb-2 text-gray-600"></p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" id="rejectScheduleId" name="schedule_id">
            
            <label class="block text-gray-700 font-bold mb-1">Rejection Reason</label>
            <textarea name="rejection_reason" id="rejectionReason" class="form-control w-full" required></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn-secondary" id="closeRejectModal">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>
<style>
    /* Modal Backdrop */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-backdrop[style*="display: flex"] {
        opacity: 1;
    }

    /* Modal Content */
    .modal-content {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
        padding: 1.5rem;
    }
    
    .modal-backdrop[style*="display: flex"] .modal-content {
        transform: translateY(0);
    }

    /* Responsive adjustments */
    @media (min-width: 640px) {
        .modal-content {
            width: 80%;
        }
    }
    
    @media (min-width: 768px) {
        .modal-content {
            width: 60%;
        }
    }
    
    @media (min-width: 1024px) {
        .modal-content {
            width: 40%;
        }
    }
</style>
<!-- Completion Modal -->
<div id="completeModal" class="modal-backdrop" style="display: none;">
    <div class="modal-content">
        <h3 class="text-xl font-bold mb-4">Complete Schedule Request</h3>
        <p id="completeStudentName" class="mb-2 text-gray-600"></p>

        <form id="completeForm" method="POST">
            @csrf
           
            <input type="hidden" id="completeScheduleId" name="schedule_id">
            
            <label class="block text-gray-700 font-bold mb-1">Completion Notes (Optional)</label>
            <textarea name="completion_notes" id="completionNotes" class="form-control w-full"></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn-secondary" id="closeCompleteModal">Cancel</button>
                <button type="submit" class="btn btn-primary">Mark as Completed</button>
            </div>
        </form>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', function() {
    // School Year and Semester Dynamic Loading
    const schoolYearSelect = document.querySelector('select[name="school_year_id"]');
    const semesterSelect = document.querySelector('select[name="semester_id"]');
    
    if (schoolYearSelect && semesterSelect) {
        schoolYearSelect.addEventListener('change', function() {
            const schoolYearId = this.value;
            
            // Clear current semester options except the first "All Semesters" option
            while (semesterSelect.options.length > 1) {
                semesterSelect.remove(1);
            }
            
            if (!schoolYearId) return;
            
            fetch(`/admin/schedules/semesters?school_year_id=${schoolYearId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                const semesters = Array.isArray(data) ? data : 
                                (data.data || data.semesters || Object.values(data));
                
                semesters.forEach(semester => {
                    const option = new Option(semester.name, semester.id);
                    semesterSelect.add(option);
                });
            })
            .catch(error => console.error('Error loading semesters:', error));
        });
    }
    
    // Modal Management System
    const modalSystem = {
        modals: {
            reject: {
                id: 'rejectModal',
                openButtons: '.reject-btn',
                closeButton: '#closeRejectModal',
                form: '#rejectForm',
                idField: '#rejectScheduleId',
                nameField: '#rejectStudentName',
                inputField: '#rejectionReason'
            },
            complete: {
                id: 'completeModal',
                openButtons: '.complete-btn',
                closeButton: '#closeCompleteModal',
                form: '#completeForm',
                idField: '#completeScheduleId',
                nameField: '#completeStudentName',
                inputField: '#completionNotes'
            }
        },
        
        init: function() {
            // Initialize all modals
            Object.values(this.modals).forEach(modalConfig => {
                const modal = document.getElementById(modalConfig.id);
                
                // Open modal handlers
                document.querySelectorAll(modalConfig.openButtons).forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelector(modalConfig.idField).value = btn.dataset.scheduleId;
                        document.querySelector(modalConfig.nameField).textContent = 
                            `${modalConfig.id === 'rejectModal' ? 'Reject' : 'Complete'} request for ${btn.dataset.studentName}?`;
                        document.querySelector(modalConfig.form).action = 
                            `/admin/schedules/${btn.dataset.scheduleId}/${modalConfig.id === 'rejectModal' ? 'reject' : 'complete'}`;
                        this.showModal(modalConfig.id);
                    });
                });
                
                // Close modal handler
                document.querySelector(modalConfig.closeButton).addEventListener('click', () => {
                    this.hideModal(modalConfig.id);
                });
                
                // Click outside to close
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.hideModal(modalConfig.id);
                    }
                });
            });
            
            // Reject form validation
            document.querySelector('#rejectForm').addEventListener('submit', function(e) {
                const reason = document.querySelector('#rejectionReason').value.trim();
                if (!reason) {
                    e.preventDefault();
                    alert("Please provide a reason for rejection.");
                    document.querySelector('#rejectionReason').focus();
                }
            });
            
            // Setup student highlight effects
            this.setupStudentHighlight();
        },
        
        showModal: function(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = "flex";
            // Trigger reflow for animation
            void modal.offsetWidth;
            modal.classList.add('active');
        },
        
        hideModal: function(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = "none";
                // Clear inputs when hiding
                const config = this.modals[modalId.split('Modal')[0].toLowerCase()];
                if (config) {
                    document.querySelector(config.inputField).value = '';
                }
            }, 300);
        },
        
        setupStudentHighlight: function() {
            document.querySelectorAll('.btn-success, .btn-danger, .btn-primary').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    const row = this.closest('tr');
                    if (row) {
                        const studentLink = row.querySelector('.student-link');
                        if (studentLink) {
                            studentLink.classList.add('student-link-highlight');
                        }
                    }
                });
                
                button.addEventListener('mouseleave', function() {
                    const row = this.closest('tr');
                    if (row) {
                        const studentLink = row.querySelector('.student-link');
                        if (studentLink) {
                            studentLink.classList.remove('student-link-highlight');
                        }
                    }
                });
            });
        }
    };
    
    // Initialize the modal system
    modalSystem.init();
});
</script>
@endsection