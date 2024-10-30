<div class="pos-tab-content ">
    
    <div class="row">
       <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_return_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('sell_return_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
        <?php
        $util = new \App\Utils\Util();
        $payment_types= $util->payment_types();
        ?>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('payment_type',  __('طريقة الدفع') . ':') !!}
                {!! Form::select('payment_type', $payment_types, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped ajax_view" id="sell_return_table">
                    <thead>
                        <tr>
                            <th>@lang('messages.action')</th>
                            <th>@lang('messages.date')</th>
                            <th>@lang('sale.invoice_no')</th>
                            <th>@lang('lang_v1.parent_sale')</th>
                            <th>@lang('sale.customer_name')</th>
                            <th>@lang('sale.location')</th>
                            <th>@lang('purchase.payment_status')</th>
                            <th>@lang('sale.total_amount')</th>
                            <th>@lang('purchase.payment_due')</th>
                           
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td></td>
                            <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                            <td id="footer_payment_status_count_sr"></td>
                            <td><span class="display_currency" id="footer_sell_return_total" data-currency_symbol ="true"></span></td>
                            <td><span class="display_currency" id="footer_total_due_sr" data-currency_symbol ="true"></span></td>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>