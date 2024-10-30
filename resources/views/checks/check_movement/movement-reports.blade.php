@extends('layouts.app')

@section('title', ' تقارير حركة الشيكات ')

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

                <div id="message-container">
                    <!-- This is where success or error messages will appear -->
                </div>
                <h1 class="mb-4 text-center">تقارير حركة الشيكات</h1>

                <div class="mb-4">
                    <form method="GET" action="{{ route('checks.report') }}">
                        <div class="row d-flex align-items-end">
                            <!-- Filters for check type and dates -->
                            <div class="col-md-2 mb-3">
                                <label for="check_type" class="form-label">نوع الشيك</label>
                                <select id="check_type" name="check_type" class="form-control">
                                    <option value="">جميع الحالات</option>
                                    <option value="issued" {{ request('check_type') === 'issued' ? 'selected' : '' }}>صادر
                                    </option>
                                    <option value="received" {{ request('check_type') === 'received' ? 'selected' : '' }}>وارد
                                    </option>
                                    <option value="under_collection"
                                        {{ request('check_type') === 'under_collection' ? 'selected' : '' }}>تحت التحصيل
                                    </option>
                                </select>
                            </div>
                            <!-- Date filters -->
                            <div class="col-md-2 mb-3">
                                <label for="issue_date_from" class="form-label">تاريخ التحرير من</label>
                                <div class='input-group date' id='datetimepicker1'>

                                    <input type="date" id="issue_date_from" name="issue_date_from" class="form-control"
                                        value="{{ request('issue_date_from') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="issue_date_to" class="form-label">تاريخ التحرير إلى</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="issue_date_to" name="issue_date_to" class="form-control"
                                        value="{{ request('issue_date_to') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="due_date_from" class="form-label">تاريخ الاستحقاق من</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="due_date_from" name="due_date_from" class="form-control"
                                        value="{{ request('due_date_from') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="due_date_to" class="form-label">تاريخ الاستحقاق إلى</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="due_date_to" name="due_date_to" class="form-control"
                                        value="{{ request('due_date_to') }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <!-- Submit button -->
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
                                <th>رقم الشيك</th>
                                <th>تاريخ الإصدار</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>القيمة</th>
                                <th>نوع الشيك</th>
                                <th>البنك</th>
                                <th>الصندوق</th>
                                <th>حساب</th>
                                <th>نوع الحركة</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checks as $check)
                                @foreach ($check->transactions as $transaction)
                                    <tr>
                                        <td>{{ $check->check_number }}</td>
                                        <td>{{ $check->issue_date->format('Y-m-d') }}</td>
                                        <td>{{ $check->due_date->format('Y-m-d') }}</td>
                                        <td>{{ $check->check_value }}</td>
                                        {{-- <td>{{ $check->check_type }}</td> --}}
                                        <td>{{ $check->check_type === 'issued' ? 'صادر' : ($check->check_type === 'received' ? 'وارد' : 'تحت التحصيل') }}

                                        <td>
                                            @if ($check->transactions->count())
                                                {{ $check->transactions->first()->bank }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($check->transactions->count())
                                                {{ $check->transactions->first()->cashbox }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($check->transactions->count())
                                                {{ $check->transactions->first()->account }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($check->transactions->count())
                                                {{ $check->transactions->first()->type === 'issue_check'
                                                    ? 'صرف الشيك البنكي'
                                                    : ($check->transactions->first()->type === 'receive_check'
                                                        ? 'استلام الشيك النقدي'
                                                        : ($check->transactions->first()->type === 'cancel_check'
                                                            ? 'إلغاء الشيك'
                                                            : ($check->transactions->first()->type === 'bank_receipt'
                                                                ? 'حافظة بنك'
                                                                : ($check->transactions->first()->type === 'endorse_check'
                                                                    ? 'تظهير'
                                                                    : 'غير محدد')))) }}
                                            @else
                                                -
                                            @endif
                                        </td>


                                        <td>
                                            <!-- Add a delete button -->

                                            <button class="btn btn-danger btn-sm delete-transaction"
                                                data-id="{{ $transaction->id }}">حذف الحركة</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد شيكات تطابق معايير البحث.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>

        @endcomponent
    </section>
@stop

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

            // Automatically hide success message after 5 seconds
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 4000);

            // Delete transaction using AJAX
            $('.delete-transaction').on('click', function() {
                var transactionId = $(this).data('id');
                var row = $(this).closest('tr');

                if (confirm('هل أنت متأكد أنك تريد حذف هذه الحركة من حركة الشيكات؟')) {
                    $.ajax({
                        url: '{{ route('checks.destroy', '') }}/' + transactionId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            row.remove();
                            $('#message-container').html(
                                '<div id="success-message" class="alert alert-success">تم حذف الحركة بنجاح.</div>'
                            );
                            setTimeout(function() {
                                $('#success-message').fadeOut('slow');
                            }, 4000);
                        },
                        error: function() {
                            $('#message-container').html(
                                '<div id="error-message" class="alert alert-danger">حدث خطأ أثناء محاولة حذف الحركة.</div>'
                            );
                            setTimeout(function() {
                                $('#error-message').fadeOut('slow');
                            }, 4000);
                        }
                    });
                }
            });
        });
    </script>
@stop
