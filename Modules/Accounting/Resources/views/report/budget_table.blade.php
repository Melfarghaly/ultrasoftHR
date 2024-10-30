<?php
$accUtil=new \Modules\Accounting\Utils\AccountingUtil();
$localCurrency=session('business.currency_id');
$business_id=session('business.id');

?>
<div class="row">
    <table class="table" id="parent_table">
        <tr>
            <th colspan="2" class="text-center"> ميزانية {{session('business.name')}}
                           من : {{$start ?? '' }}
                           الي : {{$end ?? '' }}</th>
        </tr>
        <tr>
            <td class="border-td">
                <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="table_incomelist">
                    <thead>
                        <tr class="">
                            <th colspan="3" class="text-center" >الأصــــــول</th>
                           
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
                         @if(in_array($account->account_primary_type,['asset']))
                            <tr class="bg-gray">
                                
                                <td>{{$account->name}} ({{$account->gl_code}})</td>
                           
                                <td>@if(!empty($account->account_primary_type)){{__('accounting::lang.' . $account->account_primary_type)}}@endif</td>
                                <td>
                                    <?php $bal=0;
                                    
                                    ?>
                                    @if(!empty($account->balance))
                                        <?php $bal+=$accUtil->convertCurrency($business_id,$account->balance,$account->currency_id,$localCurrency); ?> 
                                    @endif
                                      @if(count($account->child_accounts) > 0)
                                       @foreach($account->child_accounts as $child_account)
                                        @if(!empty($child_account->balance))
                                            <?php $bal+= $accUtil->convertCurrency($business_id,$child_account->balance,$child_account->currency_id,$localCurrency) ?> 
                                        @endif
                                       @endforeach
                                       @endif
                                   
                                    @format_currency($bal)
                                    <?php $all_bal+=$bal; ?>
                                    <input type="hidden" class="{{$account->account_primary_type}}" value="{{$bal}}">
                                </td>
                                <!-- <td></td> -->
                                
                            </tr>
                       
                           
                            
                          
                            @if(count($account->child_accounts) > 0)
                
                                @foreach($account->child_accounts as $child_account)
                                    <tr>
                                       
                                        <td style="padding-left:30px">{{$child_account->name}} ({{$child_account->gl_code}})</td>
                                        
                                        <td>@if(!empty($child_account->account_primary_type)){{__('accounting::lang.' . $child_account->account_primary_type)}}@endif</td>
                                        <td>@if(!empty($child_account->balance)) @format_currency($accUtil->convertCurrency($business_id,$child_account->balance  ,$child_account->currency_id,$localCurrency)) @endif</td>
                                        <!-- <td></td> -->
                                        
                                    </tr>
                                @endforeach
                            @endif
                         @endif    
                        @endforeach
                            <tr>
                              <td>المجموع</td> 
                               <td></td> 
                               <td>@format_currency($all_bal) <?php //$all_bal=0; ?></td> 
                            </tr>
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
                    
                </table>
                </div>
            </div>
            </td>
            <td  class="border-td">
                <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped " id="table_incomelist">
                    <thead>
                        <tr class="">
                            <th colspan="3" class="text-center">
                                الالتزامات وحقوق الملكية
                           </th>
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
                        @if(in_array($account->account_primary_type,['equity','liability']))
                            <tr class="bg-gray">
                                
                                <td>{{$account->name}} ({{$account->gl_code}})</td>
                           
                                <td>@if(!empty($account->account_primary_type)){{__('accounting::lang.' . $account->account_primary_type)}}@endif</td>
                                <td>
                                    <?php $bal=0;
                                    
                                    ?>
                                    @if(!empty($account->balance))
                                        <?php $bal+=$accUtil->convertCurrency($business_id,$account->balance,$account->currency_id,$localCurrency); ?> 
                                    @endif
                                      @if(count($account->child_accounts) > 0)
                                       @foreach($account->child_accounts as $child_account)
                                        @if(!empty($child_account->balance))
                                            <?php $bal+=  $accUtil->convertCurrency($business_id,$child_account->balance,$child_account->currency_id,$localCurrency) ?> 
                                        @endif
                                       @endforeach
                                       @endif
                                   
                                    @format_currency($bal)
                                    <?php $all_bal+=$bal; ?>
                                   
                                    <input type="hidden" class="{{$account->account_primary_type}}" value="{{$bal}}">
                                </td>
                                <!-- <td></td> -->
                
                            </tr>
                        
                            
                            @if(count($account->child_accounts) > 0)
                
                                @foreach($account->child_accounts as $child_account)
                                     <tr>
                                       
                                        <td style="padding-left:30px">{{$child_account->name}} ({{$child_account->gl_code}})</td>
                                        
                                        <td>@if(!empty($child_account->account_primary_type)){{__('accounting::lang.' . $child_account->account_primary_type)}}@endif</td>
                                        <td>@if(!empty($child_account->balance)) @format_currency($accUtil->convertCurrency($business_id,$child_account->balance  ,$child_account->currency_id,$localCurrency)) @endif</td>
                                        <!-- <td></td> -->
                                        
                                    </tr>
                                @endforeach
                            @endif
                         @endif   
                        @endforeach
                            <tr>
                               <td>المجموع</td> 
                               <td></td> 
                               <td>@format_currency($all_bal) <?php //$all_bal=0; ?></td> 
                            </tr>
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
                    
                </table>
                </div>
            </div>
            </td>
        </tr>
    </table>
</div>