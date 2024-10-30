@extends('layouts.app')
@section('title', 'إنشاء سند استلام نقدي')

@section('content')
    <style>
        .invalid-feedback {
            color: red;
            display: block;
            /* Ensure it appears below the input */
            margin-top: 0.25rem;
            /* Add some space between the input and the error message */
        }

        .is-invalid {
            border-color: red;
            padding-right: calc(1.5em + .75rem);
            background-position: right calc(.375em + .1875rem) center;
            box-shadow: 0 0 0 0.2rem rgba(255, 0, 0, 0.25);
            /* Optional: add a shadow for visibility */
        }

        .alert-success {
            color: green;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #d4edda;
            border-radius: 5px;
            background-color: #d4edda;
        }
    </style>
    <section class="content no-print">
        <div id="response-message"></div>

        <form id="cash_receipt_form" method="POST" action="{{ route('vouchers.store.cash_receipt') }}">
            @csrf
            @component('components.widget', ['class' => 'box-solid'])
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="container mt-5">
                            <h1 class="mb-4 text-center">إنشاء سند استلام نقدي</h1>
                            <input type="hidden" name="voucher_type" value="cash_receipt">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">عدد سندات الاستلام</label>
                                    <input type="text" class="form-control" value="{{ max(1, $receivedCashCount) }}"
                                        readonly>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="voucher_date" class="form-label">تاريخ السند</label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type="date" id="voucher_date" name="voucher_date"
                                            class="form-control @error('voucher_date') is-invalid @enderror"
                                            value="{{ old('voucher_date') ?? now()->format('Y-m-d') }}">
                                        <span class="input-group-addon"><span
                                                class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    @error('voucher_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label for="currency" class="form-label">العملة</label>
                                    <input type="text" id="currency" name="currency"
                                        class="form-control @error('currency') is-invalid @enderror" placeholder="العملة"
                                        value="{{ old('currency') ?? 'الجنيه' }}">
                                    @error('currency')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="amount" class="form-label">المبلغ</label>
                                    <input type="number" step="0.01" id="amount" name="amount"
                                        class="form-control @error('amount') is-invalid @enderror" placeholder="المبلغ"
                                        value="{{ old('amount') }}">
                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <label for="cash_drawer" class="form-label">الخزنة الافتراضية</label>
                                    <input type="text" id="cash_drawer" name="cash_drawer"
                                        class="form-control @error('cash_drawer') is-invalid @enderror"
                                        placeholder="الخزنة الافتراضية" value="{{ old('cash_drawer') }}">
                                    @error('cash_drawer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Account Name -->
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
                                <input type="hidden" id="account_name" name="account_name" value="{{ old('account_name') }}">

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 mb-2">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="ملاحظات"
                                        rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="container text-center m-5">
                                <button type="submit" class="btn btn-primary btn-custom m-5">حفظ</button>
                            </div>

                        </div>
                    </div>
                </div>
            @endcomponent
        </form>
    </section>
@stop

@section('javascript')
    <script src="{{ asset('js/cash_receipt.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script type="text/javascript">
        $(document).ready(function() {


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

            // Call the bindDatePicker function to initialize the datepicker
            bindDatePicker();

            function removeHtmlTags(str) {
                return str.replace(/<\/?[^>]+>/gi, '');
            }

            // Initialize Select2
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

            function setVoucherNumber(form) {
                var actionUrl = form.attr('action');
                $.ajax({
                    url: actionUrl.replace('store',
                        'generateUniqueVoucherNumber'
                    ), // Call the route that returns the new voucher number
                    method: 'GET',
                    success: function(response) {
                        form.find('input[name="voucher_number"]').val(response.voucher_number);
                    }
                });
            }

            function reloadPage() {
                window.location.reload(); // Reload the current page
            }

            $('#cash_disbursement_form, #cash_receipt_form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                setVoucherNumber(form);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#response-message').html('<div class="alert-success">' + response
                                .message + '</div>').show();
                            form[0].reset();
                            setTimeout(function() {
                                $('#response-message').fadeOut();
                                reloadPage
                                    (); // Reload the page after a successful submission
                            }, 1000);
                        } else {
                            $('#response-message').html('<div class="alert-danger">' + response
                                .message + '</div>').show();
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<div class="alert-danger"><ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul></div>';
                        $('#response-message').html(errorHtml).show();
                    }
                });
            });

            @if (session('success'))
                $("#response-message").html('<div class="alert-success">{{ session('success') }}</div>').show();
                setTimeout(function() {
                    $("#response-message").fadeOut();
                }, 3000);
            @endif
        });
    </script>
@endsection
