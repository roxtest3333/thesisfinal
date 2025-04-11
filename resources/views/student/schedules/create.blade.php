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
                <input type="text" class="form-control" id="preferred_date" name="preferred_date" required>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css">

<!-- JavaScript for COR/COG Selection & Date Restrictions -->
<script>
    $(document).ready(function() {
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
    
        let dateRange = getValidDateRange();
    
        $("#preferred_date").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: dateRange.minDate,
            maxDate: dateRange.maxDate,
            beforeShowDay: function(date) {
                let day = date.getDay();
                return [(day !== 0 && day !== 6)]; // Disable weekends
            }
        });
    });
    </script>

@endsection
