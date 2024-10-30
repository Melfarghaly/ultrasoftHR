@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Projects </h3>
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createJobModal">Create New
                Prloject</button>

        </div>
        <div class="box-body">

            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->code }}</td>
                        <td>{{ $project->description }}</td>
                        <td>

                            <a href="{{ route('hr_projects.edit', $project->id) }}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('hr_projects.destroy', $project->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger">
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
    <div class="modal fade" id="createprojectModal" tabindex="-1" role="dialog" aria-labelledby="createprojectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createprojectModalLabel">Create New Project</h5>
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
                    $('#createProjectModal').modal('hide');
                    location.reload(); // Reload the page to reflect the new job in the list
                },
                error: function(xhr) {
                    alert('An error occurred while creating the project.');
                }
            });
        });
        // AJAX Script to Delete a Project
        $(document).on('click', '.btn-danger', function() {
            var id = $(this).closest('tr').data('id');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('hr_projects.destroy', $project->id) }}",
                method: "DELETE",
                data: {
                    _token: csrfToken,
                    id: id
                },
                success: function(response) {
                    location.reload(); // Reload the page to reflect the deleted job in the list
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the project.');
                }
            });
        })
        //sweet delete project
        $(document).on('click', '.btn-warning', function() {
            var id = $(this).closest('tr').data('id');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('hr_projects.destroy', $project->id) }}",
                        method: "DELETE",
                        data: {
                            _token: csrfToken,
                            id: id
                        },
                        success: function(response) {
                            Swal.fire(
                                '
                                Deleted!',
                                'Your project has been deleted.',
                                'success'


                            ).then((result) => {
                                if (result.isConfirmed) {
                                    location
                                        .reload(); // Reload the page to reflect the deleted job in the list
                                }
                            });



                        }
                        error: function(xhr) {
                            Swal.fire(
                                '
                                Error!',
                                'An error occurred while deleting the project.',
                                'error'


                            ).then((result) => {
                                if (result.isConfirmed) {
                                    location
                                        .reload(); // Reload the page to reflect the deleted job in the list
                                }
                            });
                        }
                    })
                }
            })
        })
    </script>
@endsection
