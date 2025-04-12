@extends('layouts.default')

@section('content')
<div class="dashboard-container min-h-screen bg-gray-100 min-w-[75vw]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Dashboard Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::guard('student')->user()->first_name }}!</h2>
            <p class="text-gray-600 mt-1">Manage your document requests and track their status.</p>
        </div>

        <!-- Dashboard Tab Navigation -->
        <div x-data="{ activeTab: 'pending' }" class="mb-6">
            <div class="flex border-b border-gray-200 space-x-6">
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'text-blue-600 border-blue-600' : 'text-gray-500 border-transparent'"
                        class="pb-2 text-sm font-medium border-b-2 focus:outline-none transition">
                    Pending Requests
                    @if($pendingCount > 0)
                        <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-300 text-blue-800">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </button>
                <button @click="activeTab = 'accepted'" 
                        :class="activeTab === 'accepted' ? 'text-green-600 border-green-600' : 'text-gray-500 border-transparent'"
                        class="pb-2 text-sm font-medium border-b-2 focus:outline-none transition">
                    Approved
                    @if($acceptedCount > 0)
                        <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-300 text-green-800">
                            {{ $acceptedCount }}
                        </span>
                    @endif
                </button>
                <button @click="activeTab = 'compliance'" 
                        :class="activeTab === 'compliance' ? 'text-red-600 border-red-600' : 'text-gray-500 border-transparent'"
                        class="pb-2 text-sm font-medium border-b-2 focus:outline-none transition">
                    Pending Compliance
                    @if($pendingComplianceCount > 0)
                        <span class="ml-1 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-300 text-red-800">
                            {{ $pendingComplianceCount }}
                        </span>
                    @endif
                </button>
            </div>

            <!-- Tab Content -->
            <div>
                <!-- Pending Requests Tab -->
                <div x-show="activeTab === 'pending'" class="bg-white rounded-xl shadow mt-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Pending Requests</h3>
                        <p class="text-sm text-gray-500 mt-1">Document requests awaiting approval</p>
                    </div>

                    @if($pendingSchedules->isEmpty())
                        <div class="p-6 text-center text-gray-500">No pending requests at this time.</div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($pendingByType as $typeName => $requests)
                                <div class="px-6 py-4">
                                    <div x-data="{ open: true }">
                                        <button @click="open = !open" class="flex justify-between items-center w-full text-left text-gray-700 font-semibold">
                                            <span>{{ $typeName }} ({{ $requests->count() }})</span>
                                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <div x-show="open" class="mt-3 space-y-4">
                                            @foreach($requests as $schedule)
                                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                                        <div>
                                                            <h4 class="text-gray-800 font-semibold">{{ $schedule->file->file_name }}</h4>
                                                            <div class="mt-2 text-sm text-gray-600 space-x-3">
                                                                <span>📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                                                <span>⏰ <strong>Time:</strong> {{ ucfirst($schedule->preferred_time) }}</span>
                                                            </div>
                                                            <div class="mt-1 text-sm text-gray-600">
                                                                🛠️ <strong>Est. Completion:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->addDays(5)->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 sm:mt-0 flex items-center space-x-2">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                                ⏳ Pending
                                                            </span>
                                                            <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST"
                                                                  onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500">
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @if(\Carbon\Carbon::parse($schedule->preferred_date)->subDays(1)->isPast())
                                                        <div class="mt-3">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                🔥 High Priority
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

                <!-- Accepted Tab -->
                <div x-show="activeTab === 'accepted'" class="bg-white rounded-xl shadow mt-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Accepted Requests</h3>
                        <p class="text-sm text-gray-500 mt-1">Document requests that have been approved</p>
                    </div>

                    @if($acceptedSchedules->isEmpty())
                        <div class="p-6 text-center text-gray-500">No accepted requests at this time.</div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($acceptedByType as $typeName => $requests)
                                <div class="px-6 py-4">
                                    <div x-data="{ open: true }">
                                        <button @click="open = !open" class="flex justify-between items-center w-full text-left text-gray-700 font-semibold">
                                            <span>{{ $typeName }} ({{ $requests->count() }})</span>
                                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <div x-show="open" class="mt-3 space-y-4">
                                            @foreach($requests as $schedule)
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                                                    <div class="flex flex-col sm:flex-row sm:justify-between">
                                                        <div>
                                                            <h4 class="text-gray-800 font-semibold">{{ $schedule->file->file_name }}</h4>
                                                            <div class="mt-2 text-sm text-gray-600 space-x-3">
                                                                <span>📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                                                <span>⏰ <strong>Time:</strong> {{ ucfirst($schedule->preferred_time) }}</span>
                                                            </div>
                                                            <div class="mt-1 text-sm text-gray-600">
                                                                🛠️ <strong>Est. Completion:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->addDays(5)->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 sm:mt-0 flex items-center">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✓ Approved
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($schedule->completion_date)
                                                        <div class="mt-3">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                🎉 Completed on {{ \Carbon\Carbon::parse($schedule->completion_date)->format('M d, Y') }}
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
                <div x-show="activeTab === 'compliance'" class="bg-white rounded-xl shadow mt-6">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Pending Compliance</h3>
                        <p class="text-sm text-gray-500 mt-1">Requests needing additional information or corrections</p>
                    </div>

                    @if($pendingComplianceSchedules->isEmpty())
                        <div class="p-6 text-center text-gray-500">No pending compliance requests at this time.</div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($pendingComplianceSchedules as $schedule)
                                <div class="px-6 py-4 bg-red-50">
                                    <div class="flex flex-col md:flex-row md:justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-gray-800 font-semibold">{{ $schedule->file->file_name }}</h4>
                                            <div class="mt-2 text-sm text-gray-600 space-x-3">
                                                <span>📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</span>
                                                <span>⏰ <strong>Time:</strong> {{ ucfirst($schedule->preferred_time) }}</span>
                                            </div>
                                            <div class="mt-4">
                                                <h5 class="text-sm font-bold text-red-700">Required Actions:</h5>
                                                <div class="mt-2 p-3 bg-white rounded border border-red-300 text-sm text-red-800">
                                                    {{ $schedule->remarks }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 md:mt-0 md:ml-6 flex flex-col justify-center items-start md:items-end">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                                                ⚠️ Pending Compliance
                                            </span>
                                            <a href="{{ route('student.file_requests.create', ['reference_id' => $schedule->id]) }}"
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
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

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection