<?php

namespace Modules\Accounting\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\BusinessLocation;
use App\Transaction;
use Modules\Accounting\Entities\AccountingAccount;

class MapPaymentDeleted
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
    public function handle($event)
    {
        $payment = $event->transactionPayment;
        $accountingUtil = new \Modules\Accounting\Utils\AccountingUtil();
        $accountingUtil->deleteMap(null, $payment->id);
    }
}
