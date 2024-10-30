<!-- business information here -->
<!DOCTYPE html>
<?php // dd($receipt_details); ?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="style.css"> -->
        <title>{{$job_sheet->customer->business->name}}</title>
    </head>
    <body>
        <div class="ticket">
        	<div class="text-box">
        	@if(!empty($receipt_details->logo))
        		<img class="logo" src="{{$receipt_details->logo}}" alt="Logo">
        	@endif
        	<!-- Logo -->
            <p class="@if(!empty($receipt_details->logo)) text-with-image @else centered @endif" styl>
            	<!-- Header text -->
            		<span class="headings"style="font-size:40px">	<strong>{{$job_sheet->customer->business->name}}</strong></span>
					<br/>
					<center>
					<table>
					    <tr>
					        
					        <td>{{optional($job_sheet->businessLocation)->name}}</td>
					        <td>	: @lang('business.location')</td>
					    </tr>
					</table>
				</center>	
			</p>
			</div>
			
			<div class="bb-lg mb-10"></div>
            <table style="padding-top: 5px !important" class="border-bottom width-100 table-f-12 mb-10">
            </table>
            	{{-- Job sheet details --}}
            	    	<h1 style="padding-left:30%"></h1>
			<table   style="padding-top: 5px !important  ;  direction: rtl; text-align: right; width:100%" class="border-bottom width-100 table-f-12 mb-10">
				<tr>
					
					    	<th><b>تاريخ الاستلام</b></th>
						<th>
        						<span style="font-weight: 100">
        							{{@format_datetime($job_sheet->created_at)}}
        						</span>
						</th>
					
				<td></td>
				</tr>
				<tr>
			
				<td>
						<b>
						تاريخ  التسليم 
						</b>
					</td>
						<td>
						@if(!empty($job_sheet->delivery_date))
							<span style="font-weight: 100">
								{{@format_datetime($job_sheet->delivery_date)}}
							</span>
						@endif
					</td>
                    	<th></th>
				</tr>
				<tr>
				   
				    <td>
						<b>@lang('repair::lang.job_sheet_no')</b>
						
					</td>
				    <td>{{$job_sheet->job_sheet_no}}</td>
					 <td></td>
				</tr>
				<tr>
					<td>
						<strong>@lang('role.customer'):</strong><br>
					</td>
					<td>
						<p>
							{{$job_sheet->customer->name}} <br>

						</p>
					</td>
					<td>
					    
					</td>
                </tr>
                <tr>
                    <td>@lang('contact.mobile'):</td>
                    <td>	{{$job_sheet->customer->mobile}}</td>

						
                    <td></td>
                </tr>
                <tr>
					<td>
						<b>@lang('product.brand'):</b>
						
						<br>
					</td>
					<td>
					    {{optional($job_sheet->brand)->name}}
					</td>
					<td></td>
				</tr>
				<tr>
				    <td> <b>@lang('repair::lang.device'):</b></td>
				    <td>	{{optional($job_sheet->device)->name}}</td>
				    <td></td>
				   
					
					
					
						
					
				</tr>
				<tr>
				    	<td>
						<b>@lang('repair::lang.device_model'):</b>
						</td>
						<td>
						{{optional($job_sheet->deviceModel)->name}}
						</td>
				    <td></td>
				    
				</tr>
				<tr>
				    <td>
				        <b>@lang('repair::lang.serial_no'):</b>
					</td>
					<td>{{$job_sheet->serial_no}}</td>
					<td></td>
						 
				</tr>
				<tr>
					<td>
						<b>
							@lang('sale.invoice_no'):
						</b>
					</td>
					<td>
						@if($job_sheet->invoices->count() > 0)
							@foreach($job_sheet->invoices as $invoice)
								{{$invoice->invoice_no}}
								@if (!$loop->last)
							        {{', '}}
							    @endif
							@endforeach
						@endif
					</td>
				</tr>
				<tr>
					<td >
						<b>
							@lang('repair::lang.estimated_cost'):
						</b>
					</td>
					<td>
						<span class="display_currency" data-currency_symbol="true">
							{{$job_sheet->estimated_cost}}
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<b>
							@lang('sale.status'):
						</b>
					</td>
					<td>
						{{optional($job_sheet->status)->name}}
					</td>
				</tr>
			
				
			
				@if($job_sheet->service_type == 'pick_up' || $job_sheet->service_type == 'on_site')
					<tr>
						<td colspan="3">
							<b>
								@lang('repair::lang.pick_up_on_site_addr'):
							</b> <br>
							{!!$job_sheet->pick_up_on_site_addr!!}
						</td>
					</tr>
				@endif
				
				<tr>
					<td colspan="3">
						<b>
						الحالة
						</b> <br>
						@php
							$product_condition = json_decode($job_sheet->product_condition, true);
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
							$defects = json_decode($job_sheet->defects, true);
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
					<td>
						<b>
							@lang('repair::lang.authorized_signature'):
						</b>
					</td>
					<td>
						<b>
							{{\Auth::user()->first_name}}
						</b>
					</td>
					<td>
						<b>
						
						</b>
					</td>
				</tr>
			</table>
           
		

             </div>
                                                                        <!-- <button id="btnPrint" class="hidden-print">Print</button>
                                                                        <script src="script.js"></script> -->
                  </body>
        </html>

<style type="text/css">
.f-8 {
	font-size: 8px !important;
}
body{
    font-size:larger;
}
td{
    font-size:21px;
}
@media print {
	* {
    	font-size: 12px;
    	font-family: 'Times New Roman';
    	word-break: break-all;
	}
	.f-8 {
		font-size: 8px !important;
	}

.headings{
	font-size: 16px;
	font-weight: 700;
	text-transform: uppercase;
}

.sub-headings{
	font-size: 15px;
	font-weight: 700;
}

.border-top{
    border-top: 1px solid #242424;
}
.border-bottom{
	border-bottom: 1px solid #242424;
}

.border-bottom-dotted{
	border-bottom: 1px dotted darkgray;
}

td.serial_number, th.serial_number{
	width: 5%;
    max-width: 5%;
}

td.description,
th.description {
    width: 35%;
    max-width: 35%;
    word-break: break-all;
}

td.quantity,
th.quantity {
    width: 15%;
    max-width: 15%;
    word-break: break-all;
}
td.unit_price, th.unit_price{
	width: 25%;
    max-width: 25%;
    word-break: break-all;
}

td.price,
th.price {
    width: 20%;
    max-width: 20%;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    width: 100%;
    max-width: 100%;
}

img {
    max-width: inherit;
    width: auto;
}

    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
.table-info {
	width: 100%;
}
.table-info tr:first-child td, .table-info tr:first-child th {
	padding-top: 8px;
}
.table-info th {
	text-align: left;
}
.table-info td {
	text-align: right;
}
.logo {
	float: left;
	width:35%;
	padding: 10px;
}

.text-with-image {
	float: left;
	width:65%;
}
.text-box {
	width: 100%;
	height: auto;
}
.m-0 {
	margin:0;
}
.textbox-info {
	clear: both;
}
.textbox-info p {
	margin-bottom: 0px
}
.flex-box {
	display: flex;
	width: 100%;
}
.flex-box p {
	width: 50%;
	margin-bottom: 0px;
	white-space: nowrap;
}

.table-f-12 th, .table-f-12 td {
	
	word-break: break-word;
}

.bw {
	word-break: break-word;
}
.bb-lg {
	border-bottom: 1px solid lightgray;
}
</style>