<?php
$accUtil=new \Modules\Accounting\Utils\AccountingUtil();
$localCurrency=session('business.currency_id');
$business_id=session('business.id');

?>
<center>
<table class="table table-bordered table-striped " id="table_incomelist">
    <thead>
        <tr class="">
            <th ><center>{{session('business.name')}}</center></th>
           <th>من : {{$start ?? '' }}</th>
           <th>الي : {{$end ?? '' }}</th>
           
        </tr>
        <tr>
            <th>@lang( 'user.name' )</th>
           
            <th>@lang( 'accounting::lang.account_type' )</th>
          
          
            <!-- <th>@lang( 'accounting::lang.primary_balance' )</th> -->
            <th>@lang( 'accounting::lang.primary_balance' )</th>
        </tr>
    </thead>
    <tbody>
        <?php $all_bal=0; ?>
        @foreach($accounts as $key => $account)
            <tr class="bg-gray">
                
                <td>{{$account->name}}</td>
           
                <td>@if(!empty($account->account_primary_type)){{__('accounting::lang.' . $account->account_primary_type)}}@endif</td>
                <td>
                    <?php $bal=0;
                    
                    ?>
                    @if(!empty($account->balance))
                        <?php $bal+=$accUtil->convertCurrency($business_id,$account->balance,$account->currency_id,$localCurrency); ?> 
                    @else
                      @if(count($account->child_accounts) > 0)
                       @foreach($account->child_accounts as $child_account)
                        @if(!empty($child_account->balance))
                            <?php $bal+=  $accUtil->convertCurrency($business_id,$child_account->balance,$child_account->currency_id,$localCurrency) ?> 
                        @endif
                       @endforeach
                       @endif
                    @endif
                    @format_currency($bal)
                    <?php $all_bal+=$bal; ?>
                    <input type="hidden" class="{{$account->account_primary_type}}" value="{{$bal}}">
                </td>
                <!-- <td></td> -->
                
            </tr>
            @if(isset($accounts[$key+1]))
            @if($accounts[$key+1]->account_primary_type != $account->account_primary_type)
            <tr>
              <td>المجموع</td> 
               <td></td> 
               <td>@format_currency($all_bal) <?php $all_bal=0; ?></td> 
            </tr>
            @endif
            @else
            <tr>
               <td>المجموع</td> 
               <td></td> 
               <td>@format_currency($all_bal) <?php $all_bal=0; ?></td> 
            </tr>
            @endif
            @if(count($account->child_accounts) > 0)

                @foreach($account->child_accounts as $child_account)
                   
                @endforeach
            @endif
            
        @endforeach

        @if(!$account_exist)
            <tr>
                <td colspan="8" class="text-center">
                    <h3>@lang( 'accounting::lang.no_accounts' )</h3>
                    <p>@lang( 'accounting::lang.add_default_accounts_help' )</p>
                    <a href="{{route('accounting.create-default-accounts')}}" class="btn btn-success btn-xs">@lang( 'accounting::lang.add_default_accounts' ) <i class="fas fa-file-import"></i></a>
                </td>
            </tr>
        @endif
        
    </tbody>
    <tfoot>
        <tr>
            <th>
                الدخل قبل الضريبة
                
            </th>
            <td></td>
            <td>
                <span class="total_before_tax_txt" data-orig-val="0">0</span>
                <input type="hidden" class="total_before_tax" vlaue="">
            </td>
        </tr>
        <tr>
            <th>
             الضريبة
                
            </th>
            <td>
                <span class="input_tax_txt" > </span>
                <input type="text" class="form-control tax_input input_number"  placeholder="نسبة الضريبة">
            </td>
            <td>
                <span class="tax_amount_input_txt" >قيمة الضريبة</span>
                <input type="hidden" class="form-control tax_amount_input input_number"  placeholder="نسبة الضريبة">
            </td>
        </tr>
        <tr>
            <th>
            صافي الدخل
                
            </th>
            <td></td>
            <td>
                 <span class="net_incom_txt" ></span>
                <input type="hidden" class="form-control net_incom input_number" vlaue="">
            </td>
            
        </tr>
    </tfoot>
</table>
</center>