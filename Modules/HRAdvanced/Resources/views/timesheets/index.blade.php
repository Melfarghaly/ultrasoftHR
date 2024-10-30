@extends('layouts.app')

@section('content')
@include('hradvanced::layouts.nav')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

        <div class="col-xs-12 pos-tab-container">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pos-tab-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item text-center active">يومي </a>
                    <a href="#" class="list-group-item text-center">اسبوعي</a>
                    <a href="#" class="list-group-item text-center">شهري</a>
                    <a href="#" class="list-group-item text-center">مسير الرواتب</a>
                </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                @include('hradvanced::timesheets.partials.daily')
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                @include('hradvanced::timesheets.partials.weekly')
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                @include('hradvanced::timesheets.partials.monthly')
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pos-tab">
                @include('hradvanced::timesheets.partials.payroll')
            </div>
        </div>
        
        </div>
    </div>

</section>
@endsection

@section('javascript')
<script>
$(document).ready(function() {
             // Check national ID input dynamically
   //Date range as a button
   $('#daily_filter_date_range').daterangepicker(
        dateRangeSettings,
        function(start, end) {
            $('#daily_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(
                moment_date_format));
                daily_timesheet.ajax.reload();
        }
    );
    $('#daily_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#daily_filter_date_range').val('');
        daily_timesheet.ajax.reload();
    });
  //  $(function() {
    daily_timesheet = $('#daily_timesheet').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('timesheets.daily') }}',
            data: function(d) {
                var dateRange = $('#daily_filter_date_range').val();
                if (dateRange) {
                    var start = $('#daily_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#daily_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
                d.employee_id = $('#daily_employee_id').val();
            }
        },
        columns: [
            { data: 'daily_delete', name: 'daily_delete', orderable: false, searchable: false },
            { data: 'date', name: 'date' },
            { data: 'name', name: 'name' },
            { data: 'timesheet_status', name: 'timesheet_status' },
            { data: 'overtime', name: 'overtime' },
            { data: 'send', name: 'send' },
            { data: 'approved', name: 'approved' },
            { data: 'posted', name: 'posted' },
            { data: 'after_posted', name: 'after_posted' },
            { data: 'status', name: 'status' }
        ],
        //rowId: 'id' // Ensure your server response contains an 'id' field for each record
    //});
});
$(document).on('change','#daily_employee_id',function(){
    daily_timesheet.ajax.reload();
})
    $('#week, #month').on('change', function() {
        var selectedWeek = parseInt($('#week').val());
        var selectedMonth = $('#month').val();

        // Check if both month and week are selected
        if (!selectedWeek || !selectedMonth) {
            $('#start_date').val('');
            $('#end_date').val('');
            return;
        }

        // Split the selected month into year and month
        var parts = selectedMonth.split('-');
        var year = parseInt(parts[0]);
        var month = parseInt(parts[1]);

        // Calculate the first day of the month using moment.js
        var firstDayOfMonth = moment([year, month - 1, 1]);

        // Find the day of the week of the first day of the month
        var firstDayOfWeek = firstDayOfMonth.day();

        // Adjust the first day to the previous Saturday if the first day isn't a Saturday
        var adjustmentDays = firstDayOfWeek === 5 ? -6 : -(firstDayOfWeek + 1); // Adjust for first Saturday
        var firstSaturday = firstDayOfMonth.add(adjustmentDays, 'days');

        // Variables to hold the start and end dates
        var startDate, endDate;

        // Calculate the start and end dates based on the selected week
        switch (selectedWeek) {
            case 1:
                startDate = moment(firstSaturday); // First Saturday
                endDate = moment(firstSaturday).add(6, 'days'); // First Friday
                break;
            case 2:
                startDate = moment(firstSaturday).add(7, 'days'); // Second Saturday
                endDate = moment(startDate).add(6, 'days'); // Second Friday
                break;
            case 3:
                startDate = moment(firstSaturday).add(14, 'days'); // Third Saturday
                endDate = moment(startDate).add(6, 'days'); // Third Friday
                break;
            case 4:
                startDate = moment(firstSaturday).add(21, 'days'); // Fourth Saturday
                endDate = moment(startDate).add(6, 'days'); // Fourth Friday
                break;
            case 5:
                startDate = moment(firstSaturday).add(28, 'days'); // Fifth Saturday
                endDate = moment(startDate).add(6, 'days'); // Fifth Friday
                break;
            case 6:
                startDate = moment(firstSaturday).add(35, 'days'); // Sixth Saturday
                var lastDayOfMonth = moment([year, month, 0]); // Last day of the month
                endDate = lastDayOfMonth; // End date is the last day of the month
                break;
        }

        // Format dates as YYYY-MM-DD
        var startFormatted = startDate.format('YYYY-MM-DD');
        var endFormatted = endDate.format('YYYY-MM-DD');

        // Set the start and end date inputs
        $('#start_date').val(startFormatted);
        $('#end_date').val(endFormatted);
    });
});
$(function() {
    $('#weekly_timesheet').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('timesheets.weekly') }}',
        columns: [
            { data: 'mass_delete', name: 'mass_delete', orderable: false, searchable: false },
            { data: 'employee_number', name: 'employee_number' },
            { data: 'name_ar', name: 'name_ar' },
          
         
            { data: 'nationality', name: 'nationality' },
            { data: 'job_title', name: 'job_title' },
            { data: 'project_id', name: 'project_id' }
            
           
        ]
    });
    
    
   
});

