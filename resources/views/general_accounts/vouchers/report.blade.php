@extends('layouts.app')

@section('title', 'كشف حركة الخزينة')

@section('content')
    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])

            <div class="container mt-5">
                <h1 class="mb-4 text-center">كشف حركة الخزينة</h1>

                <div class="mb-4">
                    <form method="GET" action="{{ route('vouchers.report') }}">
                        <div class="row d-flex align-items-end">
                            <!-- Filter Form -->
                            <div class="col-md-3 mb-3">
                                <label for="voucher_type" class="form-label">نوع السند</label>
                                <select id="voucher_type" name="voucher_type" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    <option value="cash_disbursement"
                                        {{ request('voucher_type') === 'cash_disbursement' ? 'selected' : '' }}>سند صرف</option>
                                    <option value="cash_receipt"
                                        {{ request('voucher_type') === 'cash_receipt' ? 'selected' : '' }}>سند استلام</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="date_from" class="form-label">من تاريخ</label>
                                <input type="date" id="date_from" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <input type="date" id="date_to" name="date_to" class="form-control"
                                    value="{{ request('date_to') }}">
                            </div>

                            <div class="col-md-2 mb-6 d-flex justify-content-center" style="padding: 24px;">
                                <button type="submit" class="btn btn-primary btn-custom">عرض</button>
                            </div>
                        </div>
                    </form>
                </div>

                   <!-- Print Button -->
                   <div class="text-center mt-4">
                    <a href="{{ route('vouchers.printReport') }}" class="btn btn-success" target="_blank">
                        طباعة التقرير
                    </a>
                </div>
                
                
                

                @if ($vouchers->isEmpty())
                    <div class="alert alert-info text-center">
                        لا توجد سندات لعرضها.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>رقم السند</th>
                                    <th>نوع السند</th>
                                    <th>تاريخ السند</th>
                                    <th>العملة</th>
                                    <th>الخزنة</th>
                                    <th>اسم الحساب</th>
                                    <th>القيمة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $voucher->voucher_number }}</td>
                                        <td>{{ $voucher->voucher_type === 'cash_disbursement' ? 'سند صرف' : 'سند استلام' }}</td>
                                        <td>{{ $voucher->voucher_date ? $voucher->voucher_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $voucher->currency }}</td>
                                        <td>{{ $voucher->cash_drawer }}</td>
                                        <td>{{ $voucher->account_name }}</td>
                                        <td>{{ $voucher->amount ? number_format($voucher->amount, 2) : '0.00' }}</td>
                                        <td>{{ $voucher->notes }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endcomponent
    </section>
@endsection