<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class FileRequestsController extends Controller
{
    // Certificate categories - define which files are certificates
    private $certificationTypes = [
        'Units Earned',
        'Grades',
        'Grades (per semester/term)',
        'Grades (all terms attended)',
        'General Weighted Average',
        'Academic Completions',
        'Graduation',
        'As a candidate for Graduation',
        'As Honor Graduation',
        'Subjects Enrolled / Curriculum',
        'Enrollment / Registration',
        'English as a Medium of Instruction',
        'Course Description'
    ];

    // Files that can reference previous school years and semesters
    private $historicalFileTypes = [
        'Grades',
        'Grades (per semester/term)',
        'Grades (all terms attended)',
        'General Weighted Average',
        'Units Earned',
        'Subjects Enrolled / Curriculum',
    ];

    /**
     * Show the schedule and file request form.
     */
    public function create(Request $request)
{
    // Get reference request if exists
    $referenceRequest = null;
    if ($request->has('reference_id')) {
        $referenceRequest = Schedule::with('file')
            ->where('id', $request->reference_id)
            ->where('status', 'rejected')
            ->first();
        
        if (!$referenceRequest) {
            return redirect()->route('student.file_requests.create')
                ->with('error', 'Invalid compliance request reference');
        }
    }

    // Get all available files with their requirements
    $files = File::where('is_available', true)->with('requirements')->get();
    $schoolYears = SchoolYear::with('semesters')->orderBy('year', 'desc')->get();
    $semesters = Semester::all();

    // Get the latest school year and semester for record keeping
    $latestSchoolYear = SchoolYear::latest('created_at')->first();
    $latestSemester = Semester::latest('created_at')->first();

    // Separate regular documents from certificates
    $regularFiles = collect();
    $certificateFiles = collect();
    
    foreach ($files as $file) {
        if (in_array($file->file_name, $this->certificationTypes)) {
            $certificateFiles->push($file);
        } else {
            $regularFiles->push($file);
        }
    }

    // Define certification categories
    $certificationCategories = [
        'Certifications' => $this->certificationTypes
    ];

    // Define which files can have historical requests
    $historicalFileTypes = $this->historicalFileTypes;

    // Get date constraints for the form
    $minDate = $this->addWorkingDays(Carbon::today(), 3)->format('Y-m-d');
    $maxDate = $this->addWorkingDays(Carbon::today(), 7)->format('Y-m-d');

    return view('student.file_requests.create', compact(
        'regularFiles',
        'certificateFiles',
        'certificationCategories', 
        'schoolYears', 
        'semesters',
        'minDate',
        'maxDate',
        'latestSchoolYear',
        'latestSemester',
        'historicalFileTypes',
        'referenceRequest' 
    ));
}

    /**
     * Store multiple file requests with validation and safety checks.
     */
  /**
 * Store multiple file requests with validation and safety checks.
 */
public function store(Request $request)
{
    try {
        $student = Auth::guard('student')->user();
        $latestSchoolYear = SchoolYear::latest('created_at')->first();
        $latestSemester = Semester::latest('created_at')->first();

        // Get original request early so we can properly update it regardless of outcome
        $originalRequest = null;
        if ($request->has('reference_id')) {
            $originalRequest = Schedule::find($request->reference_id);
            
            if (!$originalRequest || $originalRequest->student_id != $student->id || $originalRequest->status != 'rejected') {
                return back()->with('error', 'Invalid compliance request reference.');
            }
        }

        if (!$latestSchoolYear || !$latestSemester) {
            return redirect()->back()->with('error', 'No school year or semester has been set by the admin.');
        }

        $today = Carbon::today();
        $minDate = $this->addWorkingDays($today->copy(), 3);
        $maxDate = $this->addWorkingDays($today->copy(), 7);

        // Validate the request data
        $request->validate([
            'preferred_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($minDate, $maxDate) {
                    $selectedDate = Carbon::parse($value);
                    if ($selectedDate->lessThan($minDate) || $selectedDate->greaterThan($maxDate)) {
                        $fail("Date must be between {$minDate->toDateString()} and {$maxDate->toDateString()}.");
                    }
                    if ($selectedDate->isWeekend()) {
                        $fail("Date falls on a weekend. Please choose a working day.");
                    }
                }
            ],
            'preferred_time' => 'required|in:AM,PM',
            'files' => 'required|array',
            'files.*.copies' => 'nullable|integer|min:1|max:5',
            'files.*.reason' => 'nullable|string|max:500',
            'files.*.manual_school_year' => 'nullable|string|max:255',
            'files.*.manual_semester' => 'nullable|string|max:255',
            'reference_id' => 'nullable|exists:schedules,id'
        ]);

        // Get the selected files
        $files = $request->input('files', []);
        if (empty(array_filter($files, function($file) {
            return isset($file['selected']);
        }))) {
            return back()->with('error', 'Please select at least one file to request.');
        }

        // If this is a compliance request, validate the original request
        if ($originalRequest) {
            // Verify at least one selected file matches the original request file
            $hasMatchingFile = false;
            foreach ($files as $fileId => $fileData) {
                if (isset($fileData['selected']) && $fileId == $originalRequest->file_id) {
                    $hasMatchingFile = true;
                    break;
                }
            }
            
            if (!$hasMatchingFile) {
                return back()->with('error', 'Your compliance request must include the original document: ' . $originalRequest->file->file_name);
            }
        }

        $createdCount = 0;
        $errors = [];

        // Process each selected file
        foreach ($files as $fileId => $fileData) {
            if (!isset($fileData['selected'])) continue;

            $file = File::with('requirements')->find($fileId);
            if (!$file) {
                $errors[] = "Invalid file selection.";
                continue;
            }

            // Validate requirements if the file has any
            if ($file->requirements->isNotEmpty()) {
                $requiredIds = $file->requirements->pluck('id')->toArray();
                $submittedReqs = isset($fileData['requirements']) ? $fileData['requirements'] : [];
                
                // Convert to strings for comparison (form may submit as strings)
                $requiredIds = array_map('strval', $requiredIds);
                sort($requiredIds);
                sort($submittedReqs);

                if (count($requiredIds) !== count($submittedReqs) || 
                    array_diff($requiredIds, $submittedReqs)) {
                    $errors[] = "All requirements must be selected for '{$file->file_name}'.";
                    continue;
                }
            }

            // Validate copies
            $copies = isset($fileData['copies']) ? (int)$fileData['copies'] : 1;
            if ($copies < 1 || $copies > 5) {
                $errors[] = "Total copies for '{$file->file_name}' must be between 1 and 5.";
                continue;
            }

            // Set up school year and semester data
            $scheduleData = [
                'file_id' => $file->id,
                'student_id' => $student->id,
                'school_year_id' => $latestSchoolYear->id,
                'semester_id' => $latestSemester->id,
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'reason' => $fileData['reason'] ?? null,
                'copies' => $copies,
                'status' => 'pending',
                'school_year' => $latestSchoolYear->year,
                'semester' => $latestSemester->name,
                'reference_id' => $request->reference_id ?? null,
            ];

            // Only add manual school year and semester if the file type allows historical references
            if (in_array($file->file_name, $this->historicalFileTypes)) {
                if (!empty($fileData['manual_school_year'])) {
                    $scheduleData['manual_school_year'] = $fileData['manual_school_year'];
                }
                
                if (!empty($fileData['manual_semester'])) {
                    $scheduleData['manual_semester'] = $fileData['manual_semester'];
                }
            }
            
            // Create the schedule entry
            $schedule = Schedule::create($scheduleData);
            $createdCount++;
        }

        // Check if there were any errors
        if (!empty($errors)) {
            return back()->withInput()->with('error', implode('<br>', $errors));
        }

        // Check if any requests were created
        if ($createdCount === 0) {
            return back()->with('error', 'No valid file requests were submitted.');
        }

        // Update original request status if this was a compliance request
        if ($originalRequest && $createdCount > 0) {
            $originalRequest->update(['status' => 'compliance_addressed']);
        }

        return redirect()->route('student.dashboard')->with('message', 'File request(s) scheduled successfully! You will be notified when your documents are ready for pickup.');

    } catch (Exception $e) {
        // If there was an exception and we had an original request, make sure we log it
        if (isset($originalRequest) && $originalRequest) {
            \Log::error('Failed to update compliance request: ' . $e->getMessage());
        }
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

    /**
     * Display all requests for the authenticated student.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        $requests = Schedule::with(['file', 'schoolYear', 'semester', 'followupRequest'])
                            ->where('student_id', $student->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('student.file_requests.index', compact('requests'));
    }

    /**
     * Show details of a specific request.
     */
    public function show($id)
    {
        $student = Auth::guard('student')->user();
        $request = Schedule::with(['file', 'schoolYear', 'semester'])
                          ->where('id', $id)
                          ->where('student_id', $student->id)
                          ->firstOrFail();

        return view('student.file_requests.show', compact('request'));
    }

    /**
     * Cancel a pending request.
     */
    public function cancelRequest($id)
    {
        $student = Auth::guard('student')->user();
        $schedule = Schedule::where('id', $id)
                            ->where('student_id', $student->id)
                            ->where('status', 'pending')
                            ->first();

        if (!$schedule) {
            return back()->with('error', 'Schedule request not found or already processed.');
        }

        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('student.file_requests.index')
                        ->with('success', 'Document request cancelled successfully.');
    }

    /**
     * Get requirements for a specific file.
     */
    public function getRequirements($id)
    {
        $file = File::with('requirements')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'requirements' => $file->requirements->map(function($req) {
                return [
                    'id' => $req->id,
                    'name' => $req->name,
                    // include any other fields you need
                ];
            })
        ]);
    }

    /**
     * Helper: Add working days to a date.
     */
    private function addWorkingDays($date, $days)
    {
        $count = 0;
        while ($count < $days) {
            $date->addDay();
            if (!$date->isWeekend()) {
                $count++;
            }
        }
        return $date;
    }
}