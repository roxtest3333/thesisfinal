@extends('layouts.admin')

@section('content')
<h1>Edit Requirement</h1>

<form method="POST" action="{{ route('admin.file-requirements.update', $fileRequirement->id) }}">
    @csrf
    @method('PUT')
    <div>
        <label for="file_id">File:</label>
        <select name="file_id" required>
            @foreach($files as $file)
                <option value="{{ $file->id }}" {{ $file->id == $fileRequirement->file_id ? 'selected' : '' }}>
                    {{ $file->file_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="name">Requirement Name:</label>
        <input type="text" name="name" value="{{ $fileRequirement->name }}" required>
    </div>
    <button type="submit">Update Requirement</button>
</form>
@endsection
