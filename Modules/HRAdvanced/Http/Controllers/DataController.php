<?php

namespace Modules\HRAdvanced\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Utils\ModuleUtil;
use App\Utils\Util;
use Menu;
class DataController extends Controller
{
    public function superadmin_package()
    {
        return [
            [
                'name' => 'hr_advanced_module',
                'label' => __('hradvanced::lang.hr_advanced_module'),
                'default' => false,
            ],
        ];
    }

    /**
     * Adds cms menus
     *
     * @return null
     */
    public function modifyAdminMenu()
    {
        $business_id = session()->get('user.business_id');
        $module_util = new ModuleUtil();

        $is_accounting_enabled = (bool) $module_util->hasThePermissionInSubscription($business_id, 'hr_advanced_module');

        $commonUtil = new Util();
        $is_admin = $commonUtil->is_admin(auth()->user(), $business_id);

        if (true) {
            Menu::modify(
                'admin-sidebar-menu',
                function ($menu) {
                    $menu->url(action([\Modules\HRAdvanced\Http\Controllers\EmployeeController::class, 'index']), __('hradvanced::lang.hr_advanced_module'), ['icon' => 'fas fa-money-check fa', 'style' =>  'background-color: #D483D9;', 'active' => request()->segment(1) == 'hradvanced'])->order(50);
                }
            );
        }
    }
}
