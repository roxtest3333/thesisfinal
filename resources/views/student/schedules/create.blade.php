@extends('layouts.default')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-3xl bg-white bg-opacity-50 backdrop-blur-sm shadow-lg rounded-lg p-8 sm:px-10">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800 drop-shadow-lg">Schedule File Retrieval</h2>
        </div>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong>Submission failed!</strong> Please check the form and try again.
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.schedules.store') }}" class="space-y-4">
            @csrf
            
            <!-- Select File -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Select File to Retrieve</label>
                <select class="form-control w-full" name="file_id" id="file_id" required>
                    <option value="">-- Select a file --</option> 
                    @foreach($files as $file)
                        <option value="{{ $file->id }}" 
                            {{ old('file_id') == $file->id ? 'selected' : '' }}>
                            {{ $file->file_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Manual School Year & Semester (Only for COR/COG) -->
            <div id="manual_sy_sem" class="{{ old('manual_school_year') || old('manual_semester') ? '' : 'hidden' }}">
                <label class="block text-gray-700 font-bold mb-1">Enter School Year</label>
                <input type="text" name="manual_school_year" class="form-control w-full" value="{{ old('manual_school_year') }}">

                <label class="block text-gray-700 font-bold mb-1">Enter Semester</label>
                <input type="text" name="manual_semester" class="form-control w-full" value="{{ old('manual_semester') }}">
            </div>

            <!-- Preferred Date -->
            <div class="mb-3">
                <label for="preferred_date" class="form-label">Preferred Date</label>
                <input type="text" class="form-control" id="preferred_date" name="preferred_date" placeholder="Select a date" readonly required>
                <div id="date-error" class="text-red-500 text-sm hidden mt-1"></div>
                @error('preferred_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Preferred Time -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Preferred Time</label>
                <select name="preferred_time" class="form-control w-full" required>
                    <option value="AM" {{ old('preferred_time') == 'AM' ? 'selected' : '' }}>Morning (AM)</option>
                    <option value="PM" {{ old('preferred_time') == 'PM' ? 'selected' : '' }}>Afternoon (PM)</option>
                </select>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Reason for Retrieval</label>
                <input type="text" name="reason" class="form-control w-full" value="{{ old('reason') }}" required>
            </div>

            <!-- Copies -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Number of Copies</label>
                <input type="number" name="copies" min="1" class="form-control w-full" value="{{ old('copies', 1) }}" required>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition transform hover:scale-105">
                Submit Request
            </button>
        </form>
    </div>
</div>

<!-- Load Flatpickr resources over HTTPS -->
</script>

<!-- Combined JavaScript for Date Picker and File Selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the file dropdown and manual fields elements
        const fileDropdown = document.getElementById('file_id');
        const manualFields = document.getElementById('manual_sy_sem');
        const dateErrorDisplay = document.getElementById('date-error');
        
        // Function to calculate valid date range (3-7 working days from today)
        function getValidDateRange() {
            let today = new Date();
            let minDate = new Date(today);
            let maxDate = new Date(today);
            
            let daysToAdd = 0;
            while (daysToAdd < 3) { // Find the minimum date (3 working days ahead)
                minDate.setDate(minDate.getDate() + 1);
                if (minDate.getDay() !== 0 && minDate.getDay() !== 6) {
                    daysToAdd++;
                }
            }
    
            daysToAdd = 0;
            while (daysToAdd < 7) { // Find the maximum date (7 working days ahead)
                maxDate.setDate(maxDate.getDate() + 1);
                if (maxDate.getDay() !== 0 && maxDate.getDay() !== 6) {
                    daysToAdd++;
                }
            }
    
            return { minDate, maxDate };
        }
    
        // Get the valid date range
        let dateRange = getValidDateRange();
        
        // Function to check if a date is a weekend
        function isWeekend(date) {
            let day = date.getDay();
            return day === 6 || day === 0; // Saturday = 6, Sunday = 0
        }
        
        // Function to display date error message
        function showDateError(message) {
            dateErrorDisplay.textContent = message;
            dateErrorDisplay.classList.remove('hidden');
            setTimeout(() => {
                dateErrorDisplay.classList.add('hidden');
            }, 3000);
        }
        
        // Initialize Flatpickr with custom configuration
        const datePicker = flatpickr("#preferred_date", {
            dateFormat: "Y-m-d",
            minDate: dateRange.minDate,
            maxDate: dateRange.maxDate,
            disable: [
                function(date) {
                    return isWeekend(date); // Disable weekends
                }
            ],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                // Add a class to weekend days for styling
                if (isWeekend(dayElem.dateObj)) {
                    dayElem.className += " weekend-day";
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Additional validation when a date is selected
                if (selectedDates.length > 0) {
                    let selectedDate = selectedDates[0];
                    
                    if (isWeekend(selectedDate)) {
                        showDateError("Weekends are not available for scheduling.");
                        instance.clear();
                    } else if (selectedDate < dateRange.minDate) {
                        showDateError("Date must be at least 3 working days from today.");
                        instance.clear();
                    } else if (selectedDate > dateRange.maxDate) {
                        showDateError("Date must be within 7 working days from today.");
                        instance.clear();
                    }
                }
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 0) {
                    return;
                }
                
                let selectedDate = selectedDates[0];
                
                // This is a backup validation, should not be needed due to disabled dates
                if (isWeekend(selectedDate)) {
                    showDateError("Weekends are not available for scheduling.");
                    instance.clear();
                }
            }
        });
        
        // Add event listener after Flatpickr is initialized
        document.addEventListener('click', function(e) {
            // Check if the clicked element is a disabled day in the date picker
            if (e.target.classList.contains('flatpickr-day') && e.target.classList.contains('disabled')) {
                // Check if it's a weekend
                if (e.target.classList.contains('weekend-day')) {
                    showDateError("Weekends are not available for scheduling.");
                } else if (e.target.classList.contains('flatpickr-disabled')) {
                    // Check if it's before the minimum date or after the maximum date
                    const dayDate = new Date(e.target.getAttribute('aria-label'));
                    if (dayDate < dateRange.minDate) {
                        showDateError("Date must be at least 3 working days from today.");
                    } else {
                        showDateError("Date must be within 7 working days from today.");
                    }
                }
            }
        });
        
        // Show/hide manual fields based on file selection
        if (fileDropdown) {
            fileDropdown.addEventListener('change', function() {
                const selectedFileName = fileDropdown.options[fileDropdown.selectedIndex].text;
                if (selectedFileName === 'COR' || selectedFileName === 'COG') {
                    manualFields.classList.remove('hidden');
                } else {
                    manualFields.classList.add('hidden');
                }
            });
            
            // Check initial value on page load
            const selectedFileName = fileDropdown.options[fileDropdown.selectedIndex].text;
            if (selectedFileName === 'COR' || selectedFileName === 'COG') {
                manualFields.classList.remove('hidden');
            }
        }
    });
</script>

<style>
    /* Custom styles for the date picker */
    .flatpickr-day.weekend-day {
        background-color: #fee2e2;
        color: #ef4444;
        text-decoration: line-through;
    }
    
    .flatpickr-day.selected {
        background: #6366f1;
        border-color: #6366f1;
    }
    
    .flatpickr-day.selected:hover {
        background: #4f46e5;
        border-color: #4f46e5;
    }
    
    .flatpickr-day:hover {
        background: #e0e7ff;
    }
</style>

@endsection