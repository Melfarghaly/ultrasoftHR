@extends('layouts.app')
@section('title', 'حركة الشيكات')

@section('content')
    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-solid'])
            @if (session('success'))
                <div id="success-message" class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="container mt-5">
                <h1 class="mb-4 text-center">حركة الشيكات</h1>
                <div class="mb-4">
                    <form method="GET" action="{{ route('checks.movementChecks') }}">
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
                    <form method="POST" action="{{ route('checks.save') }}">
                        @csrf
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>حركة الشيك</th>
                                    <th>الإجراءات</th>
                                    <th id="bank_name_header" style="display: none;">اسم البنك</th>
                                    <th id="vault_name_header" style="display: none;">الخزنة</th>
                                    <th id="account_type_header" style="display: none;">نوع الحساب</th>
                                    <th>تاريخ التحرير</th>
                                    <th>اسم الحساب</th>
                                    <th>رقم الشيك</th>
                                    <th>قيمة الشيك</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>ملاحظات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checks as $check)
                                    <tr id="check_row_{{ $check->id }}">
                                        <td>
                                            <select id="check_type_{{ $check->id }}"
                                                name="checks[{{ $check->id }}][check_type]" class="form-control"
                                                onchange="updateActions(this, {{ $check->id }})">
                                                <option value="" selected>اختر الحركة</option>
                                                <option value="issued">صادر</option>
                                                <option value="received">وارد</option>
                                                <option value="under_collection">تحت التحصيل</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="action_select_{{ $check->id }}"
                                                name="checks[{{ $check->id }}][action]" class="form-control"
                                                style="display: none;">
                                            </select>
                                        </td>
                                        <td id="bank_name_cell_{{ $check->id }}" style="display: none;">
                                            <input type="text" id="bank_name_{{ $check->id }}"
                                                name="checks[{{ $check->id }}][bank_name]" class="form-control"
                                                value="{{ $check->bank_name }}">
                                        </td>
                                        <td id="vault_name_cell_{{ $check->id }}" style="display: none;">
                                            <input type="text" id="vault_name_{{ $check->id }}"
                                                name="checks[{{ $check->id }}][vault_name]" class="form-control"
                                                value="{{ $check->vault_name }}">
                                        </td>
                                        <td id="account_type_cell_{{ $check->id }}" style="display: none;">
                                            <input type="text" id="account_type_{{ $check->id }}"
                                                name="checks[{{ $check->id }}][account_type]" class="form-control"
                                                value="{{ $check->account_type }}">
                                        </td>
                                        <td>{{ $check->issue_date->format('Y-m-d') }}</td>
                                        <td>{{ $check->account_name }}</td>
                                        <td>{{ $check->check_number }}</td>
                                        <td>{{ $check->check_value }}</td>
                                        <td>{{ $check->due_date->format('Y-m-d') }}</td>
                                        <td>{{ $check->notes }}</td>
                                        <td>
                                            <button type="submit" name="save_check[{{ $check->id }}]"
                                                class="btn btn-success btn-sm">حفظ</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">لا توجد بيانات لعرضها</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        @endcomponent
    </section>
