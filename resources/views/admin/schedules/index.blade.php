@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <div class="d-flex justify-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Schedule Management</h2>
        <a href="{{ route('admin.reports.index') }}" class="archived-link">
            <i class="fas fa-archive"></i> View Report Page
        </a>
    </div>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> All schedule requests are kept on this page for 7 days before moving to the Report Page.
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

    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Copies</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="fade-in">
                            <td>
                                @if($schedule->student)
                                    <a href="{{ route('admin.reports.student', $schedule->student->id) }}" class="student-link">
                                        {{ $schedule->student->first_name }} {{ $schedule->student->last_name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                @if(in_array(optional($schedule->file)->file_name, ['COR', 'COG']) && $schedule->manual_school_year && $schedule->manual_semester)
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
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="button-container">
                                    <form action="{{ route('schedules.approve', $schedule->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm reject-btn"
                                        data-schedule-id="{{ $schedule->id }}" 
                                        data-student-name="{{ $schedule->student->first_name }} {{ $schedule->student->last_name }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
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
<div id="rejectModal" class="fixed inset-0 items-center justify-center bg-gray-800 bg-opacity-50 transition-opacity duration-300 z-50" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h3 class="text-xl font-bold mb-4">Reject Schedule Request</h3>
        <p id="rejectStudentName" class="mb-2 text-gray-600"></p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" id="rejectScheduleId" name="schedule_id">
            
            <label class="block text-gray-700 font-bold mb-1">Rejection Reason</label>
            <textarea name="rejection_reason" id="rejectionReason" class="form-control w-full" required></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn-secondary" id="closeModal">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>
<style>
   #rejectModal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    inset: 0;
    z-index: 50;
}

/* Ensure the modal content is centered */
#rejectModal > div {
    max-width: 500px;
    width: 90%;
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.3s ease-in-out;
}
    .sortable {
        color: #2563eb;
        text-decoration: none;
        font-weight: bold;
        position: relative;
        padding-right: 20px;
    }
    
    .sortable::after {
        content: "▼";
        font-size: 12px;
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.5;
    }
    
    .sortable:hover {
        text-decoration: underline;
    }
    
    .sortable[data-direction="asc"]::after {
        content: "▲";
    }
    
    .table tbody tr {
        transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        cursor: pointer;
    }
    .table tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.1); 
        transform: scale(1.01);
    }
    .button-container {
        display: flex;
        gap: 0.25rem;
        align-items: center;
    }
    
    .button-container form {
        margin: 0; 
    }
    
    .button-container .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.5rem; 
        font-size: 0.75rem;
        line-height: 1.2;
        gap: 0.25rem; 
        height: auto; 
        white-space: nowrap; 
        transition: all 0.3s ease-in-out;
    }
    
    .button-container .btn i {
        font-size: 0.75rem; 
        margin-right: 0.25rem; 
    }
    
    /* Ensure buttons don't stretch table cells */
    .table td .button-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    .btn-success:hover {
        background-color: #059669; 
        box-shadow: 0 4px 8px rgba(5, 150, 105, 0.3);
        transform: scale(1.05);
    }
    
    .btn-danger:hover {
        background-color: #dc2626; 
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
        transform: scale(1.05);
    }
    
    /* Add a smooth press effect */
    .btn-success:active,
    .btn-danger:active {
        transform: scale(0.98);
    }
    
    /* filter buttons */
        .filter-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: flex-end; 
    }
    
    .filter-buttons .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 42px; 
    }
    /* Improved Filter Box */
    .filter-box {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
    }
    .filter-box:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .filter-buttons .btn:hover {
        transform: scale(1.05);
    }
    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        transition: border 0.2s;
        height: 42px;
    }
    
    .form-control:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 6px rgba(99, 102, 241, 0.3);
    }
    
    /* Status Badges */
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.25rem;
        text-transform: uppercase;
    }
    .bg-success { background-color: #10b981; color: white; }
    .bg-danger { background-color: #ef4444; color: white; }
    .bg-warning { background-color: #f59e0b; color: white; }
    /* Button Styling */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
    }
    .table td,
    .table th {
        position: relative;
    }
    
    .table td:not(:last-child)::after,
    .table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background-color: var(--border-color);
    }
    
    /* Ensure responsive behavior is maintained */
    @media (max-width: 768px) {
        .table td:not(:last-child)::after,
        .table th:not(:last-child)::after {
            display: none;
        }
    }.student-link-highlight {
    background-color: rgba(59, 130, 246, 0.15) !important;
    color: #1e3a8a !important;
    text-shadow: 0 1px 3px rgba(59, 130, 246, 0.4) !important;
    transition: all 0.3s ease-in-out;
    @keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
    }
    
    .fade-in {
        opacity: 0;
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    /* Apply staggered fade-in effect for schedule rows */
    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rejectModal = document.getElementById('rejectModal');
        const rejectForm = document.getElementById('rejectForm');
        const rejectScheduleId = document.getElementById('rejectScheduleId');
        const rejectStudentName = document.getElementById('rejectStudentName');
        const rejectionReason = document.getElementById('rejectionReason');
    
        // Handle reject button click - open modal
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function () {
                rejectScheduleId.value = this.dataset.scheduleId;
                rejectStudentName.textContent = `Reject request for ${this.dataset.studentName}?`;
                rejectForm.setAttribute('action', `/admin/schedules/${this.dataset.scheduleId}/reject`);
    
                // Show modal using `style.display`
                rejectModal.style.display = "flex";
            });
        });
    
        // Close modal
        document.getElementById('closeModal').addEventListener('click', function () {
            rejectModal.style.display = "none";
            rejectionReason.value = ""; // Reset input field on close
        });
    
        // Prevent form submission if no rejection reason is entered
        rejectForm.addEventListener('submit', function (event) {
            if (rejectionReason.value.trim() === "") {
                event.preventDefault();
                alert("Please provide a reason for rejection.");
            }
        });
    });
</script>
@endsection
