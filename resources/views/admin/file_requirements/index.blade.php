@extends('layouts.admin')

@section('content')
<h1>File Requirements</h1>

@foreach($files as $file)
    <h3>{{ $file->file_name }}</h3>
    <ul>
        @foreach($file->requirements as $requirement)
            <li>{{ $requirement->name }} – 
                <a href="{{ route('admin.file-requirements.edit', $requirement->id) }}">Edit</a>
            </li>
        @endforeach
    </ul>
@endforeach

<a href="{{ route('admin.file-requirements.create') }}" class="btn btn-primary">Add Requirement</a>
@endsection
