<?php

namespace Modules\Accounting\Http\Controllers;

use App\Utils\ModuleUtil;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccountingAccount;
use Modules\Accounting\Entities\AccountingAccountsTransaction;
use Modules\Accounting\Entities\AccountingAccountType;
use Modules\Accounting\Utils\AccountingUtil;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CoaController extends Controller
{
    protected $accountingUtil;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(AccountingUtil $accountingUtil, ModuleUtil $moduleUtil)
    {
        $this->accountingUtil = $accountingUtil;
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');

        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        $account_types = AccountingAccountType::accounting_primary_type();

        foreach ($account_types as $k => $v) {
            $account_types[$k] = $v['label'];
        }
        // dd($account_types) ;
        if (request()->ajax()) {
            $balance_formula = $this->accountingUtil->balanceFormula('AA');

            $query = AccountingAccount::where('business_id', $business_id)
                                ->whereNull('parent_account_id')
                                ->with(['child_accounts' => function ($query) use ($balance_formula) {
                                    $query->select([DB::raw("(SELECT $balance_formula from accounting_accounts_transactions AS AAT
                                        JOIN accounting_accounts AS AA ON AAT.accounting_account_id = AA.id
                                        WHERE AAT.accounting_account_id = accounting_accounts.id) AS balance"), 'accounting_accounts.*']);
                                },
                                    'child_accounts.detail_type', 'detail_type', 'account_sub_type',
                                    'child_accounts.account_sub_type', ])
                                ->select([DB::raw("(SELECT $balance_formula
                                    FROM accounting_accounts_transactions AS AAT 
                                    JOIN accounting_accounts AS AA ON AAT.accounting_account_id = AA.id
                                    WHERE AAT.accounting_account_id = accounting_accounts.id) AS balance"),
                                    'accounting_accounts.*', ]);

            if (! empty(request()->input('account_type'))) {
                $query->where('accounting_accounts.account_primary_type', request()->input('account_type'));
            }
            if (! empty(request()->input('status'))) {
                $query->where('accounting_accounts.status', request()->input('status'));
            }

            $accounts = $query->get();

            $account_exist = AccountingAccount::where('business_id', $business_id)->exists();

            if (request()->input('view_type') == 'table') {
                return view('accounting::chart_of_accounts.accounts_table')
                        ->with(compact('accounts', 'account_exist'));
            } else {
                $account_sub_types = AccountingAccountType::where('account_type', 'sub_type')
                                            ->where(function ($q) use ($business_id) {
                                                $q->whereNull('business_id')
                                                    ->orWhere('business_id', $business_id);
                                            })
                                            ->get();

                return view('accounting::chart_of_accounts.accounts_tree')
                ->with(compact('accounts', 'account_exist', 'account_types', 'account_sub_types'));
            }
        }

        return view('accounting::chart_of_accounts.index')->with(compact('account_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $account_types = AccountingAccountType::accounting_primary_type();

            return view('accounting::chart_of_accounts.create')->with(compact('account_types'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createDefaultAccounts()
    {
        //check no accounts
        $business_id = request()->session()->get('user.business_id');

        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        $user_id = request()->session()->get('user.id');

        $default_accounts = array (
            0 => 
            array (
              'name' => 'الحسابات الدائنة (A / P)',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 6,
              'detail_type_id' => 58,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            1 => 
            array (
              'name' => 'بطاقة الائتمان',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 7,
              'detail_type_id' => 59,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            2 => 
            array (
              'name' => 'مصاريف الأجور',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 140,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            3 => 
            array (
              'name' => 'المصاريف الخدمية',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 149,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            4 => 
            array (
              'name' => 'خسارة غير محققة في الأوراق المالية ، بعد خصم الضرائب',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 12,
              'detail_type_id' => 113,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            5 => 
            array (
              'name' => 'الأموال غير المودعة',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 29,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            6 => 
            array (
              'name' => 'الدخل غير المصنف',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => 103,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            7 => 
            array (
              'name' => 'مصاريف غير مصنفة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 138,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            8 => 
            array (
              'name' => 'الأصول غير المصنفة',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 26,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            9 => 
            array (
              'name' => 'دخل المدفوعات النقدية غير المطبقة',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => 105,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            10 => 
            array (
              'name' => 'مصاريف السفر - مصاريف البيع',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '147',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            11 => 
            array (
              'name' => 'مصاريف السفر - مصاريف عمومية وإدارية',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '146',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            12 => 
            array (
              'name' => 'نفقات المؤن',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 145,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            13 => 
            array (
              'name' => 'المقاولون من الباطن - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            14 => 
            array (
              'name' => 'ادوات مكتبيه والطباعه',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '137',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            15 => 
            array (
              'name' => 'الخصم قصير الأجل',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 69,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            16 => 
            array (
              'name' => 'مصاريف الشحن والتسليم',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 143,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            17 => 
            array (
              'name' => 'رأس المال',
              'business_id' => $business_id,
              'account_primary_type' => 'equity',
              'account_sub_type_id' => 10,
              'detail_type_id' => 95,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            18 => 
            array (
              'name' => 'مبيعات دخل المنتج',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => 103,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            19 => 
            array (
              'name' => 'مبيعات - جملة',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => '102',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            20 => 
            array (
              'name' => 'مبيعات التجزئة',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => '101',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            21 => 
            array (
              'name' => 'مبيعات',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => 103,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            22 => 
            array (
              'name' => 'الإيرادات - عام',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => '100',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            23 => 
            array (
              'name' => 'الأرباح المحتجزة',
              'business_id' => $business_id,
              'account_primary_type' => 'equity',
              'account_sub_type_id' => 10,
              'detail_type_id' => 94,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            24 => 
            array (
              'name' => 'إصلاح وصيانة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 142,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            25 => 
            array (
              'name' => 'مدفوعات الإيجار أو الإيجار',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 141,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            26 => 
            array (
              'name' => 'تناقضات المصالحة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 15,
              'detail_type_id' => 153,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            27 => 
            array (
              'name' => 'المشتريات',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 144,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            28 => 
            array (
              'name' => 'الممتلكات والآلات والمعدات',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 4,
              'detail_type_id' => 42,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            29 => 
            array (
              'name' => 'النفقات المدفوعة مسبقا',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 27,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            30 => 
            array (
              'name' => 'المطلوبات الرواتب',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 71,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            31 => 
            array (
              'name' => 'نفقات الأجور',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 140,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            32 => 
            array (
              'name' => 'مقاصة الرواتب',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 70,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            33 => 
            array (
              'name' => 'التكاليف العامة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            34 => 
            array (
              'name' => 'أنواع أخرى من المصاريف - مصاريف الإعلان',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '119',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            35 => 
            array (
              'name' => 'مصاريف بيع أخرى',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 139,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            36 => 
            array (
              'name' => 'إيرادات (مصروفات) تشغيل أخرى',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 12,
              'detail_type_id' => 111,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            37 => 
            array (
              'name' => 'مصاريف عمومية وإدارية أخرى',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '137',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            38 => 
            array (
              'name' => 'الدخل الشامل الآخر',
              'business_id' => $business_id,
              'account_primary_type' => 'equity',
              'account_sub_type_id' => 10,
              'detail_type_id' => 87,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            39 => 
            array (
              'name' => 'أخرى - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            40 => 
            array (
              'name' => 'نفقات المكتب',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => '137',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            41 => 
            array (
              'name' => 'وجبات الطعام والترفيه',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 137,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            42 => 
            array (
              'name' => 'المواد - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            43 => 
            array (
              'name' => 'تعويضات الإدارة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 135,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            44 => 
            array (
              'name' => 'خسارة في التخلص من الأصول',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 12,
              'detail_type_id' => 108,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            45 => 
            array (
              'name' => 'خسارة من العمليات المتوقفة ، صافية من الضريبة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 134,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            46 => 
            array (
              'name' => 'استثمارات طويلة الأجل',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 5,
              'detail_type_id' => 54,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            47 => 
            array (
              'name' => 'دين طويل الأمد',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 9,
              'detail_type_id' => 78,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            48 => 
            array (
              'name' => 'المطلوبات المتعلقة بالموجودات المحتفظ بها للبيع',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 9,
              'detail_type_id' => 77,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            49 => 
            array (
              'name' => 'الرسوم القانونية والمهنية',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 133,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            50 => 
            array (
              'name' => 'أصل المخزون',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 21,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            51 => 
            array (
              'name' => 'المخزون',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 21,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            52 => 
            array (
              'name' => 'إيرادات الفوائد',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 12,
              'detail_type_id' => 107,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            53 => 
            array (
              'name' => 'مصروفات الفوائد',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 132,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            54 => 
            array (
              'name' => 'غير الملموسة',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 5,
              'detail_type_id' => 51,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            55 => 
            array (
              'name' => 'التأمين - المسؤولية',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 131,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            56 => 
            array (
              'name' => 'التأمين - عام',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 131,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            57 => 
            array (
              'name' => 'التأمين - الإعاقة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 131,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            58 => 
            array (
              'name' => 'ضريبة الدخل المستحقة',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 65,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            59 => 
            array (
              'name' => 'مصروف ضريبة الدخل',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 130,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            60 => 
            array (
              'name' => 'شهرة المحل',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 5,
              'detail_type_id' => 50,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            61 => 
            array (
              'name' => 'الشحن والتسليم - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            62 => 
            array (
              'name' => 'حقوق الملكية في أرباح الشركات التابعة',
              'business_id' => $business_id,
              'account_primary_type' => 'equity',
              'account_sub_type_id' => 10,
              'detail_type_id' => 84,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            63 => 
            array (
              'name' => 'تأجير المعدات',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 128,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            64 => 
            array (
              'name' => 'الرسوم والاشتراكات',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 127,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            65 => 
            array (
              'name' => 'أرباح مستحقة الدفع',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 64,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            66 => 
            array (
              'name' => 'توزيعات الارباح',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 12,
              'detail_type_id' => 106,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            67 => 
            array (
              'name' => 'توزيعات الأرباح المصروفة',
              'business_id' => $business_id,
              'account_primary_type' => 'equity',
              'account_sub_type_id' => 10,
              'detail_type_id' => 83,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            68 => 
            array (
              'name' => 'الخصومات الممنوحة - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            69 => 
            array (
              'name' => 'العمالة المباشرة - التكلفة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            70 => 
            array (
              'name' => 'الأصول الضريبية المؤجلة',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 5,
              'detail_type_id' => 49,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            71 => 
            array (
              'name' => 'تكلفة المبيعات',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '118',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            72 => 
            array (
              'name' => 'العمولات والرسوم',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 125,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            73 => 
            array (
              'name' => 'التغيير في المخزون - COS',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 13,
              'detail_type_id' => '114',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            74 => 
            array (
              'name' => 'النقد والنقد المعادل',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 3,
              'detail_type_id' => 31,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            75 => 
            array (
              'name' => 'دخل حساب قابل للفوترة',
              'business_id' => $business_id,
              'account_primary_type' => 'income',
              'account_sub_type_id' => 11,
              'detail_type_id' => 103,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            76 => 
            array (
              'name' => 'الرسوم البنكية',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 123,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            77 => 
            array (
              'name' => 'الديون المعدومة',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 122,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            78 => 
            array (
              'name' => 'الأصول المتاحة للبيع (قصيرة الأجل)',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 18,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            79 => 
            array (
              'name' => 'موجودات محتفظ بها للبيع',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 5,
              'detail_type_id' => 48,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            80 => 
            array (
              'name' => 'مصاريف الاستهلاك',
              'business_id' => $business_id,
              'account_primary_type' => 'expenses',
              'account_sub_type_id' => 14,
              'detail_type_id' => 120,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            81 => 
            array (
              'name' => 'التسامح في الديون المتعثرة',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 2,
              'detail_type_id' => 17,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            82 => 
            array (
              'name' => 'الاستهلاك المتراكم على الممتلكات والمنشآت والمعدات',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 4,
              'detail_type_id' => 38,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            83 => 
            array (
              'name' => 'الخصوم المستحقة غير المتداولة',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 9,
              'detail_type_id' => 76,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            84 => 
            array (
              'name' => 'مسؤوليات متراكمة',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 8,
              'detail_type_id' => 60,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            85 => 
            array (
              'name' => 'الإجازة المستحقة الدفع',
              'business_id' => $business_id,
              'account_primary_type' => 'liability',
              'account_sub_type_id' => 9,
              'detail_type_id' => 75,
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
            86 => 
            array (
              'name' => 'حسابات القبض (A / R)',
              'business_id' => $business_id,
              'account_primary_type' => 'asset',
              'account_sub_type_id' => 1,
              'detail_type_id' => '16',
              'status' => 'active',
              'created_by' => $user_id,
              'created_at' => \Carbon::now(),
              'updated_at' => \Carbon::now(),
            ),
        );

        if (AccountingAccount::where('business_id', $business_id)->doesntExist()) {
            AccountingAccount::insert($default_accounts);
        }

        //redirect back
        $output = ['success' => 1,
            'msg' => __('lang_v1.added_success'),
        ];

        return redirect()->back()->with('status', $output);
    }

    public function getAccountDetailsType()
    {
        $business_id = request()->session()->get('user.business_id');

        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $account_type_id = request()->input('account_type_id');
            $detail_types_obj = AccountingAccountType::where('parent_id', $account_type_id)
                                    ->where(function ($q) use ($business_id) {
                                        $q->whereNull('business_id')
                                            ->orWhere('business_id', $business_id);
                                    })
                                    ->where('account_type', 'detail_type')
                                    ->get();

            $parent_accounts = AccountingAccount::where('business_id', $business_id)
                                            ->where('account_sub_type_id', $account_type_id)
                                            ->whereNull('parent_account_id')
                                            ->select('name as text', 'id')
                                            ->get();
            $parent_accounts->prepend([
                'id' => 'null',
                'text' => __('messages.please_select'),
            ]);

            $detail_types = [[
                'id' => 'null',
                'text' => __('messages.please_select'),
                'description' => '',
            ]];

            foreach ($detail_types_obj as $detail_type) {
                $detail_types[] = [
                    'id' => $detail_type->id,
                    'text' => __('accounting::lang.'.$detail_type->name),
                    'description' => ! empty($detail_type->description) ?
                        __('accounting::lang.'.$detail_type->description) : '',
                ];
            }

            return [
                'detail_types' => $detail_types,
                'parent_accounts' => $parent_accounts,
            ];
        }
    }

    public function getAccountSubTypes()
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $account_primary_type = request()->input('account_primary_type');
            $sub_types_obj = AccountingAccountType::where('account_primary_type', $account_primary_type)
                                        ->where(function ($q) use ($business_id) {
                                            $q->whereNull('business_id')
                                                ->orWhere('business_id', $business_id);
                                        })
                                        ->where('account_type', 'sub_type')
                                        ->get();

            $sub_types = [[
                'id' => 'null',
                'text' => __('messages.please_select'),
                'show_balance' => 0,
            ]];

            foreach ($sub_types_obj as $st) {
                $sub_types[] = [
                    'id' => $st->id,
                    'text' => $st->account_type_name,
                    'show_balance' => $st->show_balance,
                ];
            }

            return [
                'sub_types' => $sub_types,
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $input = $request->only(['name', 'account_primary_type', 'account_sub_type_id', 'detail_type_id',
                'parent_account_id', 'description', 'gl_code', ]);

            $account_type = AccountingAccountType::find($input['account_sub_type_id']);

            $input['parent_account_id'] = ! empty($input['parent_account_id'])
            && $input['parent_account_id'] !== 'null' ? $input['parent_account_id'] : null;
            $input['created_by'] = auth()->user()->id;
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['status'] = 'active';

            $account = AccountingAccount::create($input);

            if ($account_type->show_balance == 1 && ! empty($request->input('balance'))) {
                //Opening balance
                $data = [
                    'amount' => $this->accountingUtil->num_uf($request->input('balance')),
                    'accounting_account_id' => $account->id,
                    'created_by' => auth()->user()->id,
                    'operation_date' => ! empty($request->input('balance_as_of')) ?
                    $this->accountingUtil->uf_date($request->input('balance_as_of')) :
                    \Carbon::today()->format('Y-m-d'),
                ];

                //Opening balance
                $data['type'] = in_array($input['account_primary_type'], ['asset', 'expenses']) ? 'debit' : 'credit';
                $data['sub_type'] = 'opening_balance';
                AccountingAccountsTransaction::createTransaction($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $account = AccountingAccount::where('business_id', $business_id)
                                    ->with(['detail_type'])
                                    ->find($id);

            $account_types = AccountingAccountType::accounting_primary_type();
            $account_sub_types = AccountingAccountType::where('account_primary_type', $account->account_primary_type)
                                            ->where('account_type', 'sub_type')
                                            ->where(function ($q) use ($business_id) {
                                                $q->whereNull('business_id')
                                                    ->orWhere('business_id', $business_id);
                                            })
                                            ->get();
            $account_detail_types = AccountingAccountType::where('parent_id', $account->account_sub_type_id)
                                    ->where('account_type', 'detail_type')
                                    ->where(function ($q) use ($business_id) {
                                        $q->whereNull('business_id')
                                            ->orWhere('business_id', $business_id);
                                    })
                                    ->get();

            $parent_accounts = AccountingAccount::where('business_id', $business_id)
                                    ->where('account_sub_type_id', $account->account_sub_type_id)
                                    ->whereNull('parent_account_id')
                                    ->get();
            $contacts = \App\Contact::customersDropdown($business_id, false);
            return view('accounting::chart_of_accounts.edit')->with(compact('account_types', 'account','contacts',
                                                'account_sub_types', 'account_detail_types', 'parent_accounts'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $business_id = $request->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $input = $request->only(['name', 'account_primary_type', 'account_sub_type_id', 'detail_type_id','contact_id',
                'parent_account_id', 'description', 'gl_code', ]);

            $input['parent_account_id'] = ! empty($input['parent_account_id'])
            && $input['parent_account_id'] !== 'null' ? $input['parent_account_id'] : null;

            $account = AccountingAccount::find($id);
            $account->update($input);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function activateDeactivate($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $account = AccountingAccount::where('business_id', $business_id)
                                    ->find($id);

            $account->status = $account->status == 'active' ? 'inactive' : 'active';
            $account->save();

            $msg = $account->status == 'active' ? __('accounting::lang.activated_successfully') :
            __('accounting::lang.deactivated_successfully');
            $output = ['success' => 1,
                'msg' => $msg,
            ];

            return $output;
        }
    }

    /**
     * Displays the ledger of the account
     *
     * @param  int  $account_id
     * @return Response
     */
    public function ledger($account_id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (! (auth()->user()->can('superadmin') ||
            $this->moduleUtil->hasThePermissionInSubscription($business_id, 'accounting_module')) ||
            ! (auth()->user()->can('accounting.manage_accounts'))) {
            abort(403, 'Unauthorized action.');
        }

        $account = AccountingAccount::where('business_id', $business_id)
                        ->with(['account_sub_type', 'detail_type'])
                        ->findorFail($account_id);

        if (request()->ajax()) {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');

            // $before_bal_query = AccountingAccountsTransaction::where('accounting_account_id', $account->id)
            //                     ->leftjoin('accounting_acc_trans_mappings as ATM', 'accounting_accounts_transactions.acc_trans_mapping_id', '=', 'ATM.id')
            //         ->select([
            //             DB::raw('SUM(IF(accounting_accounts_transactions.type="credit", accounting_accounts_transactions.amount, -1 * accounting_accounts_transactions.amount)) as prev_bal')])
            //         ->where('accounting_accounts_transactions.operation_date', '<', $start_date);
            // $bal_before_start_date = $before_bal_query->first()->prev_bal;

            $transactions = AccountingAccountsTransaction::where('accounting_account_id', $account->id)
                            ->leftjoin('accounting_acc_trans_mappings as ATM', 'accounting_accounts_transactions.acc_trans_mapping_id', '=', 'ATM.id')
                            ->leftjoin('transactions as T', 'accounting_accounts_transactions.transaction_id', '=', 'T.id')
                            ->leftjoin('users AS U', 'accounting_accounts_transactions.created_by', 'U.id')
                           
                            ->leftjoin('transaction_payments as TP', 'TP.id', '=', 'accounting_accounts_transactions.transaction_payment_id')

                            ->select('accounting_accounts_transactions.operation_date',
                                'accounting_accounts_transactions.sub_type',
                                'accounting_accounts_transactions.type',
                                'TP.payment_ref_no',
                                'ATM.ref_no', 'ATM.note',
                                'accounting_accounts_transactions.amount',
                                DB::raw("CONCAT(COALESCE(U.surname, ''),' ',COALESCE(U.first_name, ''),' ',COALESCE(U.last_name,'')) as added_by"),
                                'T.invoice_no'
                            );
            if (! empty($start_date) && ! empty($end_date)) {
                $transactions->whereDate('accounting_accounts_transactions.operation_date', '>=', $start_date)
                        ->whereDate('accounting_accounts_transactions.operation_date', '<=', $end_date);
            }

            return DataTables::of($transactions)
                    ->editColumn('operation_date', function ($row) {
                        return $this->accountingUtil->format_date($row->operation_date, true);
                    })
                    ->editColumn('ref_no', function ($row) {
                        $description = '';

                        if ($row->sub_type == 'journal_entry') {
                            $description = '<b>'.__('accounting::lang.journal_entry').'</b>';
                            $description .= '<br>'.__('purchase.ref_no').': '.$row->ref_no;
                        }

                        if ($row->sub_type == 'opening_balance') {
                            $description = '<b>'.__('accounting::lang.opening_balance').'</b>';
                        }

                        if ($row->sub_type == 'sell') {
                            $description = '<b>'.__('sale.sale').'</b>';
                            $description .= '<br>'.__('sale.invoice_no').': '.$row->invoice_no;
                        }
                        if($row->sub_type == 'sell_payment'){
                          $description = '<b>' . __('دفع') . '</b>';
                          $description .= '' . ': ' . $row->payment_ref_no;
                        }
                        if($row->sub_type == 'sell_return'){
                            $description = '<b>' . __('lang_v1.sell_return') . '</b>';
                            $description .= ''  . ': ' . $row->invoice_no;
                        }
                        return $description;
                    })
                    ->addColumn('debit', function ($row) {
                        if ($row->type == 'debit') {
                            return '<span class="debit" data-orig-value="'.$row->amount.'">'.$this->accountingUtil->num_f($row->amount, true).'</span>';
                        }

                        return '';
                    })
                    ->addColumn('credit', function ($row) {
                        if ($row->type == 'credit') {
                            return '<span class="credit"  data-orig-value="'.$row->amount.'">'.$this->accountingUtil->num_f($row->amount, true).'</span>';
                        }

                        return '';
                    })
                    // ->addColumn('balance', function ($row) use ($bal_before_start_date, $start_date) {
                    //     //TODO:: Need to fix same balance showing for transactions having same operation date
                    //     $current_bal = AccountingAccountsTransaction::where('accounting_account_id',
                    //                         $row->account_id)
                    //                     ->where('operation_date', '>=', $start_date)
                    //                     ->where('operation_date', '<=', $row->operation_date)
                    //                     ->select(DB::raw("SUM(IF(type='credit', amount, -1 * amount)) as balance"))
                    //                     ->first()->balance;
                    //     $bal = $bal_before_start_date + $current_bal;
                    //     return '<span class="balance" data-orig-value="' . $bal . '">' . $this->accountingUtil->num_f($bal, true) . '</span>';
                    // })
                    ->editColumn('action', function ($row) {
                        $action = '';

                        return $action;
                    })
                    ->filterColumn('added_by', function ($query, $keyword) {
                        $query->whereRaw("CONCAT(COALESCE(u.surname, ''), ' ', COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) like ?", ["%{$keyword}%"]);
                    })
                    ->rawColumns(['ref_no', 'credit', 'debit', 'balance', 'action'])
                    ->make(true);
        }

        $current_bal = AccountingAccount::leftjoin('accounting_accounts_transactions as AAT',
                            'AAT.accounting_account_id', '=', 'accounting_accounts.id')
                        ->where('business_id', $business_id)
                        ->where('accounting_accounts.id', $account->id)
                        ->select([DB::raw($this->accountingUtil->balanceFormula())]);
        $current_bal = $current_bal->first()->balance;

        return view('accounting::chart_of_accounts.ledger')
            ->with(compact('account', 'current_bal'));
    }
    public function post_balance($id=0){
        if(!empty($id)){
            $accounts = AccountingAccount::where('id',$id)->get();
        }else{
            $accounts = AccountingAccount::where('id',$id)->get();
        }
      
        $year_from=request()->get('year_from');
        $year_to=request()->get('year_to');
        try{
            foreach($accounts as $account){
                $current_bal = AccountingAccountsTransaction::where('accounting_account_id', 
                    $account->id)
                ->whereYear('operation_date', $year_from)
                ->select(DB::raw("SUM(IF(type='credit', amount, -1 * amount)) as balance"))
                ->first()->balance;
                //dd($current_bal);
                DB::beginTransaction();
                $opening_balance=\DB::table('accounting_accounts_transactions')
                    ->where('accounting_account_id',$account->id)
                    ->where('sub_type','opening_balance')
                    ->whereYear('operation_date',$year_to)
                    ->first();
                if(!empty($current_bal)){
                    //Opening balance
                    $data = [
                        'amount' => abs($current_bal),
                        'accounting_account_id' => $account->id,
                        'created_by' => auth()->user()->id,
                        'note' => 'رصيد مرحل من العام السابق '.$year_from ,
                        'operation_date' => \Carbon\Carbon::create($year_to)->startOfYear()->format('Y-m-d')
                    ];
                    if(in_array($account->account_primary_type, ['asset', 'expenses'] )){
                    $current_bal= $current_bal * -1 ; 
                    }
                    
                    //$balance_type=$request->input('balance_type');
                    if($current_bal>0){
                    $balance_type = in_array($account->account_primary_type, ['asset', 'expenses']) ? 'debit' : 'credit';

                    }else{
                    $balance_type = in_array($account->account_primary_type, ['asset', 'expenses']) ? 'credit' : 'debit';

                    }
                    //Opening balance
                    $data['type'] = $balance_type;
                    //Opening balance
                    $data['sub_type'] = 'opening_balance';
                    // dd($data);
                    if(empty($opening_balance)){
                    
                        AccountingAccountsTransaction::createTransaction($data);
                    }else{
                    \DB::table('accounting_accounts_transactions')
                            ->where('id',$opening_balance->id)
                            ->update($data);
                    }
                }
            }
            DB::commit();
                $output=['success'=>true,
                'msg'=>'accounting::lang.opening_balance_added',

                ];
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output=['success'=>false,
                'msg' => __('messages.something_went_wrong'),
                ];
        }
        return redirect()->back()->with('status',$output);
    }
}
