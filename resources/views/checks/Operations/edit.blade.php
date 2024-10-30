@extends('layouts.app')
@section('title', __('stock_adjustment.edit'))

@section('content')
    <style>
        .invalid-feedback {
            color: red;
        }
    </style>
    <!-- Main content -->
    <section class="content no-print">
        {!! Form::model($check, [
            'route' => ['checks.update', $check->id],
            'method' => 'put',
            'id' => 'edit_check_form',
        ]) !!}

        @component('components.widget', ['class' => 'box-solid'])
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="container mt-5">
                        <!-- Page header -->
                        <h1 class="mb-4 text-center">تعديل شيك</h1>

                        @if (session('success'))
                            <div id="success-message" class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif


                        <!-- Transaction and Cost Center -->
                        <div class="row mb-3">
                            <!-- Check Number -->
                            <div class="col-md-6 mb-2">
                                <label for="check_number" class="form-label">رقم الشيك</label>
                                <input type="text" id="check_number" name="check_number"
                                    class="form-control @error('check_number') is-invalid @enderror" placeholder="رقم الشيك"
                                    value="{{ old('check_number', $check->check_number) }}">
                                @error('check_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Bank Name -->
                            <div class="col-md-6 mb-2">
                                <label for="bank" class="form-label">البنك</label>
                                <input type="text" id="bank" name="bank"
                                    class="form-control @error('bank') is-invalid @enderror" placeholder="اسم البنك"
                                    value="{{ old('bank', $check->bank) }}">
                                @error('bank')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Issue Date and Due Date -->
                        <div class="row mb-3">
                            <!-- Issue Date -->
                            <div class="col-md-6 mb-2">
                                <label for="issue_date" class="form-label">تاريخ التحرير</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="issue_date" name="issue_date"
                                        class="form-control @error('issue_date') is-invalid @enderror"
                                        value="{{ old('issue_date', $check->issue_date->format('Y-m-d')) }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                                @error('issue_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6 mb-2">
                                <label for="due_date" class="form-label">تاريخ الاستحقاق</label>
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type="date" id="due_date" name="due_date"
                                        class="form-control @error('due_date') is-invalid @enderror"
                                        value="{{ old('due_date', $check->due_date->format('Y-m-d')) }}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                                @error('due_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Check Value and Currency -->
                        <div class="row mb-6">
                            <!-- Check Value -->
                            <div class="col-md-6 mb-2">
                                <label for="check_value" class="form-label">قيمة الشيك</label>
                                <input type="number" step="0.01" id="check_value" name="check_value"
                                    class="form-control @error('check_value') is-invalid @enderror" placeholder="0.00"
                                    value="{{ old('check_value', $check->check_value) }}">
                                @error('check_value')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Currency -->
                            <div class="col-md-6 mb-2">
                                <label for="currency" class="form-label">العملة</label>
                                <input type="text" id="currency" name="currency"
                                    class="form-control @error('currency') is-invalid @enderror" placeholder="العملة"
                                    value="{{ old('currency', $check->currency) }}">
                                @error('currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Fields -->
                        <div class="row mb-3">
                            <!-- Account Name -->
                            {{-- <div class="col-md-6 mb-2">
                                <label for="account_name" class="form-label">اسم الحساب</label>
                                <input type="text" id="account_name" name="account_name"
                                    class="form-control @error('account_name') is-invalid @enderror" placeholder="اسم الحساب"
                                    value="{{ old('account_name', $check->account_name) }}">
                                @error('account_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div> --}}

                            <div class="col-md-6 mb-2">
                                <label for="account_id" class="form-label">اسم الحساب</label>
                                <select id="account_id" name="account_id"
                                    class="form-control accounts-dropdown @error('account_id') is-invalid @enderror">
                                    <!-- Options will be dynamically loaded by Select2 -->
                                </select>
                                @error('account_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <input type="hidden" id="account_name" name="account_name" value="{{ old('account_name', $check->account_name) }}">




                            <!-- Cost Center -->
                            <div class="col-md-6 mb-2">
                                <label for="cost_center" class="form-label">مركز التكلفة</label>
                                <input type="text" id="cost_center" name="cost_center"
                                    class="form-control @error('cost_center') is-invalid @enderror" placeholder="مركز التكلفة"
                                    value="{{ old('cost_center', $check->cost_center) }}">
                                {{-- @error('cost_center')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror --}}
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror"
                                    placeholder="ملاحظات" rows="3">{{ old('notes', $check->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="container text-center m-5">
                            <button type="submit" class="btn btn-primary btn-custom m-5">تحديث</button>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
        {!! Form::close() !!}
    </section>
@stop
@section('javascript')
    <script type="text/javascript">
        $(function() {

                        //// select accounts-dropdown////
                        function removeHtmlTags(str) {
                return str.replace(/<\/?[^>]+>/gi, '');
            }
            $("select.accounts-dropdown").select2({
                ajax: {
                    url: '/accounting/accounts-dropdown',
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.text;
                },
                templateSelection: function(data) {
                    return data.text;
                }

            }).on('select2:select', function(e) {
                var selectedAccountName = removeHtmlTags(e.params.data.text);
                $('#account_name').val(selectedAccountName);
            });

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
        });
    </script>
@endsection