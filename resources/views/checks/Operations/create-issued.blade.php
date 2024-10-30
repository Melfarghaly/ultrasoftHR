@extends('layouts.app')
@section('title', __('stock_adjustment.add'))
@section('content')

    <style>
        .invalid-feedback {
            color: red;
        }
    </style>
    <!-- Main content -->
    <section class="content no-print">
        {!! Form::open([
            'url' => route('checks.store.issued'),
            'method' => 'post',
            'id' => 'issued_check_form',
        ]) !!}

        @component('components.widget', ['class' => 'box-solid'])
            <div class="container mt-5">
                <!-- Page header -->
                <h1 class="mb-4 text-center">إنشاء شيك صادر</h1>

                @if (session('success'))
                    <div id="success-message" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Transaction and Cost Center -->
                <div class="row mb-3">
                    <!-- Transaction Type -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label">عدد الشيكات الصادر</label>
                        <input type="text" class="form-control"
                            value="{{ session('issuedCheckCount', max(1, $issuedCheckCount)) }}" readonly>
                    </div>

                    <!-- Account Name -->
                    <div class="col-md-4 mb-2">
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
                    <input type="hidden" id="account_name" name="account_name" value="{{ old('account_name') }}">

                    <!-- Cost Center -->
                    <div class="col-md-4 mb-2">
                        <label for="cost_center" class="form-label">مركز التكلفة</label>
                        <input type="text" id="cost_center" name="cost_center"
                            class="form-control @error('cost_center') is-invalid @enderror" placeholder="مركز التكلفة"
                            value="{{ old('cost_center') }}">
                        @error('cost_center')
                            <div class="invalid-feedback">
                                {{-- {{ $message }} --}}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Check Number and Bank Name -->
                <div class="row mb-3">
                    <!-- Check Number -->
                    <div class="col-md-6 mb-2">
                        <label for="check_number" class="form-label">رقم الشيك</label>
                        <input type="text" id="check_number" name="check_number"
                            class="form-control @error('check_number') is-invalid @enderror" placeholder="رقم الشيك"
                            value="{{ old('check_number') }}">
                        @error('check_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                   
                    <!-- Bank Name -->
                    <div class="col-md-6 mb-2">
                        <label for="bank" class="form-label">البنك</label>
                        <select id="bank" name="bank" class="form-control  @error('bank') is-invalid @enderror">
                          @foreach($banks as $id => $name ){
                            <option value="{{ $id }}" >{{$name}}</option>
                          @endforeach
                        </select>
                        @error('bank')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <input type="hidden" id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                    

                    <!-- Bank Name -->
                    {{-- <div class="col-md-6 mb-2">
                        <label for="bank" class="form-label">البنك</label>
                        <select id="bank" name="bank" class="form-control @error('bank') is-invalid @enderror">
                            <option value="">اختر البنك</option>
                            @foreach ($banks as $bankValue => $bankName)
                                <option value="{{ $bankValue }}" {{ old('bank') == $bankValue ? 'selected' : '' }}>
                                    {{ $bankName }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}

                </div>

                <!-- Issue Date and Due Date -->
                <div class="row mb-3">
                    <!-- Issue Date -->
                    <div class="col-md-6 mb-2">
                        <label for="issue_date" class="form-label">تاريخ التحرير</label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type="date" id="issue_date" name="issue_date"
                                class="form-control @error('issue_date') is-invalid @enderror"
                                value="{{ old('issue_date') ?? now()->format('Y-m-d') }}">
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
                                value="{{ old('due_date') ?? '0000-00-00' }}">
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
                            value="{{ old('check_value') }}">
                        @error('check_value')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Currency -->   
                    <div class="col-md-6 mb-2">
                        <label for="currency" class="form-label">العملة</label>
                       
                            <select id="currency" name="currency" class="form-control select2 @error('bank') is-invalid @enderror">
                            <option value="{{session('currency')['id']}}"> {{session('currency')['code']}}</option>
                            @foreach ($currencies as $id => $name)
                                <option value="{{$id}}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Notes -->
                <div class="row mb-3">
                    <div class="col-md-12 mb-2">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror"
                            placeholder="ملاحظات" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center m-5">
                    <button type="submit" class="btn btn-primary btn-custom">حفظ</button>
                </div>
            </div>
        @endcomponent
        {!! Form::close() !!}
    </section>
@stop

@section('javascript')
    <script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        __page_leave_confirmation('#issued_check_form');

        $(function() {


            // Initialize Select2 on the banks dropdown
            $("select.banks-dropdown").select2({
            ajax: {
                url: '/accounting/banks-dropdown', // URL to fetch data
                dataType: 'json',
                processResults: function(data) {
                    // Process the results into the format required by Select2
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
                // Return markup as-is
                return markup;
            },
            templateResult: function(data) {
                // Display the text
                return data.text;
            },
            templateSelection: function(data) {
                // Display the text
                return data.text;
            }
        }).on('select2:select', function(e) {
            // Extract and set the selected bank name
            var selectedBankName = e.params.data.text;
            $('#bank_name').val(selectedBankName);
        });

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
            // Automatically hide success message after 5 seconds
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
            }, 4000);
        });
    </script>
@endsection

@cannot('view_purchase_price')
    <style>
        .show_price_with_permission {
            display: none !important;
        }
    </style>
@endcannot
