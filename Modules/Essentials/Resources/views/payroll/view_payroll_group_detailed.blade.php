@extends('layouts.app')
@section('title', __('essentials::lang.view_payroll_group'))
@section('content')
@include('essentials::layouts.nav_hrm')
<style>
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border-top: 1px solid #f4f4f4;
    text-align: center;
}
</style>
<section class="content-header">
	<h1>
    	@lang('essentials::lang.view_payroll_group')
    	<small><code>({{$payroll_group->name}})</code></small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid" id="payroll-group">
				<div class="box-header no-print">
					<div class="box-tools">
						<button type="button" class="btn btn-primary" aria-label="Print" id="print_payrollgroup">
							<i class="fa fa-print"></i>
							@lang( 'messages.print' )
				      	</button>
			      	</div>
			    </div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<h3 class="text-center">
	                            <u>{!! __('essentials::lang.payroll_for_month', ['date' => $month_name . ' ' . $year]) !!}</u>
	                        </h3>
						</div>
					</div>
					<div class="row margin-bottom-20">
						<div class="col-md-6 text-center mt-5">
							<strong class="font-23">{{$payroll_group->business->name}}</strong> <br>
							@if(!empty($payroll_group->businessLocation))
								{{$payroll_group->businessLocation->name}} <br>
								{!!$payroll_group->businessLocation->location_address!!}
							@else
								{{__('report.all_locations')}}
							@endif
						</div>
						<div class="col-md-6 text-center mt-5">
							<b class="font-17">
								@lang('essentials::lang.payroll_group'):
							</b>
							{{$payroll_group->name}} <br>
							<b class="font-17">
								@lang('sale.status'):
							</b>
							@lang('sale.'.$payroll_group->status)
						</div>
					</div>
	                <div class="table-responsive mt-15">
	                    <table class="table" id="payroll_g_table" style="width: 100% !important;">
	                        <thead>
	                            <tr>
	                               <th>اسم الموظف</th>
	                               <th>	@lang( 'essentials::lang.amount_per_unit_duartion' ):</th>
	                               <th>@lang( 'essentials::lang.total_work_duration' ):</th>
	                              
	                               <th>الراتب الاساسي</th>
	                               <th>المكافئات</th>
	                               <th>الخصومات</th>
	                               <th>السلف</th>
	                               <th>@lang('lang_v1.tax')</th>
	                               <th>@lang('lang_v1.insurance')</th>
	                               <th>صافي الراتب</th>
	                               <th>اجراء</th>
	                            </tr>
	                        </thead>
	                        	@php
	                        	$counter1=0;
	                        	$counter2=0;
	                        	$counter3=0;
	                        	$counter4=0;
	                        	$counter5=0;
	                        	$counter6=0;
	                        	$counter7=0;
	                        	
	                        	$counter_insurance=0;
	                        	$counter_taxes=0;
	                        	@endphp
	                        <tbody>
	                        	@foreach($payrolls as $id => $row)
	                        	@php
	                        	
	                        	
	                        	$payroll=\App\Transaction::with(['transaction_for'])->find($row['transaction_id']);
	                        	@endphp
		                        	<tr>
		                        		<td>
		                        			{{$row['employee']}} 
		                        		</td>
		                        		<td>{{$payroll->essentials_amount_per_unit_duration}}</td>
		                        		<td>{{$payroll->essentials_duration}}</td>
		                        		<td>
		                        		    <span class="display_currency" data-currency_symbol="true">{{$fixed_salary=$payroll->essentials_duration * $payroll->essentials_amount_per_unit_duration}} </span>
		                        		
		                        		</td>
		                        		@php
		                        		$allowances = !empty($payroll->essentials_allowances) ? json_decode($payroll->essentials_allowances, true) : [];
                                        $deductions = !empty($payroll->essentials_deductions) ? json_decode($payroll->essentials_deductions, true) : [];
		                        	    $total_allowances=0;
		                        	    $total_deductions=0;
		                        	    $total_taxes=0;
		                        	    $total_insurance=0;
		                        	    $tax_name=__('lang_v1.tax');
		                        	    $insurance_name=__('lang_v1.insurance');
		                        		@endphp
		                        		@foreach($allowances['allowance_names'] as $key => $value)
                	             
                		                        @php
                		                            $total_allowances += !empty($allowances['allowance_amounts'][$key]) ? $allowances['allowance_amounts'][$key] : 0;
                		                        @endphp
                	                      
                	                    @endforeach
            	                    	@foreach($deductions['deduction_names'] as $key => $value)
            	             
            		                        @php
            		                        if(!in_array($deductions['deduction_names'][$key],[$tax_name,$insurance_name]))
            		                            $total_deductions += !empty($deductions['deduction_amounts'][$key]) ? $deductions['deduction_amounts'][$key] : 0;
            		                        
            		                        if($deductions['deduction_names'][$key]==$tax_name)
            		                            $total_taxes += !empty($deductions['deduction_amounts'][$key]) ? $deductions['deduction_amounts'][$key] : 0;
            		                        if($deductions['deduction_names'][$key]==$insurance_name)
            		                            $total_insurance += !empty($deductions['deduction_amounts'][$key]) ? $deductions['deduction_amounts'][$key] : 0;
            		                        @endphp
                	                      
                	                    @endforeach
                	                    <td>{{$total_allowances}}</td>
                	                    <td>{{$total_deductions}}</td>
                	                    @php
                	                    $transaction_date = \Carbon::parse($payroll['transaction_date']);
                                        $month_name = $transaction_date->format('m');
                                        $year = $transaction_date->format('Y');
                
                	                    $pay_components = DB::table('essentials_allowances_and_deductions')->join('essentials_user_allowance_and_deductions as EUAD', 'essentials_allowances_and_deductions.id', '=', 'EUAD.allowance_deduction_id')
                                      
                                        ->where('EUAD.user_id', $payroll['transaction_for']->id)
                                        ->whereMonth('applicable_date',$month_name)
                                        ->whereYear('applicable_date', $year)
                                        ->sum('amount');
                	                    @endphp
                	                    <td>{{$pay_components}}</td>
                	                    <td>{{$total_taxes}}</td>
                	                    <td>{{$total_insurance}}</td>
		                        		<td>
		                        		    {{$remain_salary=$fixed_salary-$total_deductions+$total_allowances-$pay_components -$total_insurance - $total_taxes}}
		                        		</td>
		                        	<td>
		                        	    <a href="#"  data-href="{{action('\Modules\Essentials\Http\Controllers\PayrollController@show', [$row['transaction_id']]) }}" data-container=".view_modal" class=" btn btn-xs btn-info btn-modal"><i class="fa fa-eye" aria-hidden="true"></i> @lang("messages.view")</a>
		                        	</td>	
		                        	
		                        	</tr>
		                        	@php
    		                        	$counter1 +=$payroll->essentials_amount_per_unit_duration;
        	                        	$counter2 +=$payroll->essentials_duration;
        	                        	$counter3 +=$fixed_salary;
        	                        	$counter4 +=$total_allowances;
        	                        	$counter5 +=$total_deductions;
        	                        	$counter6 +=$pay_components;
        	                        	$counter7 +=$remain_salary;
        	                        	
        	                        	$counter_insurance +=$total_insurance;
	                        	        $counter_taxes +=$total_taxes;
        	                        	
        	                        	
		                        	@endphp
		                        @endforeach
	                        </tbody>
	                        <tfoot>
	                            <tr>
	                                <th>الاجمالي</th>
	                                <th>{{$counter1}}</th>
	                                <th>{{$counter2}}</th>
	                                <th>{{$counter3}}</th>
	                                <th>{{$counter4}}</th>
	                                <th>{{$counter5}}</th>
	                                <th>{{$counter6}}</th>
	                                <th>{{$counter_taxes}}</th>
	                                <th>{{$counter_insurance}}</th>
	                                <th>{{$counter7}}</th>
	                              <th></th>
	                            </tr>
	                        </tfoot>
	                    </table>
	                </div>
            	</div>
            </div>
		</div>
  	</div>
</section>
@endsection
<style type="text/css">
	#payroll-group-table>thead>tr>th, #payroll-group-table>tbody>tr>th,
	#payroll-group-table>tfoot>tr>th, #payroll-group-table>thead>tr>td,
	#payroll-group-table>tbody>tr>td, #payroll-group-table>tfoot>tr>td {
		border: 1px solid #1d1a1a;
	}
</style>
@section('javascript')
<script type="text/javascript">
	$(document).ready(function () {
		$('#print_payrollgroup').click( function(){
			$('#payroll-group').printThis();
		});
	});
	$(document).ready(function () {
    $('#payroll_g_table').DataTable({
        pagingType: 'full_numbers',
    });
});
</script>
@stop