@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New File Requirement</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.file-requirements.store') }}">
                @csrf
                <div class="form-group">
                    <label for="file_id" class="font-weight-bold">File</label>
                    <select name="file_id" class="form-control" required>
                        <option value="">Select File</option>
                        @foreach($files as $file)
                            <option value="{{ $file->id }}">{{ $file->file_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Requirement Name</label>
                    <input type="text" name="name" class="form-control" required 
                           placeholder="Enter requirement name">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Requirement
                    </button>
                    <a href="{{ route('admin.file-requirements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection