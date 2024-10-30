@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Jobs </h3>
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createJobModal">Create New
                Job</button>

        </div>

    </div>

    <!-- Create Job Modal -->
    <div class="modal fade" id="createJobModal" tabindex="-1" role="dialog" aria-labelledby="createJobModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createJobModalLabel">Create New Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createprojectForm">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="project_title">Project Name</label>
                            <input type="text" class="form-control" id="project_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="project_title">Project Code</label>
                            <input type="text" class="form-control" id="project_code" name="code" required>
                        </div>
                        <div class="form-group">
                            <label for="project_description">Project Description</label>
                            <textarea class="form-control" id="project_description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Project </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <!-- AJAX Script to Submit the Form -->
    <script>
        $('#createprojectForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('hr_projects.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#createJobModal').modal('hide');
                    location.reload(); // Reload the page to reflect the new job in the list
                },
                error: function(xhr) {
                    alert('An error occurred while creating the project.');
                }
            });
        });
    </script>
@endsection
