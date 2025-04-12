@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 w-full">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-semibold">Requests Report</h2>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-calendar mr-1"></i> View Requests Management
        </a>
    </div>

    <!-- Filter Form -->
    <div class="filter-box bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4 mb-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
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
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
            </div>

              <!-- Date Range Filters -->
              <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar-day mr-1"></i>
                    @if(in_array(request('status'), ['pending', 'approved', 'rejected', 'canceled']))
                        Start Preferred Date
                    @else
                        Start Completion Date
                    @endif
                </label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-1">
                    <i class="fas fa-calendar-week mr-1"></i>
                    @if(in_array(request('status'), ['pending', 'approved', 'rejected', 'canceled']))
                        End Preferred Date
                    @else
                        End Completion Date
                    @endif
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
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Print and Export Buttons -->
    <div class="flex gap-2 mb-4">
        <button onclick="printReport()" class="btn btn-success">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

    <!-- Reports Table -->
    <div class="card bg-white bg-opacity-30 backdrop-blur-sm shadow-lg rounded-lg p-4">
        <div class="table-responsive">
            <table class="table w-full" id="reportTable">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>File</th>
                        <th>Preffered Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Term Requested</th>
                        
                        <th>Copies</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                        <th>Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->student->student_id ?? 'N/A' }}</td>
                            <td>{{ $schedule->student->last_name ?? 'N/A' }}, {{ $schedule->student->first_name ?? '' }}</td>
                            <td>
                                {{ optional($schedule->file)->file_name ?? 'N/A' }}
                                
                            </td>
                            <td>{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</td>
                            <td>{{ $schedule->preferred_time }}</td>
                            <td>{{ $schedule->reason }}</td>
                            <td>{{ $schedule->school_year_name ?? 'N/A' }} - {{ $schedule->semester_name ?? 'N/A' }}</td>
                            
                            
                            <td>{{ $schedule->copies }}</td>
                            <td>
                                <span class="badge bg-{{ $schedule->status == 'approved' ? 'success' : ($schedule->status == 'rejected' ? 'danger' : ($schedule->status == 'completed' ? 'primary' : ($schedule->status == 'canceled' ? 'secondary' : 'warning'))) }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $schedule->created_at->format('M d, Y') }} 
                            </td>
                            
                            <td>{{ $schedule->completed_at?->format('M d, Y') ?? 'not completed' }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($schedules->hasPages())
            <div class="pagination-container mt-4">
                {{ $schedules->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Print Function -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            
            // If no school year is selected, just leave the default option
            if (!schoolYearId) {
                return;
            }
            
            // Fetch semesters for the selected school year
            fetch(`/admin/schedules/semesters?school_year_id=${schoolYearId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Check if data is array before using forEach
                if (Array.isArray(data)) {
                    // Add new options based on the response
                    data.forEach(semester => {
                        const option = document.createElement('option');
                        option.value = semester.id;
                        option.textContent = semester.name;
                        semesterSelect.appendChild(option);
                    });
                } else if (data && typeof data === 'object') {
                    // Handle case where response is an object with array property
                    const semestersArray = data.data || data.semesters || Object.values(data);
                    if (Array.isArray(semestersArray)) {
                        semestersArray.forEach(semester => {
                            const option = document.createElement('option');
                            option.value = semester.id;
                            option.textContent = semester.name;
                            semesterSelect.appendChild(option);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error loading semesters:', error);
            });
        });
    }
    
    // Print Function
    window.printReport = function() {
        var printContents = document.getElementById('reportTable').outerHTML;
        var originalContents = document.body.innerHTML;
        
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Schedule Report</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write('.badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }');
        printWindow.document.write('.bg-success { background-color: #d1e7dd; color: #0f5132; }');
        printWindow.document.write('.bg-danger { background-color: #f8d7da; color: #842029; }');
        printWindow.document.write('.bg-primary { background-color: #cfe2ff; color: #084298; }');
        printWindow.document.write('.bg-warning { background-color: #fff3cd; color: #664d03; }');
        printWindow.document.write('.bg-secondary { background-color: #e2e3e5; color: #41464b; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2 style="text-align: center; margin-bottom: 20px;">Schedule Report</h2>');
        printWindow.document.write('<div style="margin-bottom: 20px;">Generated on: ' + new Date().toLocaleString() + '</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
});   
</script>
@endsection