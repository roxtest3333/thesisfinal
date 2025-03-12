@extends('layouts.default')

@section('content')
<div class="dashboard-container">
    <div class="page-overlay">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Welcome, {{ Auth::guard('student')->user()->first_name }}!</h2>
            <p class="dashboard-subtitle">Manage your pending and rejected schedule requests.</p>
        </div>

        <div class="dashboard-grid">
            <!-- Pending Requests Section -->
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">Pending Requests</h5>

                    @if($pendingSchedules->isEmpty())
                        <p class="text-gray-500">No pending requests.</p>
                    @else
                        @foreach ($pendingSchedules as $schedule)
                            <div class="schedule-box">
                                <div class="file-name">
                                    <strong>{{ $schedule->file->file_name }}</strong>
                                </div>

                                <div class="schedule-info">
                                    <div class="date-time-container">
                                        <span class="text-sm text-gray-500">Date:</span> 
                                        <span class="text-gray-700">{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span> 
                                    </div>
                                    <div class="date-time-container">
                                        <span class="text-sm text-gray-500">Time:</span>
                                        <span class="text-gray-700">{{ ucfirst($schedule->preferred_time) }}</span>
                                    </div>
                                </div>

                                <div class="status-actions">
                                    <span class="status-badge pending-badge">Pending</span>
                                    <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                        @csrf
                                        <button type="submit" class="cancel-btn">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Rejected Requests Section -->
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">Rejected Requests</h5>

                    @if($rejectedSchedules->isEmpty())
                        <p class="text-gray-500">No rejected requests.</p>
                    @else
                        @foreach ($rejectedSchedules as $schedule)
                            <div class="schedule-box rejected-box">
                                <div class="file-name">
                                    <strong>{{ $schedule->file->file_name }}</strong>
                                </div>

                                <div class="schedule-info">
                                    <div class="date-time">
                                        <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                        <span><strong>Time:</strong> {{ ucfirst($schedule->preferred_time) }}</span>
                                    </div>
                                </div>

                                <div class="status-row">
                                    <span class="status-badge rejected-badge">Rejected</span>
                                </div>

                                <div class="rejection-reason">
                                    <strong>Reason:</strong> {{ $schedule->remarks }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<link href="{{ asset('css/student.css') }}" rel="stylesheet">
