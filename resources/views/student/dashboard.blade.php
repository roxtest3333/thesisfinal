@extends('layouts.default')

@section('content')
<div class="dashboard-container min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::guard('student')->user()->first_name }}!</h2>
            <p class="text-gray-600">Manage your document requests and track their status.</p>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            
            <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                <div class="text-amber-500 text-xl font-bold">{{ $pendingCount }}</div>
                <div class="text-sm text-gray-500">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                <div class="text-green-500 text-xl font-bold">{{ $approvedCount }}</div>
                <div class="text-sm text-gray-500">Approved</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                <div class="text-red-500 text-xl font-bold">{{ $pendingComplianceCount }}</div>
                <div class="text-sm text-gray-500">Pending Compliance</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                <div class="text-blue-500 text-xl font-bold">{{ $totalRequests }}</div>
                <div class="text-sm text-gray-500">Total Requests</div>
            </div>
        </div>

        <!-- Dashboard Tab Navigation -->
        <div x-data="{ activeTab: 'pending' }" class="mb-6 border-b border-gray-200">
            <div class="flex overflow-x-auto">
                <button @click="activeTab = 'pending'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'pending', 'border-transparent': activeTab !== 'pending' }"
                        class="px-4 py-2 font-medium text-sm border-b-2 hover:text-blue-700 hover:border-blue-300 whitespace-nowrap">
                    Pending Requests
                </button>
                <button @click="activeTab = 'compliance'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'compliance', 'border-transparent': activeTab !== 'compliance' }"
                        class="px-4 py-2 font-medium text-sm border-b-2 hover:text-blue-700 hover:border-blue-300 whitespace-nowrap">
                    Pending Compliance
                </button>
            </div>
            
            <!-- Tab Content -->
            <div>
                <!-- Pending Requests Tab -->
                <div x-show="activeTab === 'pending'" class="bg-white rounded-lg shadow overflow-hidden mt-4">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Pending Requests</h3>
                        <p class="text-sm text-gray-500">Document requests awaiting approval</p>
                    </div>

                    @if($pendingSchedules->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            No pending requests at this time.
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($pendingByType as $typeName => $requests)
                                <div class="p-4">
                                    <div x-data="{ open: true }" class="mb-2">
                                        <button @click="open = !open" class="flex justify-between items-center w-full text-left font-medium text-gray-700">
                                            <span>{{ $typeName }} ({{ $requests->count() }})</span>
                                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" class="mt-2 space-y-3">
                                            @foreach($requests as $schedule)
                                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                                        <div>
                                                            <h4 class="font-medium text-gray-800">{{ $schedule->file->file_name }}</h4>
                                                            <div class="mt-1 text-sm text-gray-600">
                                                                <span class="inline-block mr-3"><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                                                <span class="inline-block"><span class="font-medium">Time:</span> {{ ucfirst($schedule->preferred_time) }}</span>
                                                            </div>
                                                            
                                                            <!-- Estimated Completion -->
                                                            <div class="mt-1 text-sm text-gray-600">
                                                                <span class="font-medium">Est. Completion:</span> 
                                                                {{ \Carbon\Carbon::parse($schedule->preferred_date)->addDays(5)->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-3 sm:mt-0 flex items-center">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mr-2">
                                                                Pending
                                                            </span>
                                                            <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST" 
                                                                class="inline"
                                                                onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Priority Indicator -->
                                                    @if(\Carbon\Carbon::parse($schedule->preferred_date)->subDays(1)->isPast())
                                                        <div class="mt-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <svg class="h-2 w-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                                    <circle cx="4" cy="4" r="3" />
                                                                </svg>
                                                                High Priority
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Pending Compliance Tab -->
                <div x-show="activeTab === 'compliance'" class="bg-white rounded-lg shadow overflow-hidden mt-4">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">Pending Compliance</h3>
                        <p class="text-sm text-gray-500">Requests needing additional information or corrections</p>
                    </div>

                    @if($pendingComplianceSchedules->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            No pending compliance requests at this time.
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($pendingComplianceSchedules as $schedule)
                                <div class="p-4 bg-red-50">
                                    <div class="flex flex-col md:flex-row md:justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800">{{ $schedule->file->file_name }}</h4>
                                            <div class="mt-1 text-sm text-gray-600">
                                                <span class="inline-block mr-3"><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                                <span class="inline-block"><span class="font-medium">Time:</span> {{ ucfirst($schedule->preferred_time) }}</span>
                                            </div>
                                            
                                            <!-- Required Actions -->
                                            <div class="mt-4">
                                                <h5 class="text-sm font-bold text-red-700">Required Actions:</h5>
                                                <div class="mt-1 p-3 bg-white rounded border border-red-300 text-sm">
                                                    {{ $schedule->remarks }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0 md:ml-4 flex flex-col justify-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                                                Pending Compliance
                                            </span>
                                            <a href="{{ route('student.file_requests.create', ['reference_id' => $schedule->id]) }}" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                 Submit New Request
                                             </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js for tabs -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection