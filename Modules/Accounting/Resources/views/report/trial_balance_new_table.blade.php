<?php
$accUtil=new \Modules\Accounting\Utils\AccountingUtil();
$localCurrency=session('business.currency_id');
$business_id=session('business.id');
?>
<table class="table table-bordered table-striped" id="trial_new">
    <thead>
        <tr>
            <th rowspan="2">@lang( 'accounting::lang.gl_code' )</th>
            <th rowspan="2">@lang( 'user.name' )</th>
            
            <th colspan="2"> الارصدة بداية المدة</th>
            <th colspan="2"> الحركة خلال الفترة  </th>
            <th colspan="2"> الارصدة نهاية المدة  </th>
        </tr>
        <tr>
           
            <td>مدين</td>
            <td>دائن</td>
            
            <td>مدين</td>
            <td>دائن</td>
            
            <td>مدين</td>
            <td>دائن</td>
            <!-- <th>@lang( 'accounting::lang.primary_balance' )</th> -->
          
         
        </tr>
    </thead>
    <tbody>
        @php
               $sum_credit_opening_bal  =0;
               $sum_debit_opening_bal   =0;
               $sum_credit_indate       =0;
               $sum_debit_indate        =0;
               $sum_end_row_credit      =0;
               $sum_end_row_debit       =0;
               
        @endphp
        @foreach($accounts as $account)
            <tr class="bg-gray">
                 <td>{{$account->gl_code}}</td>
                <td>{{$account->name}}</td>
                <td></td>
                <td></td>
                
                <td></td>
                <td></td>
                
                <td></td>
                <td></td>
                
                
            </tr>
            @if(count($account->child_accounts) > 0)
                
                @foreach($account->child_accounts as $child_account)
                @php
                   
                @endphp
                    <tr>
                        <td>{{$child_account->gl_code}}</td>
                        <td style="padding-left:30px">{{$child_account->name}}</td>
                        <!--opening balance-->
                        <td><?php $sum_debit_opening_bal  += $child_account->debit_opening_bal ?>   @format_currency($child_account->debit_opening_bal)</td>
                        <td><?php $sum_credit_opening_bal += $child_account->credit_opening_bal ?>  @format_currency($child_account->credit_opening_bal)</td>
                        
                        
                        <td><?php $sum_debit_indate       +=$child_account->debit_indate ?>         @format_currency($child_account->debit_indate)</td>
                        <td><?php $sum_credit_indate      += $child_account->credit_indate ?>       @format_currency($child_account->credit_indate)</td>
                        @php
                         $credit_row = $child_account->credit_opening_bal + $child_account->credit_indate;
                         $debit_row  = $child_account->debit_opening_bal + $child_account->debit_indate ;
                       
                             $end_row =    $debit_row - $credit_row;
                         
                         
                        @endphp
                        <td>
                            @if( $end_row > 0 )    
                             @format_currency(abs($end_row))
                              <?php  $sum_end_row_debit += abs($end_row)  ?>
                            @endif
                        </td>
                        <td>
                        @if( $end_row < 0 )    
                        @format_currency( abs($end_row)  )
                        <?php  $sum_end_row_credit += abs($end_row)   ?>
                        @endif
                        </td>
                        
                    </tr>
                @endforeach
            @endif
            
        @endforeach

      <tr>
            <td colspan="2"> الاجمالي</td>
             <td>@format_currency($sum_debit_opening_bal)</td>
            <td>@format_currency($sum_credit_opening_bal)</td>
           
             <td>@format_currency($sum_debit_indate)</td>
            <td>@format_currency($sum_credit_indate)</td>
           
            <td>@format_currency($sum_end_row_debit)</td>
            <td>@format_currency($sum_end_row_credit)</td>
            
        </tr>
    </tbody>
    <tfoot>
        
    </tfoot>
</table>