@extends('layouts.app')

@section('content')
@include('hradvanced::layouts.nav')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $employee->name_ar }}</h3>
    </div>
    <div class="box-body">
        <form action="{{ route('employee.updateSalaryItems', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table">
                <thead>
                    <tr>
                        <th>كود البند</th>
                        <th>اسم البند </th>
                        <th>المبلغ</th>
                        <th>يبدأ في</th>
                        <th>الحالة</th>
                       
                    </tr>
                </thead>
                <tbody>
                @foreach($employee->salaryItems as $salary)
                <tr>
                    <td>{{ $salary->item->item_code }}</td>
                    <td>{{ $salary->item->item_name }}</td>
                    <td>
                        <input type="number" name="salary_items[{{ $salary->id }}][amount]" value="{{ $salary->amount }}" class="form-control" />
                    </td>
                    <td>{{ $salary->starts_at }}</td>
                    <td>
                        <select name="salary_items[{{ $salary->id }}][status]" class="form-control">
                            <option value="open" {{ $salary->status == 'open' ? 'selected' : '' }}>مفتوح</option>
                            <option value="ready" {{ $salary->status == 'ready' ? 'selected' : '' }}>جاهز </option>
                            <option value="certified" {{ $salary->status == 'certified' ? 'selected' : '' }}>معتمد </option>
                        </select>
                    </td>
                   
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection