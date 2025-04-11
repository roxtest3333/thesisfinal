@extends('layouts.default')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- History Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Request History</h2>
                    <p class="text-gray-600">View all your document requests and their status</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Sorting Controls -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800">Sort By</h3>
            </div>
            <div x-data="{ activeSort: '{{ $sortBy ?? 'date' }}' }" class="p-4">
                <!-- Desktop Sort Buttons -->
                <div class="hidden sm:flex flex-wrap gap-2">
                    <a href="{{ route('student.requests.sort', 'date') }}" 
                       class="inline-flex items-center px-3 py-2 border rounded-md text-sm font-medium"
                       :class="activeSort == 'date' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 border-gray-300 hover:bg-gray-50'">
                        Date (newest first)
                    </a>
                    <a href="{{ route('student.requests.sort', 'type') }}" 
                       class="inline-flex items-center px-3 py-2 border rounded-md text-sm font-medium"
                       :class="activeSort == 'type' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 border-gray-300 hover:bg-gray-50'">
                        Document Type
                    </a>
                    <a href="{{ route('student.requests.sort', 'status') }}" 
                       class="inline-flex items-center px-3 py-2 border rounded-md text-sm font-medium"
                       :class="activeSort == 'status' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'text-gray-700 border-gray-300 hover:bg-gray-50'">
                        Status
                    </a>
                </div>
                
                <!-- Mobile Dropdown -->
                <div class="sm:hidden">
                    <select 
                        x-model="activeSort"
                        @change="window.location.href = '{{ route('student.requests.sort', '') }}/' + $event.target.value"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="date">Date (newest first)</option>
                        <option value="type">Document Type</option>
                        <option value="status">Status</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- History List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-800">All Requests</h3>
            </div>

            @if($allSchedules->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    No request history found.
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Document
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Requested
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Scheduled Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($allSchedules as $schedule)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $schedule->file->file_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $schedule->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}
                                            ({{ ucfirst($schedule->preferred_time) }})
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($schedule->status)
                                            @case('pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                    Pending
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                                @break
                                            @case('rejected')
                                                @if($schedule->followupRequest)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                        Compliance Addressed
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Pending Compliance
                                                    </span>
                                                @endif
                                                @break
                                            @case('cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Cancelled
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                                @break
                                            @case('compliance_addressed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                    Compliance Addressed
                                                </span>
                                                @break
                                            @default
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $schedule->status }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($schedule->status == 'pending')
                                            <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST" 
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                            </form>
                                        @elseif($schedule->status == 'rejected')
                                            <a href="{{ route('student.file_requests.create') }}" class="text-blue-600 hover:text-blue-900">Submit New Request</a>
                                        @else
                                            <span class="text-gray-400">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Card View (optimized for small screens) -->
                <div class="sm:hidden divide-y divide-gray-200">
                    @foreach($allSchedules as $schedule)
                        <div x-data="{ expanded: false }" class="p-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm font-medium text-gray-900 pr-2">{{ $schedule->file->file_name }}</div>
                                @switch($schedule->status)
                                    @case('pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                            Pending
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                        @break
                                    @case('rejected')
                                        @if($schedule->followupRequest)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                Compliance Addressed
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Pending Compliance
                                            </span>
                                        @endif
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Cancelled
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Completed
                                        </span>
                                        @break
                                    @case('compliance_addressed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Compliance Addressed
                                        </span>
                                        @break
                                    @default
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $schedule->status }}
                                        </span>
                                @endswitch
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                <p>Scheduled: {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }} ({{ ucfirst($schedule->preferred_time) }})</p>
                                <p>Requested: {{ $schedule->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="mt-3 flex space-x-2">
                                @if($schedule->status == 'pending')
                                    <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Cancel
                                        </button>
                                    </form>
                                @elseif($schedule->status == 'rejected')
                                    <a href="{{ route('student.file_requests.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Submit New
                                    </a>
                                @endif
                                
                                <button 
                                    @click="expanded = !expanded" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    aria-expanded="false"
                                    :aria-expanded="expanded"
                                >
                                    <span x-text="expanded ? 'Hide Details' : 'Show Details'">Show Details</span>
                                    <svg 
                                        :class="{'rotate-180': expanded}" 
                                        class="w-4 h-4 ml-1 transform transition-transform" 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        viewBox="0 0 20 20" 
                                        fill="currentColor"
                                    >
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div 
                                x-show="expanded"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="mt-4 bg-gray-50 p-3 rounded-md text-sm"
                            >
                                @if($schedule->status == 'rejected' && $schedule->remarks)
                                    <div class="mb-3">
                                        <h5 class="font-bold text-red-700">Required Actions:</h5>
                                        <div class="mt-1 p-2 bg-white rounded border border-red-300">
                                            {{ $schedule->remarks }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($schedule->reason)
                                    <div class="mb-3">
                                        <h5 class="font-medium text-gray-700">Reason for Request:</h5>
                                        <p class="text-gray-600">{{ $schedule->reason }}</p>
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    <h5 class="font-medium text-gray-700">Copies:</h5>
                                    <p class="text-gray-600">{{ $schedule->copies ?? 1 }}</p>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium text-gray-700">Last Updated:</h5>
                                    <p class="text-gray-600">{{ $schedule->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                    <div class="flex justify-center sm:justify-between items-center flex-wrap">
                        {{ $allSchedules->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Alpine.js with proper version pinning -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
@endsection