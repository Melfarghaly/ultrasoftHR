@extends('layouts.app')
@section('title', 'تقرير الطباعة')

@section('content')
    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])
            <div class="container my-5">
                <div class="text-center mb-4 border border-dark rounded p-3 shadow-lg bg-light">
                    <h2>اجمالي سندات الصرف والاستلام</h2>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>تاريخ الصرف</th>
                                <th>ملاحظات الصرف</th>
                                <th>قيمة الصرف</th>
                                <th>تاريخ الاستلام</th>
                                <th>ملاحظات الاستلام</th>
                                <th>قيمة الاستلام</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalDisbursements = 0; // إجمالي الصرف
                                $totalReceipts = 0; // إجمالي الاستلام
                            @endphp
                            @foreach ($vouchers as $voucher)
                                @php
                                    $isDisbursement = $voucher->voucher_type === 'cash_disbursement';
                                    $isReceipt = $voucher->voucher_type === 'cash_receipt';
                                    if ($isDisbursement) {
                                        $totalDisbursements += $voucher->amount;
                                    } else if ($isReceipt) {
                                        $totalReceipts += $voucher->amount;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $isDisbursement ? $voucher->voucher_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $isDisbursement ? $voucher->notes : '-' }}</td>
                                    <td>{{ $isDisbursement ? number_format($voucher->amount, 2) : '0.00' }}</td>
                                    <td>{{ $isReceipt ? $voucher->voucher_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $isReceipt ? $voucher->notes : '-' }}</td>
                                    <td>{{ $isReceipt ? number_format($voucher->amount, 2) : '0.00' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-footer">
                                <td colspan="2"><strong>إجمالي سندات الصرف:</strong></td>
                                <td>{{ number_format($totalDisbursements, 2) }}</td>
                                <td colspan="2"><strong>إجمالي سندات الاستلام:</strong></td>
                                <td>{{ number_format($totalReceipts, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center">
                    <p class="h4"><strong>الرصيد في بداية الفترة:</strong> {{ number_format($balanceStartPeriod, 2) }}</p>
                    <p class="h4"><strong>الرصيد في نهاية الفترة:</strong> {{ number_format($balanceEndPeriod, 2) }}</p>
                </div>
            </div>
        @endcomponent
    </section>
@endsection
