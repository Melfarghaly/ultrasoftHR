@extends('layouts.app')

@section('title', __('repair::lang.view_job_sheet'))

@section('content')
@include('repair::layouts.nav')
<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>
    	@lang('repair::lang.job_sheet')
    	(<code>{{$guarantee->job_sheet_no}}</code>)
    </h1>
</section>
<!-- Main content -->
<section class="content">
	<div class="box box-solid">
		<div class="box-header no-print">
			<div class="box-tools">
				<a href="{{action('\Modules\Repair\Http\Controllers\JobSheetController@edit', ['id' => $guarantee->id])}}" class="btn btn-info cursor-pointer">
                    <i class="fa fa-edit"></i>
                    @lang("messages.edit")
                </a>
				<button type="button" class="btn btn-primary" aria-label="Print" onclick="window.print();">
					<i class="fa fa-print"></i>
					@lang( 'messages.print' )
		      	</button>
	      </div>
	    </div>
		<div class="box-body">
			{{-- business address --}}
			<div class="row invoice-info">
				<div class="col-md-6 invoice-col">
					@if(!empty(Session::get('business.logo')))
	                  <img src="{{ asset( 'uploads/business_logos/' . Session::get('business.logo') ) }}" alt="Logo" style="width: 177px;height: 155px;">
	                @endif
				</div>
				<div class="col-md-6 bizz_addr">
					<p style="text-align: center;padding-top: 40px;padding-left: 110px;">
						<strong class="font-23">
							{{$guarantee->business}}
						</strong>
						<br>
						<span>
						
						</span>
					</p>
				</div>	
			</div>
			{{-- Job sheet details --}}
			<table class="table table-bordered" style="margin-top: 15px;">
				<tr>
					<th rowspan="3">
						@lang('receipt.date'):
						<span style="font-weight: 100">
							{{@format_datetime($guarantee->created_at)}}
						</span>
					</th>
				</tr>
				<tr>
					<td>
						<b>@lang('repair::lang.service_type'):</b>
						@lang('repair::lang.'.$guarantee->service_type)
					</td>
					<th rowspan="2">
						<b>
							@lang('repair::lang.expected_delivery_date'):
						</b>
						@if(!empty($guarantee->delivery_date))
							<span style="font-weight: 100">
								{{@format_datetime($guarantee->delivery_date)}}
							</span>
						@endif
					</th>
				</tr>
				<tr>
					<td>
						<b>@lang('repair::lang.job_sheet_no'):</b>
						{{$guarantee->id}}
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<strong>@lang('role.customer'):</strong><br>
						<p>
							{{$guarantee->customer_name}} <br>
						
							@if(!empty($contact->email))
								<br>@lang('business.email'):
								{{$guarantee->customer_email}}
							@endif
							<br>@lang('contact.mobile'):
							{{$guarantee->customer_mobile}}
							@if(!empty($contact->tax_number))
								<br>@lang('contact.tax_no'):
								{{$guarantee->customer_tax_number}}
							@endif
						</p>
					</td>
					<td>
						<b>@lang('product.brand'):</b>
						{{$guarantee->product_name ?? ""}}
						<br>
						
						<b>@lang('repair::lang.serial_no'):</b>
						{{$guarantee->serial_no}}
						<br>
						<b>@lang('lang_v1.password'):</b>
						{{$guarantee->security_pwd}}
						<br>
						<b>
							@lang('repair::lang.security_pattern_code'):
						</b>
						{{$guarantee->security_pattern}}
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>
							@lang('sale.invoice_no'):
						</b>
					</td>
					<td>
						@if($guarantee->transaction_id)
						@php
						    $invoices=\DB::table('transactions')->where('id',$guarantee->transaction_id)->get();
					
						@endphp
							@foreach($invoices as $invoice)
								{{$invoice->invoice_no}}
								@if (!$loop->last)
							        {{', '}}
							    @endif
							@endforeach
						@endif
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>
							@lang('repair::lang.estimated_cost'):
						</b>
					</td>
					<td>
						<span class="display_currency" data-currency_symbol="true">
							{{$guarantee->estimated_cost}}
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>
							@lang('sale.status'):
						</b>
					</td>
					<td>
					    	@php
						    $location=\DB::table('business_locations')->where('id',$invoices[0]->location_id)->first();
					
						@endphp
						{{$location->name}}
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>
							@lang('business.location'):
						</b>
					</td>
					<td>
					{{$location->name}}
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<b>
							@lang('repair::lang.pre_repair_checklist'):
						</b>
					</td>
					<td>
						@php
							$checklists = [];
							if (!empty($guarantee->deviceModel) && !empty($guarantee->deviceModel->repair_checklist)) {
								$checklists = explode('|', $guarantee->deviceModel->repair_checklist);
							}
						@endphp
						@if(!empty($guarantee->checklist))
							@foreach($checklists as $check)
	                            <div class="col-xs-4">
	                                @if($guarantee->checklist[$check] == 'yes')
	                                    <i class="fas fa-check-square text-success fa-lg"></i>
	                                @elseif($guarantee->checklist[$check] == 'no')
	                                  <i class="fas fa-window-close text-danger fa-lg"></i>
	                                @elseif($guarantee->checklist[$check] == 'not_applicable')
	                                  <i class="fas fa-square fa-lg"></i>
	                                @endif
	                                {{$check}}
	                                <br>
	                            </div>
	                        @endforeach
	                    @endif
					</td>
				</tr>
				@if($guarantee->service_type == 'pick_up' || $guarantee->service_type == 'on_site')
					<tr>
						<td colspan="3">
							<b>
								@lang('repair::lang.pick_up_on_site_addr'):
							</b> <br>
							{!!$guarantee->pick_up_on_site_addr!!}
						</td>
					</tr>
				@endif
				<tr>
					<td colspan="3">
						<b>
							@lang('repair::lang.product_configuration'):
						</b> <br>
						@php
							$product_configuration = json_decode($guarantee->product_configuration, true);
						@endphp
						@if(!empty($product_configuration))
							@foreach($product_configuration as $product_conf)
								{{$product_conf['value']}}
								@if(!$loop->last)
									{{','}}
								@endif
							@endforeach
						@endif
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<b>
							@lang('repair::lang.condition_of_product'):
						</b> <br>
						@php
							$product_condition = json_decode($guarantee->product_condition, true);
						@endphp
						@if(!empty($product_condition))
							@foreach($product_condition as $product_cond)
								{{$product_cond['value']}}
								@if(!$loop->last)
									{{','}}
								@endif
							@endforeach
						@endif
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<b>
							@lang('repair::lang.problem_reported_by_customer'):
						</b> <br>
						@php
							$defects = json_decode($guarantee->defects, true);
						@endphp
						@if(!empty($defects))
							@foreach($defects as $product_defect)
								{{$product_defect['value']}}
								@if(!$loop->last)
									{{','}}
								@endif
							@endforeach
						@endif
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<strong>
							@lang("lang_v1.terms_conditions"):
						</strong>
						{!!$repair_settings['repair_tc_condition']!!}
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>
							@lang('repair::lang.customer_signature'):
						</b>
					</td>
					<td>
						<b>
							@lang('repair::lang.authorized_signature'):
						</b>
					</td>
				</tr>
			</table>
		</div>
	</div>
	@if(-1> 0)
		<div class="box box-solid no-print">
			<div class="box-header with-border">
				<h4>
					@lang('repair::lang.uploaded_image_for', ['job_sheet_no' => $guarantee->job_sheet_no])
				</h4>
		    </div>
			<div class="box-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>@lang('lang_v1.image')</th>
							<th>@lang('messages.action')</th>
						</tr>
					</thead>
					<tbody>
						@foreach($guarantee->media as $media)
						<tr class="media_row">
							<td>
								<a href="{{$media->display_url}}" class="cursor-pointer"target="_blank">
									{{$media->display_name}}	
								</a>
							</td>
							<td>
								<a href="{{$media->display_url}}" class="btn btn-info btn-sm" target="_blank">
									<i class="fas fa-external-link-alt"></i>
								</a>
								<a data-href="{{action('\Modules\Repair\Http\Controllers\JobSheetController@deleteJobSheetImage', ['id' => $media->id])}}" class="btn btn-danger btn-sm delete_media">
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
</section>
<!-- /.content -->
@stop
@section('css')
<style type="text/css">
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th,
	.table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td,
	.table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
		border: 1px solid #1d1a1a;
	}
	@media print{
		.bizz_addr {
			float: right;
		}
	}
</style>
@stop
@section('javascript')
<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('click', '.delete_media', function (e) {
            e.preventDefault();
            var url = $(this).data('href');
            var this_btn = $(this);
            swal({
                title: LANG.sure,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((confirmed) => {
                if (confirmed) {
                    $.ajax({
                        method: 'GET',
                        url: url,
                        dataType: 'json',
                        success: function(result) {
                            if(result.success == true){
			                    this_btn.closest('tr').remove();
			                    toastr.success(result.msg);
			                } else {
			                    toastr.error(result.msg);
			                }
                        }
                    });
                }
            });
        });
	});
</script>
@stop