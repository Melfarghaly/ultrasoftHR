@extends('layouts.app')
@section('title', 'تقارير الشيكات')
@section('content')
    <style>
        /* CSS to ensure buttons stay in a single line */
        .btn-group {
            display: flex;
            flex-wrap: nowrap;
            gap: 5px;
            /* Adjust the gap between buttons if needed */
        }

        .btn-group .btn {
            margin: 0;
            /* Remove default margins if needed */
        }
    </style>
    
    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])
            <div class="container mt-5">

                @if (session('success'))
                    <div id="success-message" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h1 class="mb-4 text-center">تقارير الشيكات</h1>

                <div class="mb-4">
                    <form method="GET" action="{{ route('checks.index') }}">
                        <div class="row d-flex align-items-end">
                            <!-- Filter Form -->
                            <div class="col-md-2 mb-3">
                                <label for="check_type" class="form-label">نوع الشيك</label>
                                <select id="check_type" name="check_type" class="form-control">
                                    <option value="">جميع الحالات</option>
                                    <option value="issued" {{ $checkType === 'issued' ? 'selected' : '' }}>صادر</option>
                                    <option value="received" {{ $checkType === 'received' ? 'selected' : '' }}>وارد</option>
                                    <option value="under_collection" {{ $checkType === 'under_collection' ? 'selected' : '' }}>
                                        تحت التحصيل</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="issue_date_from" class="form-label">تاريخ التحرير من</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="issue_date_from" name="issue_date_from" class="form-control"
                                        value="{{ $issueDateFrom }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="issue_date_to" class="form-label">تاريخ التحرير إلى</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="issue_date_to" name="issue_date_to" class="form-control"
                                        value="{{ $issueDateTo }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">

                                <label for="due_date_from" class="form-label">تاريخ الاستحقاق من</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="due_date_from" name="due_date_from" class="form-control"
                                        value="{{ $dueDateFrom }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="due_date_to" class="form-label">تاريخ الاستحقاق إلى</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="due_date_to" name="due_date_to" class="form-control"
                                        value="{{ $dueDateTo }}">
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
                                <th>نوع الشيك</th>
                                <th>رقم الشيك</th>
                                <th>اسم البنك</th>
                                <th>اسم الحساب</th>
                                <th>مركز التكلفة</th>
                                <th>تاريخ التحرير</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>قيمة الشيك</th>
                                <th>العملة</th>
                                <th>ملاحظات</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checks as $check)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $check->check_type === 'issued' ? 'صادر' : ($check->check_type === 'received' ? 'وارد' : 'تحت التحصيل') }}
                                    </td>
                                    <td>{{ $check->check_number }}</td>
                                    <td>{{ $check->bank }}</td>
                                    <td>{{ $check->account_name }}</td>
                                    <td>{{ $check->cost_center }}</td>
                                    <td>{{ $check->issue_date->format('Y-m-d') }}</td>
                                    <td>{{ $check->due_date->format('Y-m-d') }}</td>
                                    <td>{{ $check->check_value }}</td>
                                    <td>{{ $check->currency }}</td>
                                    <td>{{ $check->notes }}</td>
                                    <td>
                                        @if ($check->transactions->isEmpty())
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('checks.edit', $check->id) }}"
                                                    class="btn btn-primary btn-sm">تعديل</a>
                                                    {!! Form::open(['route' => ['checks.checks.destroy', $check->id], 'method' => 'delete']) !!}
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الشيك؟')">حذف</button>
                                                {!! Form::close() !!}
                                                
                                                <a href="{{ route('checks.print', $check->id) }}"
                                                    class="btn btn-info btn-sm">طباعة</a>
                                            </div>
                                        @else
                                            <a href="{{ route('checks.print', $check->id) }}"
                                                class="btn btn-info btn-sm">طباعة</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">لا توجد بيانات لعرضها</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        @endcomponent
    </section>
@stop


@section('javascript')
    <script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
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
                    var date = parseDate($(this).val());

                    if (!isValidDate(date)) {
                        date = moment().format('YYYY-MM-DD');
                    }

                    $(this).val(date);
                });
            }

            var isValidDate = function(value, format) {
                format = format || false;
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

            // Automatically hide success message after 5 seconds and reset scroll
            setTimeout(function() {
                $('#success-message').fadeOut('slow', function() {
                    // Reset scroll position of the table
                    $('.table-responsive').scrollTop(0);
                });
            }, 4000);
        });
    </script>
@endsection
