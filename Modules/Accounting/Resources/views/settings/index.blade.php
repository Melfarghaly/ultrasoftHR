@extends('layouts.app')

@section('title', __('messages.settings'))

@section('content')

@include('accounting::layouts.nav')
<style>
	.automap{
		background-color: #f0f0f0;
		
	}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>@lang( 'messages.settings' )</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#account_setting" data-toggle="tab" aria-expanded="true">
							@lang('accounting::lang.account_setting') / @lang('accounting::lang.map_transactions')
						</a>
					</li>

					<li>
						<a href="#sub_type_tab" data-toggle="tab" aria-expanded="true">
							@lang('accounting::lang.account_sub_type')
						</a>
					</li>
					<li>
						<a href="#detail_type_tab" data-toggle="tab" aria-expanded="true">
							@lang('accounting::lang.detail_type')
						</a>
					</li>
				</ul>
				<div class="tab-content">

					<div class="tab-pane active" id="account_setting">
						{!! Form::open(['action' => '\Modules\Accounting\Http\Controllers\SettingsController@saveSettings',
						'method' => 'post']) !!}
						<div class="row mb-12">
							<div class="col-md-4">
								<button class="btn btn-danger accounting_reset_data" data-href="{{action([\Modules\Accounting\Http\Controllers\SettingsController::class, 'resetData'])}}">
									@lang('accounting::lang.reset_data')
								</button>
							</div>
							<div class="col-md-4">
							<button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right tw-m-2 btn-danger " id="exampleModal_btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
								@lang("accounting::lang.transfer_balance") 
							</button>
							</div>
						</div>
						<br>
@php 
$supplier_parent_account =  \Modules\Accounting\Entities\AccountingAccount::find($business->supplier_parent_account) ??  null;
$customer_parent_account =  \Modules\Accounting\Entities\AccountingAccount::find($business->customer_parent_account) ??  null;

