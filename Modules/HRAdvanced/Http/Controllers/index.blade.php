@extends('layouts.app')

@section('content')
    @include('hradvanced::layouts.nav')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">penalties </h3>
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#createPenaltyModal">Enter
                Penalty</button>

        </div>
        <div class="box-body">

            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Hours</th>
                    <th>Penalty Date</th>
                    <th>Document</th>
                    <th>Description</th>
                </tr>
                @foreach ($penalties as $penalty)
                    <tr data-id="{{ $penalty->id }}">
                        <td>{{ $penalty->id }}</td>
                        <td>{{ $penalty->employee_id }}</td>
                        <td>{{ $penalty->type }}</td>
                        <td>{{ $penalty->hours }}</td>
                        <td>{{ $penalty->document }}</td>
                        <td>{{ $penalty->penalty_date }}</td>
                        <td>{{ $penalty->description }}</td>
                        <td>

                            <a href="{{ route('penalties.edit', $penalty->id) }}" class="btn btn-xs btn-primary">
                                <i class="fa fa-edit"></i>
                            </a>

                            <button type="button" class="btn btn-xs btn-danger" data-id={{ $penalty->id }}>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <!-- Create Job Modal -->
    <div class="modal fade" id="createPenaltyModal" tabindex="-1" role="dialog" aria-labelledby="createPenaltyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPenaltyModalLabel">Create New Penalty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createPenaltyForm">
                    <div class="modal-body">


                        <div class="form-group">
                            <label for="employee_code">Employee</label>
                            <input type="text" class="form-control" id="employee_code" name="employee_id" required>
                        </div>
                        <div class="form-group">
                            <label for="penalty_type">Penalty Type</label>

                            <select class="form-control" id="penalty_type" name="type" required>
                                <option value="option1">option1</option>
                                <option value="option1">option1</option>
                                <option value="option1">option1</option>
                                <option value="option1">option1</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="penalty_hour">Penalty Hours</label>
                            <input type="number" class="form-control" id="penalty_hour" name="hours" step ="0.1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="document">Upload Document</label>
                            <input type="file" class="form-control" id="document" name="document">
                        </div>
                        <div class="form-group">
                            <label for="penalty_date">Penalty Date</label>
                            <input type="date" class="form-control" id="penalty_date" name="penalty_date">
                        </div>
                        <div class="form-group">
                            <label for="description">Penalty Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Penalty </button>
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
        $('#createPenaltyForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('penalties.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#createPenaltyModal').modal('hide');
                    location.reload(); // Reload the page to reflect the new job in the list
                },
                error: function(xhr) {
                    alert('An error occurred while creating the penality.');
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
