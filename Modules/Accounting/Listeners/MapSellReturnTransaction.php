<?php

namespace Modules\Accounting\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SellReturnAdded;
use App\BusinessLocation;
use Modules\Accounting\Entities\AccountingAccount;

class MapSellReturnTransaction
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * 
     * @param  object  $event
     * @return void
     */
    public function handle(SellReturnAdded $event)
    {
        $transaction=$event->transaction;
        //dd($event);
        //get location setting and check if default is set or not, if set the proceed.
        $business_location = BusinessLocation::find($event->transaction->location_id); 
        $accounting_default_map = json_decode($business_location->accounting_default_map, true);

        $deposit_to = isset($accounting_default_map['sell_return']['deposit_to']) ? $accounting_default_map['sell_return']['deposit_to'] : null;
        
        $payment_account=AccountingAccount::where('contact_id',$transaction->contact_id)->first()->id ?? null;
        
        if(empty($payment_account))
            $payment_account = isset($accounting_default_map['sell_return']['payment_account']) ? $accounting_default_map['sell_return']['payment_account'] : null;

        //if purchase is deleted then delete the mapping
        if(isset($event->isDeleted) && $event->isDeleted){
            $accountingUtil = new \Modules\Accounting\Utils\AccountingUtil();
            $accountingUtil->deleteMap($event->transaction->id, null);
        } else {

            if(!is_null($deposit_to) && !is_null($payment_account)){

                $type = 'sell_return';
                $id = $event->transaction->id;
                $user_id = request()->session()->get('user.id');
                $business_id = $event->transaction->business_id;
                
                $accountingUtil = new \Modules\Accounting\Utils\AccountingUtil();
                $accountingUtil->saveMap($type, $id, $user_id, $business_id, $deposit_to, $payment_account);
            }
        }
    }
}
