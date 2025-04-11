@extends('layouts.admin')

@section('content')
<h1>Add New File Requirement</h1>

<form method="POST" action="{{ route('admin.file-requirements.store') }}">
    @csrf
    <div>
        <label for="file_id">File:</label>
        <select name="file_id" required>
            @foreach($files as $file)
                <option value="{{ $file->id }}">{{ $file->file_name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="name">Requirement Name:</label>
        <input type="text" name="name" required>
    </div>
    <button type="submit">Add Requirement</button>
</form>
@endsection
