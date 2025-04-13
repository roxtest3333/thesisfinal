{{-- resources\views\student\history.blade.php --}}
@extends('layouts.default')

@section('content')
<div class="dashboard-container min-h-screen bg-gray-100 min-w-[75vw]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- History Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Request History</h2>
                    <p class="text-gray-600 mt-1">View all your document requests and their status</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row gap-6 mb-8">
            <!-- Filter Section -->
            <div class="w-full lg:w-1/2 bg-white rounded-xl shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Requests</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('student.history') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                    <option value="all" {{ $currentStatus == 'all' ? 'selected' : '' }}>All Statuses</option>
                                    <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $currentStatus == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Pending Compliance" {{ $currentStatus == 'Pending Compliance' ? 'selected' : '' }}>Pending Compliance</option>
                                </select>
                            </div>
        
                            <!-- Document Type Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                                <select name="document_type" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                    <option value="all" {{ $currentDocumentType == 'all' ? 'selected' : '' }}>All Documents</option>
                                    @foreach($documentTypes as $type)
                                        <option value="{{ $type }}" {{ $currentDocumentType == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
        
                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Apply Filters
                            </button>
                            <a href="{{ route('student.history') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        
            <!-- Sorting Section -->
            <div class="w-full lg:w-1/2 bg-white rounded-xl shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Sort By</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('student.history') }}">
                        <!-- Hidden fields to preserve filters -->
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if(request('document_type'))
                            <input type="hidden" name="document_type" value="{{ request('document_type') }}">
                        @endif
        
                        <div class="flex flex-wrap gap-4">
                            <!-- Sort Field Selection -->
                            <div class="flex-1 min-w-[150px]">
                                <select name="sort" 
                                        onchange="this.form.submit()"
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                    <option value="preferred_date" {{ request('sort') == 'preferred_date' ? 'selected' : '' }}>Date</option>
                                    <option value="file_name" {{ request('sort') == 'file_name' ? 'selected' : '' }}>Document Type</option>
                                    <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                                </select>
                            </div>
        
                            <!-- Direction Toggle -->
                            <div class="flex items-center">
                                <input type="hidden" name="direction" value="{{ request('direction') == 'asc' ? 'desc' : 'asc' }}">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <span>{{ request('direction') == 'asc' ? 'Ascending' : 'Descending' }}</span>
                                    <svg class="ml-1 h-4 w-4 transform {{ request('direction') == 'asc' ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- History List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Requests</h3>
            </div>

            @if($allSchedules->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h4 class="mt-2 text-sm font-medium text-gray-900">No request history found</h4>
                    <p class="mt-1 text-sm text-gray-500">Your document request history will appear here</p>
                </div>
            @else
                <!-- Desktop Table View -->
                <div class="hidden sm:block">
                    <div class="overflow-x-auto">
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
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $schedule->file->file_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $schedule->file->file_type }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $schedule->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $schedule->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($schedule->preferred_time) }}</div>
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
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Pending Compliance
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completed
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
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-200">Cancel</button>
                                            </form>
                                        @elseif($schedule->status == 'rejected')
                                            <a href="{{ route('student.file_requests.create', ['reference_id' => $schedule->id]) }}" class="text-blue-600 hover:text-blue-900 transition duration-200">Submit New</a>
                                        @else
                                            <span class="text-gray-400">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Expandable Row for Additional Details -->
                                @if($schedule->status == 'rejected' && $schedule->remarks || $schedule->reason)
                                <tr class="bg-gray-50">
                                    <td colspan="5" class="px-6 py-4">
                                        <div x-data="{ expanded: false }" class="w-full">
                                            <button 
                                                @click="expanded = !expanded" 
                                                class="flex items-center justify-center w-full py-2 text-sm text-gray-600 hover:text-gray-900 focus:outline-none transition duration-200"
                                                aria-expanded="false"
                                                :aria-expanded="expanded"
                                            >
                                                <span x-text="expanded ? 'Hide additional details' : 'Show additional details'">Show additional details</span>
                                                <svg :class="{'rotate-180': expanded}" class="w-4 h-4 ml-1 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div 
                                                x-show="expanded" 
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="mt-2 space-y-4"
                                            >
                                                @if($schedule->status == 'rejected' && $schedule->remarks)
                                                    <div>
                                                        <h5 class="text-sm font-bold text-red-700">Required Actions:</h5>
                                                        <div class="mt-1 p-3 bg-white rounded-lg border border-red-300 text-sm">
                                                            {{ $schedule->remarks }}
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($schedule->reason)
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-700">Reason for Request:</h5>
                                                        <p class="text-sm text-gray-600">{{ $schedule->reason }}</p>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex space-x-8">
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-700">Copies:</h5>
                                                        <p class="text-sm text-gray-600">{{ $schedule->copies ?? 1 }}</p>
                                                    </div>
                                                    
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-700">Last Updated:</h5>
                                                        <p class="text-sm text-gray-600">{{ $schedule->updated_at->format('M d, Y h:i A') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="sm:hidden divide-y divide-gray-200">
                    @foreach($allSchedules as $schedule)
                    <div x-data="{ expanded: false }" class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900">{{ $schedule->file->file_name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($schedule->preferred_date)->format('M d, Y') }} ({{ ucfirst($schedule->preferred_time) }})
                                        </p>
                                    </div>
                                    <div>
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
                                </div>
                                
                                <div class="mt-4 flex space-x-2">
                                    @if($schedule->status == 'pending')
                                        <form action="{{ route('student.schedules.cancel', $schedule->id) }}" method="POST" 
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to cancel this request?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($schedule->status == 'rejected')
                                        <a href="{{ route('student.file_requests.create', ['reference_id' => $schedule->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                            Submit New
                                        </a>
                                    @endif
                                    
                                    <button 
                                        @click="expanded = !expanded" 
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
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
                                    class="mt-4 bg-gray-50 p-4 rounded-lg text-sm"
                                >
                                    @if($schedule->status == 'rejected' && $schedule->remarks)
                                        <div class="mb-3">
                                            <h5 class="font-bold text-red-700">Required Actions:</h5>
                                            <div class="mt-1 p-2 bg-white rounded-lg border border-red-300">
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
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    {{ $allSchedules->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection