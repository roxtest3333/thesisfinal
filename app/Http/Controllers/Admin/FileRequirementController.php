<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FileRequirement;
use Illuminate\Http\Request;

class FileRequirementController extends Controller
{
    public function index()
    {
        $files = File::with('requirements')->get();
        return view('admin.file_requirements.index', compact('files'));
    }

    public function create()
    {
        $files = File::all();
        return view('admin.file_requirements.create', compact('files'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_id' => 'required|exists:files,id',
            'name' => 'required|string|max:255',
        ]);

        FileRequirement::create($validated);

        return redirect()->route('admin.file-requirements.index')->with('success', 'Requirement added successfully.');
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
    public function destroy(FileRequirement $fileRequirement)
    {
        $fileRequirement->delete();
        
        return redirect()->route('admin.file-requirements.index')
            ->with('success', 'Requirement deleted successfully.');
    }
}
