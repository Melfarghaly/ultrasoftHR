<div class="pos-tab-content">
    <div class="row">
        @php
        $business=\App\Business::find(session('business.id'));
       // dd($business);
       $vat_account=\Modules\Accounting\Entities\AccountingAccount::find($business->vat_account_id);
       $vat_credit_account=\Modules\Accounting\Entities\AccountingAccount::find($business->vat_credit_account);
       $discount_credit=\Modules\Accounting\Entities\AccountingAccount::find($business->credit_account_id);
       $discount_debit=\Modules\Accounting\Entities\AccountingAccount::find($business->debit_account_id);

       $acc_arr=[];
       if(!empty($vat_account)){
            $acc_arr[$vat_account->id ]=$vat_account->name;
       }
       if(!empty($vat_credit_account)){
            $acc_arr[$vat_credit_account->id ]=$vat_credit_account->name;
       }
       if(!empty($discount_credit)){
            $acc_arr[$discount_credit->id ]=$discount_credit->name;
       }
       if(!empty($discount_debit)){
            $acc_arr[$discount_debit->id ]=$discount_debit->name;
       }
        @endphp
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-6"> 
                <div class="form-group">
                    {!! Form::label('payment_accountaa', __('الحساب الدائن للضريبة') . ':*' ) !!}
                    {!! Form::select('vat_account_id', $acc_arr, $business->vat_account_id ?? null, ['id'=>'payment_accountaa','class' => 'form-control accounts-dropdown','placeholder' => __('accounting::lang.payment_account'), 'required' => 'required']); !!}
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group">
                    {!! Form::label('payment_accountaa', __('الحساب المدين للضريبة') . ':*' ) !!}
                    {!! Form::select('vat_credit_account', $acc_arr, $business->vat_credit_account ?? null, ['id'=>'vat_credit_account','class' => 'form-control accounts-dropdown','placeholder' => __('accounting::lang.payment_account'), 'required' => 'required']); !!}
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group">
                    {!! Form::label('payment_accountaa', __('الحساب الدائن للخصم') . ':*' ) !!}
                    {!! Form::select('discount_debit_account', $acc_arr, $business->debit_account_id ?? null, ['id'=>'debit_account_id','class' => 'form-control accounts-dropdown','placeholder' => __('accounting::lang.payment_account'), 'required' => 'required']); !!}
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group">
                    {!! Form::label('payment_accountaa', __('الحساب المدين للخصم') . ':*' ) !!}
                    {!! Form::select('discount_credit_account', $acc_arr, $business->credit_account_id ?? null, ['id'=>'credit_account_id','class' => 'form-control accounts-dropdown','placeholder' => __('accounting::lang.payment_account'), 'required' => 'required']); !!}
                </div>
            </div>
            
        @endcomponent
    </div>
</div>
