@extends('layouts.app')

@section('title', 'عرض السندات')

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
                <h1 class="mb-4 text-center">عرض السندات</h1>

                <div class="mb-4">
                    <form method="GET" action="{{ route('vouchers.index') }}">
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
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="date_from" name="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="date_to" class="form-label">إلى تاريخ</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="date_to" name="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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

                {{-- @if ($vouchers->isEmpty())
                    <div class="alert alert-info text-center">
                        لا توجد سندات لعرضها.
                    </div>
                @else --}}
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
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($vouchers->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد سندات لعرضها.</td>
                                </tr>
                            @else
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $voucher->voucher_number }}</td>
                                        <td>{{ $voucher->voucher_type === 'cash_disbursement' ? 'سند صرف' : 'سند استلام' }}</td>
                                        <td>{{ $voucher->voucher_date ? $voucher->voucher_date->format('Y-m-d') : 'غير محدد' }}
                                        </td>
                                        <td>{{ $voucher->currency }}</td>
                                        <td>{{ $voucher->cash_drawer }}</td>
                                        <td>{{ $voucher->account_name }}</td>
                                        <td>{{ $voucher->amount ? number_format($voucher->amount, 2) : '0.00' }}</td>
                                        <td>{{ $voucher->notes }}</td>
                                        <td>
                                            <a href="{{ route('vouchers.edit', $voucher->id) }}"
                                                class="btn btn-warning btn-sm" target="_blank">تعديل</a>
                                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا السند؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- @endif --}}
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
