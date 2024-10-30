@extends('layouts.app')
@section('title', 'تقرير الطباعة')

@section('content')
    <section class="content">

        @component('components.widget', ['class' => 'box-solid'])
            <div class="container my-5">
                <div class="text-center mb-4 border border-dark rounded p-3 shadow-lg bg-light">
                    <h2>اجمالي الإيداعات و المسحوبات</h2>
                </div>
                

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>تاريخ الإيداع</th>
                                <th>ملاحظات الإيداع</th>
                                <th>قيمة الإيداع</th>
                                <th>تاريخ السحب</th>
                                <th>ملاحظات السحب</th>
                                <th>قيمة السحب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $runningBalance = $balanceStartPeriod;
                            @endphp
                            @foreach ($transactions as $transaction)
                                @php
                                    $isDeposit = $transaction->transaction_type === 'deposit';
                                    $isWithdrawal = $transaction->transaction_type === 'withdrawal';
                                @endphp
                                <tr>
                                    <td>{{ $isDeposit ? $transaction->document_date : '-' }}</td>
                                    <td>{{ $isDeposit ? $transaction->notes : '-' }}</td>
                                    <td>{{ $isDeposit ? number_format($transaction->amount, 2) : '0.00' }}</td>
                                    <td>{{ $isWithdrawal ? $transaction->document_date : '-' }}</td>
                                    <td>{{ $isWithdrawal ? $transaction->notes : '-' }}</td>
                                    <td>{{ $isWithdrawal ? number_format($transaction->amount, 2) : '0.00' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-footer">
                                <td colspan="2"><strong>إجمالي الإيداعات:</strong></td>
                                <td>{{ number_format($totalDeposits, 2) }}</td>
                                <td colspan="2"><strong>إجمالي السحوبات:</strong></td>
                                <td>{{ number_format($totalWithdrawals, 2) }}</td>
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


