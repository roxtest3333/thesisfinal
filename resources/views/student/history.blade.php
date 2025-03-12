@extends('layouts.default')

@section('content')
<div class="dashboard-container">
    <div class="page-overlay">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Completed Requests</h2>
            <p class="dashboard-subtitle">Here is a list of your past requests.</p>
        </div>

        <div class="dashboard-grid">
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">Past Requests</h5>

                    @if($completedSchedules->isEmpty())
                        <p class="text-white">No completed requests.</p>
                    @else
                        @foreach ($completedSchedules as $schedule)
                            <div class="schedule-box completed-box"> 
                                <div class="file-name">
                                    <strong>{{ $schedule->file->file_name }}</strong>
                                </div>

                                <div class="schedule-info">
                                    <div class="date-time">
                                        <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span> 
                                        <span><strong>Time:</strong> {{ ucfirst($schedule->preferred_time) }}</span>
                                    </div>
                                </div>

                                <div class="status-actions">
                                    <div class="status-badge 
                                        {{ $schedule->status == 'approved' ? 'approved-badge' : 'rejected-badge' }}">
                                        {{ ucfirst($schedule->status) }}
                                    </div>
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
