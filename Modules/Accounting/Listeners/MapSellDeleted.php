<?php

namespace Modules\Accounting\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SellDeleted;
use App\BusinessLocation;
use Modules\Accounting\Entities\AccountingAccount;
class MapSellDeleted
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
    public function handle(SellDeleted $event)
    {
     
        $accountingUtil = new \Modules\Accounting\Utils\AccountingUtil();
        $accountingUtil->deleteMap($event->transaction->id, null);
    }
}
