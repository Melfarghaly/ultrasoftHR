@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')
    <h1>Edit Project</h1>

    <!-- Display success message if any -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="box box-primary row p-3">
        <div class="col-md-6 ">
            <form action="{{ route('hr_projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Project Title Field -->
                <div class="form-group">
                    <label for="Project_title">Project Title</label>
                    <input type="text" class="form-control" id="project_name" name="name"
                        value="{{ old('name', $project->name) }}" required>
                </div>

                <!-- Project Description Field -->
                <div class="form-group">
                    <label for="Project_description">Project Description</label>
                    <textarea class="form-control" id="Project_description" name="description" required>{{ old('description', $project->description) }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="{{ route('hr_projects.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
