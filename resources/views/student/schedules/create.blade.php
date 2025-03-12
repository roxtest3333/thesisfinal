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
                        <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Manual School Year & Semester (Only for COR/COG) -->
            <div id="manual_sy_sem" class="hidden">
                <label class="block text-gray-700 font-bold mb-1">Enter School Year</label>
                <input type="text" name="manual_school_year" class="form-control w-full">

                <label class="block text-gray-700 font-bold mb-1">Enter Semester</label>
                <input type="text" name="manual_semester" class="form-control w-full">
            </div>

            <!-- Preferred Date -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Preferred Date</label>
                <input type="date" id="preferred_date" name="preferred_date" class="form-control w-full"
                    min="{{ now()->addDays(3)->toDateString() }}"
                    max="{{ now()->addDays(7)->toDateString() }}"
                    required>
            </div>

            <!-- Preferred Time -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Preferred Time</label>
                <select name="preferred_time" class="form-control w-full" required>
                    <option value="AM">Morning (AM)</option>
                    <option value="PM">Afternoon (PM)</option>
                </select>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Reason for Retrieval</label>
                <input type="text" name="reason" class="form-control w-full" required>
            </div>

            <!-- Copies -->
            <div>
                <label class="block text-gray-700 font-bold mb-1">Number of Copies</label>
                <input type="number" name="copies" min="1" class="form-control w-full" required>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-lg shadow-md transition transform hover:scale-105">
                Submit Request
            </button>
        </form>
    </div>
</div>

<!-- JavaScript for COR/COG Selection & Date Restrictions -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileDropdown = document.getElementById('file_id');
    const manualFields = document.getElementById('manual_sy_sem');
    const preferredDateInput = document.getElementById('preferred_date');

    // Function to check if a date is a weekend
    function isWeekend(date) {
        let day = date.getDay();
        return day === 6 || day === 0; // Saturday = 6, Sunday = 0
    }

    // Ensure manual school year & semester fields show only for COR/COG
    if (fileDropdown) {
        fileDropdown.addEventListener('change', function () {
            const selectedFileName = fileDropdown.options[fileDropdown.selectedIndex].text;
            if (selectedFileName === 'COR' || selectedFileName === 'COG') {
                manualFields.classList.remove('hidden');
            } else {
                manualFields.classList.add('hidden');
            }
        });
    }

    // Prevent selecting weekends and enforce date range
    if (preferredDateInput) {
        preferredDateInput.addEventListener('input', function () {
            let selectedDate = new Date(this.value);
            let minDate = new Date();
            minDate.setDate(minDate.getDate() + 3); // 3 days from today
            let maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 7); // 7 days from today

            if (isWeekend(selectedDate)) {
                alert("Scheduling on weekends is not allowed. Please select a weekday.");
                this.value = "";
            } else if (selectedDate < minDate || selectedDate > maxDate) {
                alert("Preferred date must be between 3 to 7 days from today.");
                this.value = "";
            }
        });
    }
});
</script>

@endsection
