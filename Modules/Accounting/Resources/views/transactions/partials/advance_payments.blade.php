<div class="pos-tab-content">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('ap_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('ap_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
        <?php
        $util = new \App\Utils\Util();
        $payment_types= $util->payment_types();
        ?>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('ap_payment_type',  __('طريقة الدفع') . ':') !!}
                {!! Form::select('ap_payment_type', $payment_types, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
            </div>
        </div>
    </div>
    

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="{{$id}}">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('account.payment_ref_no')</th>
                                <th>@lang('account.invoice_ref_no')</th>
                                <th>@lang('sale.amount')</th>
                                <th>@lang('lang_v1.payment_method')</th>
                            
                                <th>@lang( 'lang_v1.description' )</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>