@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0 font-weight-bold text-primary">File Requirements Management</h3>
            <a href="{{ route('admin.file-requirements.create') }}" class="btn btn-primary btn-sm">
                Add New Requirement
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @foreach($files as $file)
                    <div class="mb-4">
                        <h4 class="font-weight-bold text-dark">{{ $file->file_name }}</h4>
                        <div class="list-group">
                            @foreach($file->requirements as $requirement)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $requirement->name }}</span>
                                    <div>
                                        <a href="{{ route('admin.file-requirements.edit', $requirement->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        {{-- <form action="{{ route('admin.file-requirements.destroy', $requirement->id) }}" 
                                              method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this requirement?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection