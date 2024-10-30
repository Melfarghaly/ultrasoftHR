<?php
namespace Modules\Accounting\Listeners;

use App\Business;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ContactCreatedOrModified;
use Modules\Accounting\Entities\AccountingAccount;

class MapContactAccount
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
     * @param  ContactCreatedOrModified  $event
     * @return void
     */
    public function handle(ContactCreatedOrModified $event)
    {
        $contact = (object)$event->contact;
        if(!empty($contact))
        if (!AccountingAccount::where('contact_id', $contact->id)->exists()) {
            $business = Business::find($contact->business_id);

            if (!empty($business->supplier_parent_account) && $contact->type == 'supplier') {
                $this->createContactAccount($business->supplier_parent_account, $contact);
            }

            if (!empty($business->customer_parent_account) && $contact->type == 'customer') {
                $this->createContactAccount($business->customer_parent_account, $contact);
            }
        }
    }

    private function createContactAccount($parentAccountId, $contact)
    {
        $account = AccountingAccount::find($parentAccountId);

        if ($account) {
            $account->child_accounts()->create([
                'name' => $contact->name,
                'business_id'=>$contact->business_id,
                'account_primary_type' => $account->account_primary_type,
                'account_sub_type_id' => $account->account_sub_type_id,
                'detailed_type_id' => $account->detailed_type_id,
                'parent_account_id' => $account->id,
                'contact_id' => $contact->id,
                'status' => 'active',
                'created_by' => $contact->created_by,
            ]);
        }
    }
}
