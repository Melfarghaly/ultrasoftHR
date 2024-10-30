@extends('layouts.app')

@section('content')
@include('hradvanced::layouts.nav')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Employees List</h3>
        <a href="/hradvanced/employee/create" class="btn btn-primary pull-right">اضافة</a>
    </div>
    <div class="box-body">
        <table class="table table-bordered" id="employees-table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>رقم الموظف</th>
                    <th>الاسم (بالعربية)</th>
                    <th>الاسم (بالإنجليزية)</th>
                    <th>رقم الهوية الوطنية</th>
                    <th>الجنسية</th>
                    <th>المهنة</th>
                    <th>الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>الإجراءات</th>
                </tr>

            </thead>
        </table>
    </div>
</div>
@endsection

@section('javascript')
<script>
$(function() {
    $('#employees-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('employees.index') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'employee_number', name: 'employee_number' },
            { data: 'name_ar', name: 'name_ar' },
            { data: 'name_en', name: 'name_en' },
            { data: 'national_id', name: 'national_id' },
            { data: 'nationality', name: 'nationality' },
            { data: 'occupation', name: 'occupation' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
