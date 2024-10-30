@extends('layouts.app')

@section('content')
@include('hradvanced::layouts.nav')

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Jobs </h3>
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createJobModal">Create New Job</button>

    </div>
    <div class="box-body">
        
        <table  class="table table-bordered" >
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        @foreach($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>{{ $job->job_title }}</td>
                <td>{{ $job->job_description }}</td>
                <td>
                   
                    <a href="{{ route('hr_jobs.edit', $job->id) }}" class="btn btn-xs btn-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('hr_jobs.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"  class="btn btn-xs btn-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    </div>
</div>

 <!-- Create Job Modal -->
 <div class="modal fade" id="createJobModal" tabindex="-1" role="dialog" aria-labelledby="createJobModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createJobModalLabel">Create New Job</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createJobForm">
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label for="job_title">Job Title</label>
                            <input type="text" class="form-control" id="job_title" name="job_title" required>
                        </div>
                        <div class="form-group">
                            <label for="job_description">Job Description</label>
                            <textarea class="form-control" id="job_description" name="job_description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
 
@section('javascript')
 <!-- AJAX Script to Submit the Form -->
 <script>
        $('#createJobForm').on('submit', function(event) {
            event.preventDefault();
            
            $.ajax({
                url: "{{ route('hr_jobs.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#createJobModal').modal('hide');
                    location.reload(); // Reload the page to reflect the new job in the list
                },
                error: function(xhr) {
                    alert('An error occurred while creating the job.');
                }
            });
        });
    </script>
@endsection