@extends('layouts.app')

@section('title', __('accounting::lang.trial_balance'))

@section('content')


@include('accounting::layouts.nav')
<style>
    .table-borderd th,td{
        border:1px solid black  !important;
        font-weight:bold;
       
    }
    .table > thead > tr > th{
        border:1px solid black  !important;
         background-color:#0067a4   !important;
         color:white !important;
    }
    .table > tfoot > tr > th{
        border:1px solid black  !important;
         background-color:#0067a4   !important;
         color:white !important;
    }
</style>
<section class="content">
        
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('date_range_filter', __('report.date_range') . ':') !!}
            {!! Form::text('date_range_filter', null, 
                ['placeholder' => __('lang_v1.select_a_date_range'), 
                'class' => 'form-control', 'readonly', 'id' => 'date_range_filter']); !!}
        </div>
    </div>
@php
$business=session('business');
@endphp
    <div class="col-md-10 col-md-offset-1">
        
        <div class="box box-warning">
            <div class="box-header with-border text-center">
                <div class="row">
                    <div class="col-md-4">
                        <h3>
                            {{ $business->name }} <br>
                            {{ $business->tax_label_1}} : {{ $business->tax_number_1}}
                        </h3>
                        
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                </div>
                <hr>
                <h2 class="box-title">@lang( 'accounting::lang.trial_balance')</h2>
                <p>{{@format_date($start_date)}} ~ {{@format_date($end_date)}}</p>
            </div>
            
            <div class="box-body">
                <table class="table table-stripped table-borderd" id="table2excel">
                    <thead>
                        <tr>
                            <th rowspan="2" >كود الحساب</th>
                            <th rowspan="2">اسم الحساب</th>
                            <th class="text-center" colspan="3"> الرصيد الافتتاحي</th>
                            <th class="text-center" colspan="2"> حركة حالية </th>
                            <th class="text-center" colspan="2"> ميزان المجاميع  </th>
                            <th rowspan="2">رصيد نهاية الفترة  </th>
                        </tr>
                        <tr>
                             <th>مدين</th>
                            <th>دائن</th>
                            <th>رصيد ماقبل</th>
                            
                            
                            <th>مدين</th>
                            <th>دائن</th>
                            
                            <th>مدين</th>
                            <th>دائن</th>
                        </tr>
                    </thead>

                    @php
                        $total_debit  = 0;
                        $total_credit = 0;
                        $prev_total = 0;
                        $ob_total_debit  = 0;
                        $ob_total_credit = 0;
                        $tr_total_debit  = 0;
                        $tr_total_credit = 0;
                        $end_total=0;
                    @endphp

                    <tbody>
                        @foreach($accounts as $account)

                        @php
                            $total_debit  += $account->debit_balance;
                            $total_credit += $account->credit_balance;
                            
                            $ob_total_debit  += $account->ob_debit_balance;
                            $ob_total_credit += $account->ob_credit_balance;
                        
                        @endphp

                            <tr>
                                 <td>{{$account->gl_code}}</td>
                                <td>{{$account->name}}</td>
                                <td>
                                    @format_currency($account->ob_debit_balance)
                                </td>
                                <td>
                                    @format_currency($account->ob_credit_balance)
                                </td>
                                <td>{{$prev_total+=($account->ob_debit_balance - $account->ob_credit_balance)}}</td>
                                
                                <!--current trans-->
                                <td>
                                    @format_currency($account->debit_balance)
                                </td>
                                <td>
                                    @format_currency($account->credit_balance)
                                </td>
                                <!--totals-->
                                @php
                                $trial_debit=$account->debit_balance + $account->ob_debit_balance;
                                $trial_credit=$account->credit_balance + $account->ob_credit_balance;
                                
                                $tr_total_debit +=$trial_debit;
                                $tr_total_credit +=$trial_credit;
                                @endphp
                                <td>
                                    @format_currency($trial_debit)
                                </td>
                                <td>
                                    @format_currency($trial_credit)
                                </td>
                                <?php $end_total += ($trial_debit - $trial_credit); ?>
                                <td>@format_currency($trial_debit - $trial_credit)</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>الاجمالي</th>
                            <th class="total_debit">@format_currency($ob_total_debit)</th>
                            <th class="total_credit">@format_currency($ob_total_credit)</th>
                            
                            <th class="total_debit">@format_currency($prev_total)</th>
                            <th class="total_debit">@format_currency($total_debit)</th>
                            <th class="total_credit">@format_currency($total_credit)</th>
                            
                            <th class="total_debit">@format_currency($tr_total_debit)</th>
                            <th class="total_credit">@format_currency($tr_total_credit)</th>
                            
                            <th class="total_credit">@format_currency($end_total)</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

</section>


@stop


@section('javascript')
<script src=" https://cdn.jsdelivr.net/npm/table2excel@1.0.4/dist/table2excel.min.js "></script>

<script type="text/javascript">

  //$("#button_export").click(function(){
   // debugger;
   //  $('#table2excel').DataTable();
 
//});  

    $(document).ready(function(){
        $('#table2excel').DataTable();
        dateRangeSettings.startDate = moment('{{$start_date}}');
        dateRangeSettings.endDate = moment('{{$end_date}}');

        $('#date_range_filter').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#date_range_filter').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                apply_filter();
            }
        );
        $('#date_range_filter').on('cancel.daterangepicker', function(ev, picker) {
            $('#date_range_filter').val('');
            apply_filter();
        });

        function apply_filter(){
            var start = '';
            var end = '';

            if ($('#date_range_filter').val()) {
                start = $('input#date_range_filter')
                    .data('daterangepicker')
                    .startDate.format('YYYY-MM-DD');
                end = $('input#date_range_filter')
                    .data('daterangepicker')
                    .endDate.format('YYYY-MM-DD');
            }

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('start_date', start);
            urlParams.set('end_date', end);
            window.location.search = urlParams;
        }
    });

</script>

@stop