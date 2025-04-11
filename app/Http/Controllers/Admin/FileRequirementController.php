<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Schedule;
use App\Models\FileRequirement;
use Illuminate\Http\Request;

class FileRequirementController extends Controller
{
    public function index()
    {
        $files = File::with('requirements')->get();
        return view('admin.file_requirements.index', compact('files'));
    }

    // Add to your request creation method
public function create(Request $request)
{
    $referenceId = $request->query('reference_id');
    $referenceRequest = null;
    
    if ($referenceId) {
        $referenceRequest = Schedule::find($referenceId);
    }
    
    // Rest of your code...
    return view('student.file_requests.create', compact('referenceRequest'));
}

// Add to your store method
public function store(Request $request)
{
    // Your existing request creation logic
    
    // If this addresses a compliance issue, update original request status
    if ($request->has('reference_id')) {
        $originalRequest = Schedule::find($request->reference_id);
        if ($originalRequest && $originalRequest->status === 'rejected') {
            $originalRequest->status = 'compliance_addressed';
            $originalRequest->save();
        }
    }
    
    return redirect()->route('student.dashboard');
}

    public function edit(FileRequirement $fileRequirement)
    {
        $files = File::all();
        return view('admin.file_requirements.edit', compact('fileRequirement', 'files'));
    }

    public function update(Request $request, FileRequirement $fileRequirement)
    {
        $validated = $request->validate([
            'file_id' => 'required|exists:files,id',
            'name' => 'required|string|max:255',
        ]);

        $fileRequirement->update($validated);

        return redirect()->route('admin.file-requirements.index')->with('success', 'Requirement updated successfully.');
    }
}