$(document).on('click', '#mass_attend', function(e) {
    e.preventDefault();
    var selected_rows = getSelectedRows();

    if (selected_rows.length > 0) {
        $('input#selected_rows').val(selected_rows);
        swal({
            title: 'mark attend  for all  selected',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('timesheets.massAttend') }}',
                    data: {
                            'selected_rows': selected_rows,
                            '_token': $('input[name="_token"]').val(),
                            'start_date':$('#start_date').val(),
                            'end_date':$('#end_date').val(),
                        },
                        success: function(data) {
                            swal("Good job!", "you have marked attend for all selected", "success");
                            $('#weekly_timesheet').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            swal("Error!", "something went wrong", "error");
                        }
                });

            }
        });
    } else {
        $('input#selected_rows').val('');
        swal('@lang('lang_v1.no_row_selected')');
    }
});

$(document).on('click', '#mass_approved', function(e) {
    e.preventDefault();
    var selected_rows = getSelectedRows();

    if (selected_rows.length > 0) {
        $('input#selected_rows').val(selected_rows);
        swal({
            title: 'mark approved  for all  selected',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('timesheets.massApproved') }}',
                    data: {
                            'selected_rows': selected_rows,
                            '_token': $('input[name="_token"]').val(),
                           
                        },
                        success: function(data) {
                            swal("Good job!", "you have marked approved for all selected", "success");
                            daily_timesheet.DataTable().ajax.reload();
                        },
                        error: function(data) {
                            swal("Error!", "something went wrong", "error");
                        }
                });

            }
        });
    } else {
        $('input#selected_rows').val('');
        swal('@lang('lang_v1.no_row_selected')');
    }
});
$(document).on('click', '#mass_posted', function(e) {
    e.preventDefault();
    var selected_rows = getSelectedRows();

    if (selected_rows.length > 0) {
        $('input#selected_rows').val(selected_rows);
        swal({
            title: 'mark posted  for all  selected',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('timesheets.massPosted') }}',
                    data: {
                            'selected_rows': selected_rows,
                            '_token': $('input[name="_token"]').val(),
                          
                        },
                        success: function(data) {
                            swal("Good job!", "you have marked posted for all selected", "success");
                            daily_timesheet.DataTable().ajax.reload();
                        },
                        error: function(data) {
                            swal("Error!", "something went wrong", "error");
                        }
                });

            }
        });
    } else {
        $('input#selected_rows').val('');
        swal('@lang('lang_v1.no_row_selected')');
    }
});


//montly report 
$(function() {
    let monthly_timesheet = $('#monthly_timesheet').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("timesheets.monthly") }}',
            data: function (d) {
                d.month = $('#month_monthly').val();
            }
        },
        columns: [
            { data: 'name_ar', name: 'name_ar' },
            { data: 'nationality', name: 'nationality' },
            { data: 'job_title', name: 'job_title' },
            { data: 'work_days', name: 'work_days', searchable: false },
            { data: 'total_overtime', name: 'total_overtime', searchable: false },
            { data: 'total_hours', name: 'total_hours', searchable: false },
            { data: 'total_hours', name: 'total_hours', searchable: false },
            { data: 'penalties', name: 'penalties', searchable: false },
            { data: 'absence_days', name: 'absence_days', searchable: false },
            { data: 'deduction_hours', name: 'deduction_hours', searchable: false },
            { data: 'notes', name: 'notes', orderable: false }
        ]
    });

    // Event listener for month selection
    $('#month_monthly').change(function() {
        monthly_timesheet.ajax.reload();
    });
   
});
</script>
@endsection