@endphp
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('supplier_parent_account', __('accounting::lang.supplier_parent_account') . ':') !!}
									{!! Form::select('supplier_parent_account',!empty($supplier_parent_account) ? [$supplier_parent_account->id=>$supplier_parent_account->name ] : [] ,$business->supplier_parent_account,
									['class' => 'form-control accounts-dropdown', 'id' => 'supplier_parent_account']); !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('customer_parent_account', __('accounting::lang.customer_parent_account') . ':') !!}
									{!! Form::select('customer_parent_account',!empty($customer_parent_account) ? [$customer_parent_account->id=>$customer_parent_account->name ] : [],$business->customer_parent_account,
									['class' => 'form-control accounts-dropdown', 'id' => 'customer_parent_account']); !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('journal_entry_prefix', __('accounting::lang.journal_entry_prefix') . ':') !!}
									{!! Form::text('journal_entry_prefix',!empty($accounting_settings['journal_entry_prefix'])?
									$accounting_settings['journal_entry_prefix'] : '',
									['class' => 'form-control ', 'id' => 'journal_entry_prefix']); !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('transfer_prefix', __('accounting::lang.transfer_prefix') . ':') !!}
									{!! Form::text('transfer_prefix',!empty($accounting_settings['transfer_prefix'])?
									$accounting_settings['transfer_prefix'] : '',
									['class' => 'form-control ', 'id' => 'transfer_prefix']); !!}
								</div>
							</div>
						</div>

						<hr />

						<h3>@lang('accounting::lang.map_transactions') @show_tooltip(__('accounting::lang.map_transactions_help'))</h3>

						@foreach($business_locations as $business_location)
						@component('components.widget', ['title' => $business_location->name,'class'=>'automap2'])

						@php
						$default_map = json_decode($business_location->accounting_default_map, true);
						//print_r($default_map);exit;

						$sale_payment_account = isset($default_map['sale']['payment_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sale']['payment_account']) : null;

						$sale_deposit_to = isset($default_map['sale']['deposit_to']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sale']['deposit_to']) : null;
						
						$sale_tax_account = isset($default_map['sale']['tax_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sale']['tax_account']) : null;
						$sale_discount_account = isset($default_map['sale']['discount_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sale']['discount_account']) : null;

						$sales_payments_payment_account = isset($default_map['sell_payment']['payment_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sell_payment']['payment_account']) : null;

						$sales_payments_deposit_to = isset($default_map['sell_payment']['deposit_to']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['sell_payment']['deposit_to']) : null;

						$purchases_payment_account = isset($default_map['purchases']['payment_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchases']['payment_account']) : null;

						$purchases_deposit_to = isset($default_map['purchases']['deposit_to']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchases']['deposit_to']) : null;
						
						$purchase_tax_account = isset($default_map['purchases']['tax_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchases']['tax_account']) : null;
						$purchase_discount_account = isset($default_map['purchases']['discount_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchases']['discount_account']) : null;

						$purchase_payments_payment_account = isset($default_map['purchase_payments']['payment_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchase_payments']['payment_account']) : null;

						$purchase_payments_deposit_to = isset($default_map['purchase_payments']['deposit_to']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['purchase_payments']['deposit_to']) : null;

						$expense_payment_account = isset($default_map['expenses']['payment_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['expenses']['payment_account']) : null;
						$expense_deposit_to = isset($default_map['expenses']['deposit_to']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['expenses']['deposit_to']) : null;
						$expense_tax_account = isset($default_map['expenses']['tax_account']) ? \Modules\Accounting\Entities\AccountingAccount::find($default_map['expenses']['tax_account']) : null;

						@endphp

						<strong>@lang('sale.sale')</strong>

						<div class="row automap">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('payment_account', __('accounting::lang.payment_account') . ':' ) !!}
									{!! Form::select('payment_account', !is_null($sale_payment_account) ? [$sale_payment_account->id => $sale_payment_account->name] : [], $sale_payment_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.payment_account'), 'name' => "accounting_default_map[$business_location->id][sale][payment_account]",
									'id' => $business_location->id . 'sale_payment_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('deposit_to', __('accounting::lang.deposit_to') . ':' ) !!}
									{!! Form::select('deposit_to', !is_null($sale_deposit_to) ?
									[$sale_deposit_to->id => $sale_deposit_to->name] : [], $sale_deposit_to->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.deposit_to'), 'name' => "accounting_default_map[$business_location->id][sale][deposit_to]",
									'id' => $business_location->id . '_sale_deposit_to']); !!}
								</div>
							</div>
						
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('tax_account', __('accounting::lang.tax_account') . ':' ) !!}
									{!! Form::select('tax_account', !is_null($sale_tax_account) ? [$sale_tax_account->id => $sale_tax_account->name] : [], $sale_tax_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.tax_account'), 'name' => "accounting_default_map[$business_location->id][sale][tax_account]", 'id' => $business_location->id . '_sale_tax_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('discount_account', __('accounting::lang.discount_account') . ':' ) !!}
									{!! Form::select('discount_account', !is_null($sale_discount_account) ? [$sale_discount_account->id => $sale_discount_account->name] : [], $sale_discount_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.discount_account'), 'name' => "accounting_default_map[$business_location->id][sale][discount_account]", 'id' => $business_location->id . '_sale_discount_account']); !!}
								</div>
							</div>
						</div>
						<hr>

						<strong>@lang('accounting::lang.sales_payments')</strong>

						<div class="row automap">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('payment_account', __('accounting::lang.payment_account') . ':' ) !!}
									{!! Form::select('payment_account', !is_null($sales_payments_payment_account) ? [$sales_payments_payment_account->id => $sales_payments_payment_account->name] : [], $sales_payments_payment_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.payment_account'), 'name' => "accounting_default_map[$business_location->id][sell_payment][payment_account]", 'id' => $business_location->id . 'sales_payments_payment_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('deposit_to', __('accounting::lang.deposit_to') . ':' ) !!}
									{!! Form::select('deposit_to', !is_null($sales_payments_deposit_to) ?
									[$sales_payments_deposit_to->id => $sales_payments_deposit_to->name] : [], $sales_payments_deposit_to->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.deposit_to'), 'name' => "accounting_default_map[$business_location->id][sell_payment][deposit_to]",
									'id' => $business_location->id . 'sales_payments_deposit_to'
									]); !!}
								</div>
							</div>
						</div>

						<hr>
						<strong>@lang('purchase.purchases')</strong>

						<div class="row automap">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('payment_account', __('accounting::lang.payment_account') . ':' ) !!}
									{!! Form::select('payment_account', !is_null($purchases_payment_account) ? [$purchases_payment_account->id => $purchases_payment_account->name] : [], $purchases_payment_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.payment_account'), 'name' => "accounting_default_map[$business_location->id][purchases][payment_account]",
									'id' => $business_location->id . 'purchases_payment_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('deposit_to', __('accounting::lang.deposit_to') . ':' ) !!}
									{!! Form::select('deposit_to', !is_null($purchases_deposit_to) ?
									[$purchases_deposit_to->id => $purchases_deposit_to->name] : [], $purchases_deposit_to->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.deposit_to'), 'name' => "accounting_default_map[$business_location->id][purchases][deposit_to]",
									'id' => $business_location->id . '_purchases_deposit_to']); !!}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('tax_account', __('accounting::lang.tax_account') . ':' ) !!}
									{!! Form::select('tax_account', !is_null($purchase_tax_account) ? [$purchase_tax_account->id => $purchase_tax_account->name] : [], $purchase_tax_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.tax_account'), 'name' => "accounting_default_map[$business_location->id][purchases][tax_account]", 'id' => $business_location->id . '_purchase_tax_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('discount_account', __('accounting::lang.discount_account') . ':' ) !!}
									{!! Form::select('discount_account', !is_null($purchase_discount_account) ? [$purchase_discount_account->id => $purchase_discount_account->name] : [], $purchase_discount_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.discount_account'), 'name' => "accounting_default_map[$business_location->id][purchases][discount_account]", 'id' => $business_location->id . '_purchase_discount_account']); !!}
								</div>
							</div>

						</div>

						<hr>
						<strong>@lang('accounting::lang.purchase_payments')</strong>

						<div class="row automap">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('payment_account', __('accounting::lang.payment_account') . ':' ) !!}
									{!! Form::select('payment_account', !is_null($purchase_payments_payment_account) ? [$purchase_payments_payment_account->id => $purchase_payments_payment_account->name] : [], $purchase_payments_payment_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.payment_account'), 'name' => "accounting_default_map[$business_location->id][purchase_payment][payment_account]",
									'id' => $business_location->id . 'purchase_payments_payment_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('deposit_to', __('accounting::lang.deposit_to') . ':' ) !!}
									{!! Form::select('deposit_to', !is_null($purchase_payments_deposit_to) ?
									[$purchase_payments_deposit_to->id => $purchase_payments_deposit_to->name] : [], $purchase_payments_deposit_to->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.deposit_to'), 'name' => "accounting_default_map[$business_location->id][purchase_payment][deposit_to]",
									'id' => $business_location->id . '_purchase_payments_deposit_to']); !!}
								</div>
							</div>
						</div>
						<hr>
						<strong>@lang('accounting::lang.expenses')</strong>

						<div class="row automap">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('payment_account', __('accounting::lang.payment_account') . ':' ) !!}
									{!! Form::select('payment_account', !is_null($expense_payment_account) ? [$expense_payment_account->id => $expense_payment_account->name] : [], $expense_payment_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.payment_account'), 'name' => "accounting_default_map[$business_location->id][expenses][payment_account]", 'id' => $business_location->id . '_expense_payment_account']); !!}
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('deposit_to', __('accounting::lang.deposit_to') . ':' ) !!}
									{!! Form::select('deposit_to', !is_null($expense_deposit_to) ? [$expense_deposit_to->id => $expense_deposit_to->name] : [], $expense_deposit_to->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.deposit_to'), 'name' => "accounting_default_map[$business_location->id][expenses][deposit_to]", 'id' => $business_location->id . '_expense_deposit_to']); !!}
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! Form::label('tax_account', __('accounting::lang.tax_account') . ':' ) !!}
									{!! Form::select('tax_account', !is_null($expense_tax_account) ? [$expense_tax_account->id => $expense_tax_account->name] : [], $expense_tax_account->id ?? null, ['class' => 'form-control accounts-dropdown width-100','placeholder' => __('accounting::lang.tax_account'), 'name' => "accounting_default_map[$business_location->id][expenses][tax_account]", 'id' => $business_location->id . '_expense_tax_account']); !!}
								</div>
							</div>
						</div>

						@endcomponent
						@endforeach

						<div class="row">
							<div class="col-md-12">
								<div class="form-group pull-right">
									{{Form::submit(__('messages.update'), ['class'=>"btn btn-primary"])}}
								</div>
							</div>
						</div>
						{!! Form::close() !!}

					</div>



					<div class="tab-pane" id="sub_type_tab">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-primary pull-right" id="add_account_sub_type">
									<i class="fas fa-plus"></i> @lang('messages.add')
								</button>
							</div>
							<div class="col-md-12">
								<br>
								<table class="table table-bordered table-striped" id="account_sub_type_table">
									<thead>
										<tr>
											<th>
												@lang('accounting::lang.account_sub_type')
											</th>
											<th>
												@lang('accounting::lang.account_type')
											</th>
											<th>
												@lang('messages.action')
											</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="detail_type_tab">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-primary pull-right" id="add_detail_type">
									<i class="fas fa-plus"></i> @lang('messages.add')
								</button>
							</div>
							<div class="col-md-12">
								<br>
								<table class="table table-striped" id="detail_type_table" style="width: 100%;">
									<thead>
										<tr>
											<th>
												@lang('accounting::lang.detail_type')
											</th>
											<th>
												@lang('accounting::lang.parent_type')
											</th>
											<th>
												@lang('lang_v1.description')
											</th>
											<th>
												@lang('messages.action')
											</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('accounting::account_type.create')
<div class="modal fade" id="edit_account_type_modal" tabindex="-1" role="dialog">
</div>

@stop
@include('accounting::partials.transfer_balance',['account_id'=>0])

@section('javascript')

@include('accounting::accounting.common_js')

<script type="text/javascript">
	$(document).ready(function() {
		account_sub_type_table = $('#account_sub_type_table').DataTable({
			processing: true,
			serverSide: true,
			ajax: "{{action([\Modules\Accounting\Http\Controllers\AccountTypeController::class, 'index'])}}?account_type=sub_type",
			columnDefs: [{
				targets: [2],
				orderable: false,
				searchable: false,
			}, ],
			columns: [{
					data: 'name',
					name: 'name'
				},
				{
					data: 'account_primary_type',
					name: 'account_primary_type'
				},
				{
					data: 'action',
					name: 'action'
				},
			],
		});

		detail_type_table = $('#detail_type_table').DataTable({
			processing: true,
			serverSide: true,
			ajax: "{{action([\Modules\Accounting\Http\Controllers\AccountTypeController::class, 'index'])}}?account_type=detail_type",
			columnDefs: [{
				targets: 3,
				orderable: false,
				searchable: false,
			}, ],
			columns: [{
					data: 'name',
					name: 'name'
				},
				{
					data: 'parent_type',
					name: 'parent_type'
				},
				{
					data: 'description',
					name: 'description'
				},
				{
					data: 'action',
					name: 'action'
				},
			],
		});

		$('#add_account_sub_type').click(function() {
			$('#account_type').val('sub_type')
			$('#account_type_title').text("{{__('accounting::lang.add_account_sub_type')}}");
			$('#description_div').addClass('hide');
			$('#parent_id_div').addClass('hide');
			$('#account_type_div').removeClass('hide');
			$('#create_account_type_modal').modal('show');
		});

		$('#add_detail_type').click(function() {
			$('#account_type').val('detail_type')
			$('#account_type_title').text("{{__('accounting::lang.add_detail_type')}}");
			$('#description_div').removeClass('hide');
			$('#parent_id_div').removeClass('hide');
			$('#account_type_div').addClass('hide');
			$('#create_account_type_modal').modal('show');
		})
	});
	$(document).on('hidden.bs.modal', '#create_account_type_modal', function(e) {
		$('#create_account_type_form')[0].reset();
	})
	$(document).on('submit', 'form#create_account_type_form', function(e) {
		e.preventDefault();
		var form = $(this);
		var data = form.serialize();

		$.ajax({
			method: 'POST',
			url: $(this).attr('action'),
			dataType: 'json',
			data: data,
			success: function(result) {
				if (result.success == true) {
					$('#create_account_type_modal').modal('hide');
					toastr.success(result.msg);
					if (result.data.account_type == 'sub_type') {
						account_sub_type_table.ajax.reload();
					} else {
						detail_type_table.ajax.reload();
					}
					$('#create_account_type_form').find('button[type="submit"]').attr('disabled', false);
				} else {
					toastr.error(result.msg);
				}
			},
		});
	});

	$(document).on('submit', 'form#edit_account_type_form', function(e) {
		e.preventDefault();
		var form = $(this);
		var data = form.serialize();

		$.ajax({
			method: 'PUT',
			url: $(this).attr('action'),
			dataType: 'json',
			data: data,
			success: function(result) {
				if (result.success == true) {
					$('#edit_account_type_modal').modal('hide');
					toastr.success(result.msg);
					if (result.data.account_type == 'sub_type') {
						account_sub_type_table.ajax.reload();
					} else {
						detail_type_table.ajax.reload();
					}

				} else {
					toastr.error(result.msg);
				}
			},
		});
	});

	$(document).on('click', 'button.delete_account_type_button', function() {
		swal({
			title: LANG.sure,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		}).then(willDelete => {
			if (willDelete) {
				var href = $(this).data('href');
				var data = $(this).serialize();

				$.ajax({
					method: 'DELETE',
					url: href,
					dataType: 'json',
					data: data,
					success: function(result) {
						if (result.success == true) {
							toastr.success(result.msg);
							account_sub_type_table.ajax.reload();
							detail_type_table.ajax.reload();
						} else {
							toastr.error(result.msg);
						}
					},
				});
			}
		});
	});

	$(document).on('click', 'button.accounting_reset_data', function() {
		swal({
			title: LANG.sure,
			icon: 'warning',
			text: "@lang('accounting::lang.reset_help_txt')",
			buttons: true,
			dangerMode: true,
		}).then(willDelete => {
			if (willDelete) {
				var href = $(this).data('href');
				window.location.href = href;
			}
		});
	});
</script>

@endsection