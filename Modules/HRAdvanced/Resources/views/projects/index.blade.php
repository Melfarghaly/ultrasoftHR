@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Projects </h3>
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createProjectModal">إضافة
                مشروع</button>

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
                    <tr data-id="{{ $project->id }}">
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->code }}</td>
                        <td>{{ $project->description }}</td>
                        <td>

                            <a href="{{ route('hr_projects.edit', $project->id) }}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i>
                            </a>

                            <button type="button" class="btn btn-xs btn-danger" data-id={{ $project->id }}>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <!-- Create Job Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">Create New Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createProjectForm">
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
<!-- SweetAlert CSS and JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('javascript')
    <!-- AJAX Script to Submit the Form -->
    <script>
        $('#createProjectForm').on('submit', function(event) {
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
        //sweet delete project 
        $('.btn-danger').click(function() {
            const recordId = $(this).data('id');

            Swal.fire({
                title: 'هل انت متاكد؟',
                text: "ستقوم يحذف المشروع بشكل دائم",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '!إحذف'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request using jQuery
                    $.ajax({
                        url: `/hradvanced/projects/${recordId}/destroy`,

                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your record has been deleted.',
                                    'success'
                                );
                                // Optionally, remove the deleted item from the DOM
                                $(`tr[data-id=${recordId}]`).remove();

                            } else {
                                Swal.fire(
                                    'خطا!',
                                    'هناك مكشلة مع حذف المشروع .',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'خطا!',
                                'حدث خطأ ما',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
@endsection
