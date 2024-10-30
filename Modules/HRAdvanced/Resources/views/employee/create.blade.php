@extends('layouts.app')

@section('title', __('hradvanced::lang.hradvanced'))

@section('content')
<style>
.tab-content>.tab-pane {
    display: block;
}
</style>
    @include('hradvanced::layouts.nav')
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">تسجيل موظف</h1>
        <!-- <ol class="breadcrumb">
                                                                                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                                                        <li class="active">Here</li>
                                                                                                    </ol> -->
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class=" ">

                    <div class=" ">
                        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div>

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs hide" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#emp_detial" aria-controls="emp_detial" role="tab"
                                                    data-toggle="tab">
                                                    <i class="fas fa-user"></i> تفاصيل الموظف
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#contract_details" aria-controls="contract_details" role="tab"
                                                    data-toggle="tab">
                                                    <i class="fas fa-file-contract"></i> تفاصيل العقد
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#social_insurance" aria-controls="social_insurance" role="tab"
                                                    data-toggle="tab">
                                                    <i class="fas fa-shield-alt"></i> التأمينات الاجتماعية
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#company_insurance" aria-controls="company_insurance"
                                                    role="tab" data-toggle="tab">
                                                    <i class="fas fa-briefcase-medical"></i> تفاصيل التأمينات
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#medical_insurance" aria-controls="medical_insurance"
                                                    role="tab" data-toggle="tab">
                                                    <i class="fas fa-stethoscope"></i> التأمين الطبي
                                                </a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#additional" aria-controls="additional" role="tab"
                                                    data-toggle="tab">
                                                    <i class="fas fa-info-circle"></i> معلومات اضافية
                                                </a>
                                            </li>
                                        </ul>


                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane active" id="emp_detial">
                                                <div class="col-md-6">
                                                    <!-- Employee Details Section -->
                                                    <div class="box  ">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">تفاصيل الموظف</h3>
                                                        </div>
                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label for="employee_number" class="control-label">الرقم
                                                                    الوظيفي </label>
                                                                <input type="number" name="employee_number"
                                                                    class="form-control" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="name_ar" class="control-label">الاسم
                                                                    (عربي)</label>
                                                                <input type="text" name="name_ar" class="form-control"
                                                                    required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="name_en" class="control-label">الاسم
                                                                    (انجليزي)</label>
                                                                <input type="text" name="name_en" class="form-control"
                                                                    required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="national_id" class="control-label">رقم الهوية /
                                                                    الإقامة</label>
                                                                <input type="number" name="national_id" id="national_id"
                                                                    class="form-control" required>
                                                                <span id="national_id_error" class="text-danger"
                                                                    style="display:none;">يجب أن يكون رقم الهوية 10
                                                                    أرقام</span>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="entry_number" class="control-label">رقم دخول
                                                                    الدولة</label>
                                                                <input type="number" name="entry_number"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="education_level" class="control-label">الدرجة
                                                                    العلمية / التعليم</label>
                                                                <input type="text" name="education_level"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="nationality"
                                                                    class="control-label">الجنسية</label>
                                                                <select name="nationality" class="form-control select2">
                                                                    <option value="">اختر الجنسية</option>
                                                                    <option value="Saudi">السعودية</option>
                                                                    <option value="Ethiopia">إثيوبيا</option>
                                                                    <option value="Eritrea">إريتريا</option>
                                                                    <option value="Australia">أستراليا</option>
                                                                    <option value="Jordan">الأردن</option>
                                                                    <option value="Algeria">الجزائر</option>
                                                                    <option value="Palestine">السلطة الفلسطينية</option>
                                                                    <option value="Sudan">السودان</option>
                                                                    <option value="Iraq">العراق</option>
                                                                    <option value="Philippines">الفلبين</option>
                                                                    <option value="Morocco">المغرب</option>
                                                                    <option value="UK">المملكة المتحدة</option>
                                                                    <option value="India">الهند</option>
                                                                    <option value="Yemen">اليمن</option>
                                                                    <option value="Indonesia">إندونيسيا</option>
                                                                    <option value="Pakistan">باكستان</option>
                                                                    <option value="Bangladesh">بنغلاديش</option>
                                                                    <option value="Burkina Faso">بوركينافاسو</option>
                                                                    <option value="Thailand">تايلاند</option>
                                                                    <option value="Turkey">تركيا</option>
                                                                    <option value="Chad">تشاد</option>
                                                                    <option value="Tunisia">تونس</option>
                                                                    <option value="Sri Lanka">سريلانكا</option>
                                                                    <option value="Syria">سوريا</option>
                                                                    <option value="Guinea">غينيا</option>
                                                                    <option value="France">فرنسا</option>
                                                                    <option value="Vietnam">فيتنام</option>
                                                                    <option value="Lebanon">لبنان</option>
                                                                    <option value="Mali">مالي</option>
                                                                    <option value="Egypt">مصر</option>
                                                                    <option value="Myanmar">ميانمار</option>
                                                                    <option value="Nepal">نيبال</option>
                                                                    <option value="Nigeria">نيجيريا</option>
                                                                </select>
                                                            </div>


                                                            <div class="form-group">
                                                                <div><label for="job_id"
                                                                        class="control-label">المهنة</label></div>
                                                                <div><button class="btn btn-primary pull-right"
                                                                        data-toggle="modal"
                                                                        data-target="#createJobModal">إضافة
                                                                        وظيفة</button></div>
                                                                <select name="job_id" class="form-control select2"
                                                                    id="job-list">

                                                                    <option value="">اختر المهنة</option>
                                                                    @foreach ($jobs as $row)
                                                                        <option value="{{ $row->id }}">
                                                                            {{ $row->job_title }} -
                                                                            {{ Str::limit($row->job_description, 50) }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="birthdate" class="control-label">تاريخ
                                                                    الميلاد</label>
                                                                <input type="text" name="birthdate"
                                                                    class="form-control datepicker">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="religion"
                                                                    class="control-label">الديانة</label>
                                                                <input type="text" name="religion"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="marital_status" class="control-label">الحالة
                                                                    الاجتماعية</label>
                                                                <select name="marital_status" class="form-control">
                                                                    <option value="">اختر الحالة الاجتماعية</option>
                                                                    <option value="Single">أعزب</option>
                                                                    <option value="Married">متزوج</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="phone_number" class="control-label">رقم الجوال
                                                                    (+966)</label>
                                                                <input type="text" name="phone_number"
                                                                    class="form-control" placeholder="+966">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="email" class="control-label">البريد
                                                                    الإلكتروني</label>
                                                                <input type="email" name="email"
                                                                    class="form-control">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="contract_details">
                                                <div class="col-md-6">
                                                    <!-- Contract Details Section -->
                                                    <div class="box  ">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">تفاصيل العقد</h3>
                                                        </div>
                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label for="contract_start_gregorian"
                                                                    class="control-label">بداية العقد (ميلادي)</label>
                                                                <input type="text" name="contract_start_gregorian"
                                                                    id="contract_start_gregorian"
                                                                    class="form-control datepicker">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="contract_end_gregorian"
                                                                    class="control-label">نهاية العقد (ميلادي)</label>
                                                                <input type="text" name="contract_end_gregorian"
                                                                    id="contract_end_gregorian"
                                                                    class="form-control datepicker">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="contract_start_hijri"
                                                                    class="control-label">بداية العقد (هجري)</label>
                                                                <div class="input-group date" id="hijri-start-date">
                                                                    <input type="text" id="contract_start_hijri"
                                                                        name="contract_start_hijri" class="form-control"
                                                                        placeholder="YYYY-MM-DD">
                                                                    <datepicker-hijri reference="contract_start_hijri"
                                                                        placement="bottom"
                                                                        selected-date="1446/01/01"></datepicker-hijri>
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="contract_end_hijri"
                                                                    class="control-label">نهاية العقد (هجري)</label>
                                                                <div class="input-group date" id="hijri-end-date">
                                                                    <input type="text" name="contract_end_hijri"
                                                                        id="contract_end_hijri" class="form-control"
                                                                        placeholder="YYYY-MM-DD">
                                                                    <datepicker-hijri reference="contract_end_hijri"
                                                                        placement="bottom"
                                                                        selected-date="1446/01/01"></datepicker-hijri>
                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="vacation_days" class="control-label">عدد أيام
                                                                    الإجازة</label>
                                                                <input type="number" name="vacation_days"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="contract_duration_months"
                                                                    class="control-label">مدة العقد (بالشهور)</label>
                                                                <input type="number" name="contract_duration_months"
                                                                    id="contract_duration_months" class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="entitled_to_ticket"
                                                                    class="control-label">يستحق تذكرة سفر</label>
                                                                <select id="entitled_to_ticket" name="entitled_to_ticket"
                                                                    class="form-control">
                                                                    <option value="0">لا</option>
                                                                    <option value="1">نعم</option>
                                                                </select>
                                                            </div>

                                                            <div id="ticket_options" class="form-group"
                                                                style="display: none;">
                                                                <label for="ticket_type" class="control-label">نوع
                                                                    التذكرة</label>
                                                                <select id="ticket_type" name="ticket_type"
                                                                    class="form-control">
                                                                    <option value="">اختر نوع التذكرة</option>
                                                                    <option value="cash">نقدية</option>
                                                                    <option value="physical">عينية</option>
                                                                </select>
                                                            </div>

                                                            <div id="cash_ticket" class="form-group"
                                                                style="display: none;">
                                                                <label for="cash_value" class="control-label">القيمة
                                                                    النقدية</label>
                                                                <input type="number" id="cash_value" name="cash_value"
                                                                    class="form-control"
                                                                    placeholder="أدخل القيمة النقدية">
                                                            </div>

                                                            <div id="physical_ticket" class="form-group"
                                                                style="display: none;">
                                                                <div class="form-group">
                                                                    <label for="ticket_from"
                                                                        class="control-label">من</label>
                                                                    <input type="text" id="ticket_from"
                                                                        name="ticket_from" class="form-control"
                                                                        placeholder="من (المدينة)">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="ticket_to"
                                                                        class="control-label">إلى</label>
                                                                    <input type="text" id="ticket_to" name="ticket_to"
                                                                        class="form-control" placeholder="إلى (المدينة)">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="trip_type" class="control-label">نوع
                                                                        الرحلة</label>
                                                                    <select id="trip_type" name="trip_type"
                                                                        class="form-control">
                                                                        <option value="one_way">ذهاب فقط</option>
                                                                        <option value="round_trip">ذهاب وعودة</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="social_insurance">
                                                <div class="col-md-6">
                                                    <!-- Social Security Information Section -->
                                                    <div class="box box-primary">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">معلومات التأمينات الاجتماعية</h3>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="social_insurance_registration_gregorian"
                                                                    class="control-label">تاريخ التسجيل بالتأمينات
                                                                    (ميلادي)</label>
                                                                <input type="text"
                                                                    name="social_insurance_registration_gregorian"
                                                                    class="form-control datepicker">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="social_insurance_registration_hijri"
                                                                    class="control-label">تاريخ التسجيل بالتأمينات
                                                                    (هجري)</label>
                                                                <div class="input-group date" id="insurance-hijri-date">
                                                                    <input type="text"
                                                                        name="social_insurance_registration_hijri"
                                                                        id="social_insurance_registration_hijri"
                                                                        class="form-control" placeholder="YYYY-MM-DD">
                                                                    <datepicker-hijri
                                                                        reference="social_insurance_registration_hijri"
                                                                        placement="bottom"
                                                                        selected-date="1446/01/01"></datepicker-hijri>

                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="employee_excluded_from_insurance"
                                                                    class="control-label">تم استبعاد الموظف من
                                                                    التأمينات</label>
                                                                <select name="employee_excluded_from_insurance"
                                                                    class="form-control">
                                                                    <option value="0">لا</option>
                                                                    <option value="1">نعم</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="exclusion_date_gregorian"
                                                                    class="control-label">تاريخ الاستبعاد (ميلادي)</label>
                                                                <input type="text" name="exclusion_date_gregorian"
                                                                    class="form-control datepicker">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="exclusion_date_hijri"
                                                                    class="control-label">تاريخ الاستبعاد (هجري)</label>
                                                                <div class="input-group date" id="exclusion-hijri-date">
                                                                    <input type="text" name="exclusion_date_hijri"
                                                                        id="exclusion_date_hijri" class="form-control"
                                                                        placeholder="YYYY-MM-DD">
                                                                    <datepicker-hijri reference="exclusion_date_hijri"
                                                                        placement="bottom"
                                                                        selected-date="1446/01/01"></datepicker-hijri>

                                                                    <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="exclusion_reason" class="control-label">سبب
                                                                    الاستبعاد</label>
                                                                <select name="exclusion_reason" class="form-control">
                                                                    <option value="">اختر سبب الاستبعاد</option>
                                                                    <option value="retirement">التقاعد</option>
                                                                    <option value="resignation">الاستقالة</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="company_insurance">
                                                <div class="col-md-6">
                                                    <!-- Notifications Section -->
                                                    <div class="box box-primary">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">الإشعارات</h3>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="requires_notification"
                                                                    class="control-label">يتطلب تنبيه</label>
                                                                <select name="requires_notification" class="form-control">
                                                                    <option value="0">لا</option>
                                                                    <option value="1">نعم</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group hide">
                                                                <label for="last_updated_at" class="control-label">تاريخ
                                                                    آخر تعديل</label>
                                                                <input type="datetime-local" name="last_updated_at"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group hide">
                                                                <label for="updated_time" class="control-label">وقت
                                                                    التعديل</label>
                                                                <input type="time" name="updated_time"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group hide">
                                                                <label for="created_by" class="control-label">تم الإنشاء
                                                                    بواسطة</label>
                                                                <input type="text" name="created_by"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Social Security Details Section -->
                                                    <div class="box box-primary">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">تفاصيل التأمينات الاجتماعية</h3>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="company_insurance_number"
                                                                    class="control-label">رقم اشتراك المنشأة بالتأمينات
                                                                    الاجتماعية</label>
                                                                <input type="number" name="company_insurance_number"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="employee_insurance_number"
                                                                    class="control-label">رقم اشتراك الموظف بالتأمينات
                                                                    الاجتماعية</label>
                                                                <input type="number" name="employee_insurance_number"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="work_sponsor" class="control-label">الكفيل
                                                                    بمكتب العمل</label>
                                                                <select name="work_sponsor" class="form-control">
                                                                    <option value="">اختر الكفيل</option>
                                                                    <option value="sponsor1">الكفيل 1</option>
                                                                    <option value="sponsor2">الكفيل 2</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="work_office_number"
                                                                    class="control-label">الرقم المحضر</label>
                                                                <input type="number" name="work_office_number"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="medical_insurance">
                                                <div class="col-md-6">
                                                    <!-- Additional Details Section -->
                                                    <div class="box box-warning ">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"> التأمين الطبي</h3>
                                                        </div>
                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label for="entitled_to_medical_insurance"
                                                                    class="control-label">هل يستحق التأمين الطبي؟</label>
                                                                <select id="entitled_to_medical_insurance"
                                                                    name="entitled_to_medical_insurance"
                                                                    class="form-control">
                                                                    <option value="0">لا</option>
                                                                    <option value="1">نعم</option>
                                                                </select>
                                                            </div>

                                                            <div id="insurance_type_wrapper" class="form-group"
                                                                style="display: none;">
                                                                <label for="medical_insurance_type"
                                                                    class="control-label">نوع التأمين الطبي</label>
                                                                <select id="medical_insurance_type"
                                                                    name="medical_insurance_type" class="form-control">
                                                                    <option value="individual">فردي</option>
                                                                    <option value="family">عائلي</option>
                                                                </select>
                                                            </div>

                                                            <!-- Add any additional fields specific to Individual/Family here if needed -->
                                                            <div id="individual_insurance_details" class="form-group"
                                                                style="display: none;">
                                                                <label class="control-label">تفاصيل تأمين فردي</label>
                                                                <input type="text" name="individual_details"
                                                                    class="form-control" placeholder="أدخل التفاصيل">
                                                            </div>

                                                            <div id="family_insurance_details" class="form-group"
                                                                style="display: none;">
                                                                <label class="control-label">تفاصيل تأمين عائلي</label>
                                                                <input type="text" name="family_details"
                                                                    class="form-control" placeholder="أدخل التفاصيل">
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="additional">
                                                <div class="col-md-6">
                                                    <!-- Additional Details Section -->
                                                    <div class="box box-warning ">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">تفاصيل إضافية</h3>
                                                        </div>
                                                        <div class="box-body">

                                                            <div class="form-group">
                                                                <label for="iqama" class="control-label">الإقامة
                                                                    (PDF/صورة)</label>
                                                                <input type="file" name="iqama"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="passport" class="control-label">جواز السفر
                                                                    (PDF/صورة)</label>
                                                                <input type="file" name="passport"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="new_passport" class="control-label">جواز السفر
                                                                    الجديد (PDF/صورة)</label>
                                                                <input type="file" name="new_passport"
                                                                    class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="project_name" class="control-label">اسم
                                                                    المشروع</label>
                                                                <select name="project_name" class="form-control">
                                                                    <option value="">اختر المشروع</option>
                                                                    <!-- Add projects dynamically here -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="project_code" class="control-label">كود
                                                                    المشروع </label>
                                                                <input type="text" name="project_code"
                                                                    class="form-control">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>







                            </div>

                            <!-- Submit Button -->
                            <div class="box-footer">
                                <button type="submit" id="create_button" class="btn btn-primary">إنشاء موظف
                                    جديد</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <!-- Create Job Modal -->
            <div class="modal fade" id="createJobModal" tabindex="-1" role="dialog"
                aria-labelledby="createJobModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createJobModalLabel">Create New Job</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createJobForm">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="job_title">Job Title</label>
                                    <input type="text" class="form-control" id="job_title" name="job_title" required>
                                </div>
                                <div class="form-group">
                                    <label for="job_description">Job Description</label>
                                    <textarea class="form-control" id="job_description" name="job_description" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Job</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>

    <!-- Hijri Datepicker CSS -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-hijri-datepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
    <!-- Moment.js and Moment Hijri -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-hijri/4.17.47/moment-hijri.min.js"></script>

    <!-- Bootstrap Hijri Datepicker JS -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>


@endsection
@section('javascript')
    <script src="https://cdn.jsdelivr.net/gh/abublihi/datepicker-hijri@v1.1/build/datepicker-hijri.js"></script>


    <script>
        $(document).ready(function() {
            // Hide insurance details on page load
            $('#insurance_type_wrapper').hide();
            $('#individual_insurance_details').hide();
            $('#family_insurance_details').hide();

            // Show insurance type dropdown based on entitlement selection
            $('#entitled_to_medical_insurance').change(function() {
                if ($(this).val() == '1') {
                    // Show the type of insurance (individual or family)
                    $('#insurance_type_wrapper').show();
                } else {
                    // Hide everything if "No" is selected
                    $('#insurance_type_wrapper').hide();
                    $('#individual_insurance_details').hide();
                    $('#family_insurance_details').hide();
                }
            });

            // Show respective details based on selected insurance type
            $('#medical_insurance_type').change(function() {
                if ($(this).val() == 'individual') {
                    $('#individual_insurance_details').show();
                    $('#family_insurance_details').hide();
                } else if ($(this).val() == 'family') {
                    $('#individual_insurance_details').hide();
                    $('#family_insurance_details').show();
                } else {
                    $('#individual_insurance_details').hide();
                    $('#family_insurance_details').hide();
                }
            });
            // Show/hide ticket options based on "يستحق تذكرة سفر" selection
            $('#entitled_to_ticket').change(function() {
                if ($(this).val() == "1") { // Yes (نعم)
                    $('#ticket_options').show();
                } else {
                    $('#ticket_options').hide();
                    $('#cash_ticket, #physical_ticket').hide(); // Hide any other open options
                }
            });

            // Show cash or physical ticket options based on "نوع التذكرة" selection
            $('#ticket_type').change(function() {
                if ($(this).val() == "cash") {
                    $('#cash_ticket').show(); // Show the cash value field
                    $('#physical_ticket').hide(); // Hide physical ticket options
                } else if ($(this).val() == "physical") {
                    $('#physical_ticket').show(); // Show the physical ticket fields
                    $('#cash_ticket').hide(); // Hide the cash value field
                } else {
                    $('#cash_ticket, #physical_ticket').hide(); // Hide both if no selection
                }
            });
            $('#national_id').on('input', function() {
                var nationalId = $(this).val();
                if (nationalId.length === 10) {
                    $('#national_id_error').hide(); // Hide error message
                    $('#create_button').removeAttr('disabled'); // Enable button
                } else {
                    $('#national_id_error').show(); // Show error message
                    $('#create_button').attr('disabled', 'disabled'); // Disable button
                }
            });
            // Check national ID input dynamically
            $('.datepicker').datetimepicker({
                format: moment_date_format,
                ignoreReadonly: true,
            });

            function calculateMonths() {
                var start = $('input#contract_start_gregorian').data("DateTimePicker").date();
                var end = $('input#contract_end_gregorian').data("DateTimePicker").date();

                if (start && end) {
                    var startDate = moment(start);
                    var endDate = moment(end);

                    if (startDate.isValid() && endDate.isValid()) {
                        var months = endDate.diff(startDate, 'months', true); // Get the months difference
                        $('#contract_duration_months').val(Math.round(months)); // Set the rounded month difference
                    } else {
                        $('#contract_duration_months').val(''); // Clear the field if dates are invalid
                    }
                }
            }
            $('#contract_start_gregorian').on('dp.change', function(e) {
                // Get the selected date
                calculateMonths();
            });
            $('#contract_end_gregorian').on('dp.change', function(e) {
                // Get the selected date
                calculateMonths();
            });

        });

        $('#createJobForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('hr_jobs.jobStore') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#createJobModal').modal('hide');

                    $('#job-list').append("<option value=" + response.jobId + ">" + response.jobTitle +
                        "</option>")
                },
                error: function(xhr) {
                    alert('An error occurred while creating the job.');
                }
            });
        });
    </script>
@endsection