@stop
@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateActions(selectElement, checkId) {
            const actionSelect = document.querySelector(`#action_select_${checkId}`);
            const bankNameCell = document.querySelector(`#bank_name_cell_${checkId}`);
            const vaultNameCell = document.querySelector(`#vault_name_cell_${checkId}`);
            const accountTypeCell = document.querySelector(`#account_type_cell_${checkId}`);
            const bankNameHeader = document.querySelector('#bank_name_header');
            const vaultNameHeader = document.querySelector('#vault_name_header');
            const accountTypeHeader = document.querySelector('#account_type_header');
            const selectedCheckType = selectElement.value;
            const selectedAction = actionSelect.value;

            // Define options for each movement type
            const actions = {
                'issued': [{
                        value: 'issue_check',
                        text: 'صرف الشيك البنكي'
                    },
                    {
                        value: 'receive_check',
                        text: 'استلام الشيك النقدي'
                    },
                    {
                        value: 'cancel_check',
                        text: 'إلغاء الشيك'
                    }
                ],
                'received': [{
                        value: 'issue_check',
                        text: 'صرف الشيك البنكي'
                    },
                    {
                        value: 'receive_check',
                        text: 'استلام الشيك النقدي'
                    },
                    {
                        value: 'cancel_check',
                        text: 'إلغاء الشيك'
                    },
                    {
                        value: 'bank_receipt',
                        text: 'حافظة بنك'
                    },
                    {
                        value: 'endorse_check',
                        text: 'تظهير'
                    }
                ],
                'under_collection': [{
                    value: 'cancel_check',
                    text: 'إلغاء الشيك'
                }]
            };



            // Preserve the currently selected value
            const currentValue = actionSelect.value;

            // Clear existing options
            actionSelect.innerHTML = '';



            // Add the default option first
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'اختر الحركة';
            defaultOption.selected = true; // Ensure it's selected
            actionSelect.appendChild(defaultOption);

            // Add relevant options
            if (actions[selectedCheckType]) {
                actions[selectedCheckType].forEach(action => {
                    const option = document.createElement('option');
                    option.value = action.value;
                    option.textContent = action.text;
                    actionSelect.appendChild(option);
                });

                // Restore the previously selected value, if it matches the new options
                if (actions[selectedCheckType].some(action => action.value === currentValue)) {
                    actionSelect.value = currentValue;
                } else {
                    actionSelect.value = ''; // Reset if the previous value is no longer valid
                }

                actionSelect.style.display = 'inline-block'; // Show the select element
            } else {
                actionSelect.style.display = 'none'; // Hide the select element if no valid type is selected
            }

            // Show or hide bank name or vault name input based on the action selected
            if (actionSelect.value === 'issue_check') {
                bankNameCell.style.display = 'table-cell';
                bankNameHeader.style.display = 'table-cell';
                vaultNameCell.style.display = 'none';
                vaultNameHeader.style.display = 'none';
                accountTypeCell.style.display = 'none';
                accountTypeHeader.style.display = 'none';
            } else if (actionSelect.value === 'receive_check') {
                vaultNameCell.style.display = 'table-cell';
                vaultNameHeader.style.display = 'table-cell';
                bankNameCell.style.display = 'none';
                bankNameHeader.style.display = 'none';
                accountTypeCell.style.display = 'none';
                accountTypeHeader.style.display = 'none';
            } else if (actionSelect.value === 'bank_receipt') {
                bankNameCell.style.display = 'table-cell';
                bankNameHeader.style.display = 'table-cell';
                vaultNameCell.style.display = 'none';
                vaultNameHeader.style.display = 'none';
                accountTypeCell.style.display = 'none';
                accountTypeHeader.style.display = 'none';
            } else if (actionSelect.value === 'endorse_check') {
                bankNameCell.style.display = 'none';
                bankNameHeader.style.display = 'none';
                vaultNameCell.style.display = 'none';
                vaultNameHeader.style.display = 'none';
                accountTypeCell.style.display = 'table-cell';
                accountTypeHeader.style.display = 'table-cell';
            } else {
                bankNameCell.style.display = 'none';
                bankNameHeader.style.display = 'none';
                vaultNameCell.style.display = 'none';
                vaultNameHeader.style.display = 'none';
                accountTypeCell.style.display = 'none';
                accountTypeHeader.style.display = 'none';
            }
        }

        // Add event listeners to all check type selects
        document.querySelectorAll('select[id^="check_type_"]').forEach(select => {
            select.addEventListener('change', function() {
                updateActions(this, this.id.split('_')[2]); // Pass checkId extracted from id
            });
        });

        // Add event listeners to all action selects
        document.querySelectorAll('select[id^="action_select_"]').forEach(select => {
            select.addEventListener('change', function() {
                const checkId = this.id.split('_')[2];
                updateActions(document.querySelector(`#check_type_${checkId}`), checkId);
            });
        });


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
        });
    </script>

@stop
