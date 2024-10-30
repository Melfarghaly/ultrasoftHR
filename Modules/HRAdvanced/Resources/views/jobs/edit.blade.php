@extends('layouts.app')

@section('content')
@include('hradvanced::layouts.nav')
    <h1>Edit Job</h1>

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
        <form action="{{ route('hr_jobs.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Job Title Field -->
            <div class="form-group">
                <label for="job_title">Job Title</label>
                <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title', $job->job_title) }}" required>
            </div>

            <!-- Job Description Field -->
            <div class="form-group">
                <label for="job_description">Job Description</label>
                <textarea class="form-control" id="job_description" name="job_description" required>{{ old('job_description', $job->job_description) }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Job</button>
            <a href="{{ route('hr_jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
