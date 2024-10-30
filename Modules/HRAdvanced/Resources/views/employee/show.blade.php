@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border no-print">
                    <h3 class="box-title">تفاصيل الموظف</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default" onclick="window.print();">
                            <i class="glyphicon glyphicon-print"></i> طباعة
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><strong>معلومات الموظف</strong></h4>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>رقم الموظف:</th>
                                    <td>{{ $employee->employee_number }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (بالعربية):</th>
                                    <td>{{ $employee->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>الاسم (بالإنجليزية):</th>
                                    <td>{{ $employee->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الهوية/الإقامة:</th>
                                    <td>{{ $employee->national_id }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الدخول:</th>
                                    <td>{{ $employee->entry_number }}</td>
                                </tr>
                                <tr>
                                    <th>المستوى التعليمي:</th>
                                    <td>{{ $employee->education_level }}</td>
                                </tr>
                                <tr>
                                    <th>الجنسية:</th>
                                    <td>{{ $employee->nationality }}</td>
                                </tr>
                                <tr>
                                    <th>المسمى الوظيفي:</th>
                                    <td>{{ $employee->occupation }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الميلاد:</th>
                                    <td>{{ $employee->birthdate }}</td>
                                </tr>
                                <tr>
                                    <th>الدين:</th>
                                    <td>{{ $employee->religion }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة الاجتماعية:</th>
                                    <td>{{ $employee->marital_status }}</td>
                                </tr>
                                <tr>
                                    <th>رقم الجوال:</th>
                                    <td>{{ $employee->phone_number }}</td>
                                </tr>
                                <tr>
                                    <th>البريد الإلكتروني:</th>
                                    <td>{{ $employee->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4><strong>معلومات العقد</strong></h4>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>بداية العقد (بالتقويم الميلادي):</th>
                                    <td>{{ $employee->contract_start_gregorian }}</td>
                                </tr>
                                <tr>
                                    <th>نهاية العقد (بالتقويم الميلادي):</th>
                                    <td>{{ $employee->contract_end_gregorian }}</td>
                                </tr>
                                <tr>
                                    <th>بداية العقد (بالتقويم الهجري):</th>
                                    <td>{{ $employee->contract_start_hijri }}</td>
                                </tr>
                                <tr>
                                    <th>نهاية العقد (بالتقويم الهجري):</th>
                                    <td>{{ $employee->contract_end_hijri }}</td>
                                </tr>
                                <tr>
                                    <th>أيام الإجازة:</th>
                                    <td>{{ $employee->vacation_days }}</td>
                                </tr>
                                <tr>
                                    <th>مدة العقد (بالأشهر):</th>
                                    <td>{{ $employee->contract_duration_months }}</td>
                                </tr>
                                <tr>
                                    <th>مستحق لتذكرة سفر:</th>
                                    <td>{{ $employee->entitled_to_ticket ? 'نعم' : 'لا' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- معلومات التأمين الاجتماعي -->
                    <h4><strong>معلومات التأمين الاجتماعي</strong></h4>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>تسجيل التأمين (بالتقويم الميلادي):</th>
                            <td>{{ $employee->social_insurance_registration_gregorian }}</td>
                        </tr>
                        <tr>
                            <th>تسجيل التأمين (بالتقويم الهجري):</th>
                            <td>{{ $employee->social_insurance_registration_hijri }}</td>
                        </tr>
                        <tr>
                            <th>مستثنى من التأمين:</th>
                            <td>{{ $employee->employee_excluded_from_insurance ? 'نعم' : 'لا' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الاستثناء (بالتقويم الميلادي):</th>
                            <td>{{ $employee->exclusion_date_gregorian }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الاستثناء (بالتقويم الهجري):</th>
                            <td>{{ $employee->exclusion_date_hijri }}</td>
                        </tr>
                        <tr>
                            <th>سبب الاستثناء:</th>
                            <td>{{ $employee->exclusion_reason }}</td>
                        </tr>
                    </table>

                    <!-- تفاصيل التأمين الاجتماعي -->
                    <h4><strong>تفاصيل التأمين الاجتماعي</strong></h4>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>رقم تأمين الشركة:</th>
                            <td>{{ $employee->company_insurance_number }}</td>
                        </tr>
                        <tr>
                            <th>رقم تأمين الموظف:</th>
                            <td>{{ $employee->employee_insurance_number }}</td>
                        </tr>
                        <tr>
                            <th>جهة العمل:</th>
                            <td>{{ $employee->work_sponsor }}</td>
                        </tr>
                        <tr>
                            <th>رقم مكتب العمل:</th>
                            <td>{{ $employee->work_office_number }}</td>
                        </tr>
                    </table>
                    <!-- Files Table -->
                    <h4><strong>المستندات</strong></h4>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>الإقامة (PDF/صورة):</th>
                            <td>
                                @if($employee->iqama)
                                    <a href="{{ asset('storage/' . $employee->iqama) }}" target="_blank" class="btn btn-info">
                                        تحميل الإقامة
                                    </a>
                                @else
                                    <span>لم يتم رفع الملف</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>جواز السفر (PDF/صورة):</th>
                            <td>
                                @if($employee->passport)
                                    <a href="{{ asset('storage/' . $employee->passport) }}" target="_blank" class="btn btn-info">
                                        تحميل جواز السفر
                                    </a>
                                @else
                                    <span>لم يتم رفع الملف</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>جواز السفر الجديد (PDF/صورة):</th>
                            <td>
                                @if($employee->new_passport)
                                    <a href="{{ asset('storage/' . $employee->new_passport) }}" target="_blank" class="btn btn-info">
                                        تحميل جواز السفر الجديد
                                    </a>
                                @else
                                    <span>لم يتم رفع الملف</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .box-tools { display: none; }
        .container { width: 100%; }
        table { font-size: 12px; }
    }
</style>
@endsection
