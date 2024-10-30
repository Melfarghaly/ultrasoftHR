@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')
    <h1>Edit Penalty</h1>

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
            <form action="{{ route('penalties.update', $penalty->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Project Title Field -->
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id"
                        value="{{ old('name', $penalty->employee_id) }}" required>
                    <input type="text" class="form-control" id="employee_name" disabled
                        value="{{ $penalty->employee->name_ar ?? 'N/A' }}">
                </div>
                <!-- Penalty Title Field -->
                <div class="form-group">
                    <label for="Penalty_hours">Hours</label>
                    <input type="numbwe" step="0.1" class="form-control" id="Penalty_hours" name="hours"
                        value="{{ old('name', $penalty->hours) }}" required>
                </div>
                <!-- Penalty Title Field -->
                <div class="form-group">
                    <label for="Penalty_type">Type</label>


                    <select class="form-control" id="penalty_type" name="type" required>
                        <option value="option1" {{ old('type', $penalty->type) == 'option1' ? 'selected' : '' }}>
                            option1</option>
                        <option value="option2" {{ old('type', $penalty->type) == 'option2' ? 'selected' : '' }}>
                            option2</option>
                        <option value="option3" {{ old('type', $penalty->type) == 'option3' ? 'selected' : '' }}>
                            option3</option>
                        <option value="option4" {{ old('type', $penalty->type) == 'option4' ? 'selected' : '' }}>
                            option4</option>
                    </select>
                </div>
                <!-- Penalty Title Field -->
                <div class="form-group">
                    <label for="Penalty_title">Penalty Date</label>
                    <input type="date" class="form-control" id="penalty_date" name="penalty_date"
                        value="{{ old('name', $penalty->penalty_date) }}" required>
                </div>
                <!-- Penalty Title Field -->
                <div class="form-group">
                    <label for="document">Document</label>
                    <input type="file" class="form-control" id="document" name="document"
                        value="{{ old('name', $penalty->document) }}">
                </div>

                <!-- Penalty Description Field -->
                <div class="form-group">
                    <label for="Penalty_description"> Description</label>
                    <textarea class="form-control" id="Penalty_description" name="description">{{ old('description', $penalty->description) }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Penalty</button>
                <a href="{{ route('penalties.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
