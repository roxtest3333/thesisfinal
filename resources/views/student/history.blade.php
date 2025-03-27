@extends('layouts.default')

@section('content')
<div class="dashboard-container">
    <div class="page-overlay">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Request History</h2>
            <p class="dashboard-subtitle">View all your past and current requests.</p>
        </div>

        <div class="dashboard-grid">
            <div class="schedule-card">
                <div class="p-6">
                    <h5 class="stat-card-title">All Requests</h5>

                    @if($allSchedules->isEmpty())
                        <p class="text-white">No requests found.</p>
                    @else
                        @foreach ($allSchedules as $schedule)
                            <div class="schedule-box 
                                {{ $schedule->status == 'approved' ? 'approved-box' : ($schedule->status == 'pending' ? 'pending-box' : 'rejected-box') }}"> 

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
                                        {{ $schedule->status == 'approved' ? 'approved-badge' : ($schedule->status == 'pending' ? 'pending-badge' : 'rejected-badge') }}">
                                        {{ ucfirst($schedule->status) }}
                                    </div>

                                    @if($schedule->status == 'rejected' && $schedule->remarks)
                                        <div class="rejection-reason">
                                            <strong>Reason:</strong> {{ $schedule->remarks }}
                                        </div>
                                    @endif
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
