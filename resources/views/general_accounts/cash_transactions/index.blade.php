@extends('layouts.app')
@section('title', 'تقارير المعاملات النقدية')

@section('content')
    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])
            <div class="container mt-5">
                @if (session('success'))
                    <div id="success-message" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h1 class="mb-4 text-center">تقارير المعاملات النقدية</h1>

                <div class="mb-4">
                    <form method="GET" action="{{ route('cash_transactions.index') }}">
                        <div class="row d-flex align-items-end">
                            <!-- Filter Form -->
                            <div class="col-md-2 mb-3">
                                <label for="transaction_type" class="form-label">نوع المعاملة</label>
                                <select id="transaction_type" name="transaction_type" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    <option value="deposit" {{ request('transaction_type') === 'deposit' ? 'selected' : '' }}>
                                        إيداع
                                    </option>
                                    <option value="withdrawal"
                                        {{ request('transaction_type') === 'withdrawal' ? 'selected' : '' }}>
                                        سحب
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="document_date_from" class="form-label">تاريخ الوثيقة من</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="document_date_from" name="document_date_from" class="form-control"
                                        value="{{ request('document_date_from') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="document_date_to" class="form-label">تاريخ الوثيقة إلى</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="document_date_to" name="document_date_to" class="form-control"
                                        value="{{ request('document_date_to') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-6 d-flex justify-content-center" style="padding: 24px;">
                                <button type="submit" class="btn btn-primary btn-custom">عرض</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>نوع المعاملة</th>
                                {{-- <th>رقم الوثيقة</th> --}}
                                <th>تاريخ الوثيقة</th>
                                <th>العملة</th>
                                <th>اسم البنك</th>
                                <th>اسم الحساب</th>
                                <th>المبلغ</th>
                                <th>الملاحظات</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @php
                                            $transactionType = $transaction->transaction_type;
                                            $transactionTypeInArabic = '';

                                            if ($transactionType === 'deposit') {
                                                $transactionTypeInArabic = 'إيداع';
                                            } elseif ($transactionType === 'withdrawal') {
                                                $transactionTypeInArabic = 'سحب';
                                            }
                                        @endphp

                                        {{ $transactionTypeInArabic }}
                                    </td>
                                    {{-- <td>
                                    @php
                                        $parts = explode('-', $transaction->document_number);
                                        $numberOnly = end($parts);
                                    @endphp
                                    {{ $numberOnly }}
                                </td> --}}
                                    <td>{{ $transaction->document_date }}</td>
                                    <td>{{ $transaction->currency }}</td>
                                    <td>{{ $transaction->bank_name }}</td>
                                    <td>{{ $transaction->account_name }}</td>
                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->notes }}</td>
                                    <td>
                                        <a href="{{ route('cash_transactions.edit', $transaction->id) }}"
                                            class="btn btn-primary btn-sm" target="_blank">تعديل</a>

                                        {!! Form::open([
                                            'route' => ['cash_transactions.destroy', $transaction->id],
                                            'method' => 'delete',
                                            'style' => 'display:inline;',
                                        ]) !!}
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('هل أنت متأكد من أنك تريد حذف هذه المعاملة؟')">حذف</button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">لا توجد بيانات لعرضها</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endcomponent
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
      $(function() {
            var bindDatePicker = function() {
                $(".date").datetimepicker({
                    format: 'YYYY-MM-DD',
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                }).find('input:first').on("blur", function() {
                    // check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
                    // update the format if it's yyyy-mm-dd
                    var date = parseDate($(this).val());

                    if (!isValidDate(date)) {
                        //create date based on momentjs (we have that)
                        date = moment().format('YYYY-MM-DD');
                    }

                    $(this).val(date);
                });
            }

            var isValidDate = function(value, format) {
                format = format || false;
                // lets parse the date to the best of our knowledge
                if (format) {
                    value = parseDate(value);
                }

                var timestamp = Date.parse(value);

                return isNaN(timestamp) == false;
            }

            var parseDate = function(value) {
                var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
                if (m)
                    value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

                return value;
            }

            bindDatePicker();
            setTimeout(function() {
                $('#success-message').fadeOut('slow', function() {
                    // Reset scroll position of the table
                    $('.table-responsive').scrollTop(0);
                });
            }, 4000);
        });
    </script>
@endsection
