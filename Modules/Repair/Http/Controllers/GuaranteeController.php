<?php

namespace Modules\Repair\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Contact;
use App\Brands;
use App\BusinessLocation;
use App\Business;
use App\Category;
use Modules\Repair\Entities\DeviceModel;
use Modules\Repair\Entities\RepairStatus;
use Modules\Repair\Utils\RepairUtil;
use App\Utils\Util;
use Modules\Repair\Entities\JobSheet;
use App\Utils\CashRegisterUtil;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Utils\ModuleUtil;
use App\CustomerGroup;
use App\Utils\ContactUtil;
use App\Media;

class GuaranteeController extends Controller
{   
    /**
     * All Utils instance.
     *
     */
    protected $repairUtil;
    protected $commonUtil;
    protected $cashRegisterUtil;
    protected $moduleUtil;
    protected $contactUtil;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(RepairUtil $repairUtil, Util $commonUtil, CashRegisterUtil $cashRegisterUtil, ModuleUtil $moduleUtil,
        ContactUtil $contactUtil)
    {
        $this->repairUtil = $repairUtil;
        $this->commonUtil = $commonUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moduleUtil = $moduleUtil;
        $this->contactUtil = $contactUtil;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {   
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && (auth()->user()->can('job_sheet.view_assigned') || auth()->user()->can('job_sheet.view_all') || auth()->user()->can('job_sheet.create'))))) {
            abort(403, 'Unauthorized action.');
        }

        $is_user_admin = $this->commonUtil->is_admin(auth()->user(), $business_id);

        if (request()->ajax()) {
           $data=\DB::table('repair_guarantee as rg')
           ->join('contacts','contacts.id','rg.contact_id')
           ->join('variations as v','rg.variation_id','v.id')
           ->join('products as p','p.id','v.product_id')
           ->join('repair_statuses as rs','rs.id','rg.status_id')
           ->where('rg.business_id',$business_id)
          
           ->select(
               'rg.*',
               'contacts.name as customer',
               'p.name as product_name',
               'rs.name as status',
               'rs.color as status_color'
               );
               
           if (!empty(request()->get('location_id'))) {
               $data->where('rg.location_id',request()->get('location_id'));
           }     
            if (!empty(request()->contact_id)) {
                $data->where('rg.contact_id', request()->contact_id);
            }
            if (!empty(request()->status_id)) {
                $data->where('rg.status_id', request()->status_id);
            }
            $data->get();
            
            return DataTables::of($data)
                ->addColumn('action', function($row) {
                    $html = '<div class="btn-group">
                                <button class="btn btn-info dropdown-toggle btn-xs" type="button"  data-toggle="dropdown" aria-expanded="false">
                                    '.__("messages.action").'
                                    <span class="caret"></span>
                                    <span class="sr-only">
                                    '.__("messages.action").'
                                    </span>
                                </button>';

                    $html .= '<ul class="dropdown-menu dropdown-menu-left" role="menu">';

                    if (auth()->user()->can("job_sheet.view_assigned") || auth()->user()->can("job_sheet.view_all") || auth()->user()->can("job_sheet.create")) {
                            $html .= '<li>
                                <a href="' . action('\Modules\Repair\Http\Controllers\GuaranteeController@show', ['id' => $row->id]) . '" class="cursor-pointer">
                                    <i class="fa fa-eye"></i>
                                    '.__("messages.view").'
                                </a>
                                </li>
                                ';
                    }

                    
                    
                  
                    

                    if (auth()->user()->can("job_sheet.create") || auth()->user()->can("job_sheet.edit")) {
                        $html .= '<li>
                                    <a data-href="' . action('\Modules\Repair\Http\Controllers\GuaranteeController@editStatus', ['id' => $row->id]) . '" class="cursor-pointer edit_job_sheet_status">
                                        <i class="fa fa-edit"></i>
                                        '.__("repair::lang.change_status").'
                                    </a>
                                </li>';
                    }

                  

                    $html .= '</ul>
                            </div>';
                    return $html;
                })
                ->editColumn('delivery_date', 
                    '
                        @if($delivery_date)
                            {{@format_datetime($delivery_date)}}
                        @endif
                    '
                )
                ->editColumn('created_at', 
                    '
                    {{@format_datetime($created_at)}}
                    '
                )
                ->editColumn('service_type', function($row){
                    return __('repair::lang.'.$row->service_type);
                })
                ->editColumn('estimated_cost', function($row){
                    $cost = '<span class="display_currency total-discount" data-currency_symbol="true" data-orig-value="' . $row->estimated_cost . '">' . $row->estimated_cost . '</span>';
                    
                    return $cost;
                })
                ->addColumn('technecian',function($row){
                  $tech=\DB::table('users')->where('id',$row->service_staff)->first();
                  if($tech){
                      return $tech->name;
                      
                  }
                  else{
                      return '';
                  }
                })
                ->editColumn('repair_no', function($row) {
                    $invoice_no = [];
                $add_invoice='';
                $transaction=\DB::table('transactions')->where('id',$row->transaction_id)->first();
                $invoice_no=[$transaction->invoice_no];
                    return implode(', ', $invoice_no) . $add_invoice;
                })
                ->editColumn('status', function($row) {
                    $html = '<a data-href="' . action("\Modules\Repair\Http\Controllers\GuaranteeController@editStatus", [$row->id]) . '" class="edit_job_sheet_status cursor-pointer" data-orig-value="'.$row->status.'" data-status-name="'.$row->status.'">
                                <span class="label " style="background-color:'.$row->status_color.';" >
                                    ' .$row->status .'
                                </span>
                            </a>
                        ';
                    return $html;
                })
                ->editColumn('supplier_id',function($row){
                    $data=\DB::table('contacts')->where('id',$row->supplier_id)->first();
                    if($data)
                        return $data->first_name;
                    else
                        return '';
                })
                ->editColumn('location_id',function($row){
                    $data=\DB::table('business_locations')->where('id',$row->location_id)->first();
                    
                    if($data)
                        return $data->name;
                    else
                        return '';
                })
                ->editColumn('created_by',function($row){
                    $data=\DB::table('users')->where('id',$row->created_by)->first();
                    if($data)
                        return $data->first_name." ".$data->last_name;
                    else
                        return '';
                    
                })
                ->removeColumn('id')
                ->rawColumns([ 'action','delivery_date', 'repair_no', 'status', 'estimated_cost', 'created_at','technecian'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);
        $status_dropdown = RepairStatus::forDropdown($business_id);
        $service_staffs = $this->commonUtil->serviceStaffDropdown($business_id);

        $user_role_as_service_staff = auth()->user()->roles()
                            ->where('is_service_staff', 1)
                            ->get()
                            ->toArray();
        $is_user_service_staff = false;
        if (!empty($user_role_as_service_staff) && !$is_user_admin) {
            $is_user_service_staff = true;
        }

        return view('repair::guarantee.index')
            ->with(compact('business_locations', 'customers', 'status_dropdown', 'service_staffs', 'is_user_service_staff'));
    }
    public function get_products_sold(Request $request){
        $customer_id=$request->customer_id;
        $products=\DB::table('transactions as T')->where('contact_id', $customer_id)
        ->join('transaction_sell_lines as SL','SL.transaction_id','T.id')
        ->join('variations as V','V.id','SL.variation_id')
        ->join('products','products.id','V.product_id')
        ->select(
            'V.id as variation_id',
            'V.name as variation_name',
            'products.name as product_name'
            
            )
        ->get();
   $content='<option>يرجي الاختيار</option>';
    foreach($products as $row){
        $content.='<option value="'.$row->variation_id.'">'.$row->product_name.' - '.$row->variation_name.'</option>';
    }
        return $content;
    }
    public function get_invoice_sold(Request $request){
        $customer_id=$request->customer_id;
        $variation_id=$request->variation_id;
        $products=\DB::table('transactions as T')
        ->join('transaction_sell_lines as SL','SL.transaction_id','T.id')
        
        ->where('T.contact_id', $customer_id)
        ->where('SL.variation_id',$variation_id)
        ->select(
            'T.id as transaction_id',
            'T.invoice_no',
            'T.transaction_date'
            
            )
        ->get();
    $content='<option>يرجي الاختيار</option>';
    foreach($products as $row){
        $content.='<option value="'.$row->transaction_id.'">#'.$row->invoice_no.' - '.$row->transaction_date.'</option>';
    }
        return $content;
    }
     public function get_supplier(Request $request){
        $variation_id=$request->variation_id;
        $transaction_id=$request->transaction_id;
        $products=\DB::table('transactions as T')
        ->join('transaction_sell_lines as SL','SL.transaction_id','T.id')
        ->join('transaction_sell_lines_purchase_lines as SPL','SPL.sell_line_id','SL.id')
        ->where('T.id', $transaction_id)
        ->where('SL.variation_id',$variation_id)
        ->select(
            'SPL.purchase_line_id',
            'SPL.stock_adjustment_line_id'
            )
        ->first();
        
        //dd($products);
        $contacts=\DB::table('transactions as T')
        ->join('purchase_lines as PL','PL.transaction_id','T.id')
        ->join('contacts','contacts.id','T.contact_id')
        ->where('PL.id',$products->purchase_line_id)
        ->select(
            'contacts.id',
            'contacts.name'
            )
        ->get();
    $content='<option>يرجي الاختيار</option>';
    foreach($contacts as $row){
        $content.='<option value="'.$row->id.'">#'.$row->name.'</option>';
    }
        return $content;
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $business_id = request()->session()->get('user.business_id');
        
        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && auth()->user()->can('job_sheet.create')))) {
            abort(403, 'Unauthorized action.');
        }

        $repair_statuses = RepairStatus::getRepairSatuses($business_id);
        $device_models = DeviceModel::forDropdown($business_id);
        $brands = Brands::forDropdown($business_id);
        $devices = Category::forDropdown($business_id, 'device');
        $repair_settings = $this->repairUtil->getRepairSettings($business_id);
        $business_locations = BusinessLocation::forDropdown($business_id);
        $types = Contact::getContactTypes();
        $customer_groups = CustomerGroup::forDropdown($business_id);
        $walk_in_customer = $this->contactUtil->getWalkInCustomer($business_id);
        $default_status = '';
        if (!empty($repair_settings['default_status'])) {
            $default_status = $repair_settings['default_status'];
        }

        //get service staff(technecians)
        $technecians = [];
        if ($this->commonUtil->isModuleEnabled('service_staff')) {
            $technecians = $this->commonUtil->serviceStaffDropdown($business_id);
        }
        $suppliers = Contact::suppliersDropdown($business_id, false);
        return view('repair::guarantee.create')
            ->with(compact('repair_statuses', 'suppliers' ,'device_models', 'brands', 'devices', 'default_status', 'technecians', 'business_locations', 'types', 'customer_groups', 'walk_in_customer', 'repair_settings'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $business_id = request()->session()->get('user.business_id');

       
        try {
            $input = $request->only('contact_id', 'service_type', 'variation_id', 'transaction_id', 'supplier_id', 'security_pwd', 'security_pattern', 'serial_no', 'status_id', 'delivery_date', 'estimated_cost', 'product_configuration', 'defects', 'product_condition', 'service_staff', 'location_id', 'pick_up_on_site_addr', 'comment_by_ss');

            if (!empty($input['delivery_date'])) {
                $input['delivery_date'] = $this->commonUtil->uf_date($input['delivery_date'], true);
            }

            if (!empty($input['estimated_cost'])) {
                $input['estimated_cost'] = $this->commonUtil->num_uf($input['estimated_cost']);
            }

            if (!empty($request->input('repair_checklist'))) {
                $input['checklist'] = $request->input('repair_checklist');
            }

           //DB::beginTransaction();

            //Generate reference number
            $ref_count = $this->commonUtil->setAndGetReferenceCount('job_sheet', $business_id);
            $business = Business::find($business_id);
            $repair_settings = json_decode($business->repair_settings, true);
//            $input['job_sheet_no'] = $this->commonUtil->generateReferenceNumber('job_sheet', $ref_count, null, $repair_settings['job_sheet_prefix']);

            $input['created_by'] = $request->user()->id;
            $input['business_id'] = $business_id;

            //dd($input);
            \DB::table('repair_guarantee')->insert($input);
            $line=\DB::table('transaction_sell_lines')->where('transaction_id',$input['transaction_id'])->where('variation_id',$input['variation_id'])->first();
            
                $status = RepairStatus::where('business_id', $business_id)->findOrFail($input['status_id']);
            if($status->return_product==1){
                
                $target_path=\URL::to('/sell-return/add/')."/".$input['transaction_id']."?return=true&variation_id=".$input['variation_id'];
                 return redirect()
                //->action('\Modules\Repair\Http\Controllers\JobSheetController@show', [$job_sheet->id])
                ->to($target_path)->with('status', ['success' => true,
                    'msg' => __("جاري تسجيل مرتجع مبيعات")]);
            }
            //upload media
           // Media::uploadMedia($business_id, $job_sheet, $request, 'images');
/*
            if (!empty($request->input('send_notification')) && in_array('sms', $request->input('send_notification'))) {
                $status = RepairStatus::where('business_id', $business_id)
                            ->find($job_sheet->status_id);
                if (!empty($status->sms_template)) $this->repairUtil->sendJobSheetUpdateSmsNotification($status->sms_template, $job_sheet); 
            }

            if (!empty($request->input('send_notification')) && in_array('email', $request->input('send_notification'))) {
                $status = RepairStatus::where('business_id', $business_id)
                            ->find($job_sheet->status_id);
                $notification = [
                        'subject' => $status->email_subject,
                        'body' => $status->email_body
                    ];
                if (!empty($status->email_subject) && !empty($status->email_body)) $this->repairUtil->sendJobSheetUpdateEmailNotification($notification, $job_sheet); 
            var return_path="{{URL::to('/sell-return/add/')}}"+"/"+transaction_id+"?return=true&variation_id="+variation_id;
                             window.location.assign(return_path);
                
            }
  */          
            //DB::commit();

            return redirect()
                //->action('\Modules\Repair\Http\Controllers\JobSheetController@show', [$job_sheet->id])
                ->back()->with('status', ['success' => true,
                    'msg' => __("lang_v1.success")]);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('status', ['success' => false,
                    'msg' => __('messages.something_went_wrong')
                ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {  
        $business_id = request()->session()->get('user.business_id');

      
                        
        
        $guarantee=\DB::table('repair_guarantee as rg')
           ->join('contacts','contacts.id','rg.contact_id')
           ->join('variations as v','rg.variation_id','v.id')
           ->join('products as p','p.id','v.product_id')
           ->join('repair_statuses as rs','rs.id','rg.status_id')
           ->join('business as b','b.id','rg.business_id')
           ->where('rg.business_id',$business_id)
           ->where('rg.id',$id)
           ->select(
               'rg.*',
               'b.name as business',
               'contacts.name as customer_name',
               
               'contacts.mobile as customer_mobile',
               'contacts.email as customer_email',
               'p.name as product_name',
               'rs.name as status',
               'rs.color as status_color'
               )->first();
        
//dd($guarantee);
        $business = Business::find($business_id);
        $repair_settings = json_decode($business->repair_settings, true);
        
        return view('repair::guarantee.show')
            ->with(compact('guarantee', 'repair_settings'));
    }
    public function print_slim($id)
    {  
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && (auth()->user()->can('job_sheet.view_assigned') || auth()->user()->can('job_sheet.view_all') || auth()->user()->can('job_sheet.create'))))) {
            abort(403, 'Unauthorized action.');
        }

        $query = JobSheet::with('customer',
                        'customer.business', 'technician',
                        'status', 'Brand', 'Device', 'deviceModel', 'businessLocation', 'invoices', 'media')
                        ->where('business_id', $business_id);
                        
        //if user is not admin get only assgined/created_by job sheet
        if (!$this->commonUtil->is_admin(auth()->user(), $business_id)) {
            $user_id = auth()->user()->id;
            $query->where(function ($q) use ($user_id){
                $q->where('repair_job_sheets.service_staff', $user_id)
                    ->orWhere('repair_job_sheets.created_by', $user_id);
            });
        }

        $job_sheet = $query->findOrFail($id);

        $business = Business::find($business_id);
        $repair_settings = json_decode($business->repair_settings, true);
        $receipt_details=[];
        return view('repair::job_sheet.print_slim')
            ->with(compact('job_sheet', 'repair_settings','receipt_details'));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && auth()->user()->can('job_sheet.edit')))) {
            abort(403, 'Unauthorized action.');
        }

        $job_sheet = JobSheet::where('business_id', $business_id)
                    ->findOrFail($id);

        $repair_statuses = RepairStatus::getRepairSatuses($business_id);
        $device_models = DeviceModel::forDropdown($business_id);
        $brands = Brands::forDropdown($business_id);
        $devices = Category::forDropdown($business_id, 'device');
        $repair_settings = $this->repairUtil->getRepairSettings($business_id);
        $types = Contact::getContactTypes();
        $customer_groups = CustomerGroup::forDropdown($business_id);
        $default_status = '';
        if (!empty($repair_settings['default_status'])) {
            $default_status = $repair_settings['default_status'];
        }

        //get service staff(technecians)
        $technecians = [];
        if ($this->commonUtil->isModuleEnabled('service_staff')) {
            $technecians = $this->commonUtil->serviceStaffDropdown($business_id);
        }

        return view('repair::job_sheet.edit')
            ->with(compact('job_sheet', 'repair_statuses', 'device_models', 'brands', 'devices', 'default_status', 'technecians', 'types', 'customer_groups', 'repair_settings'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && auth()->user()->can('job_sheet.edit')))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only('contact_id', 'service_type', 'brand_id', 'device_id', 'device_model_id', 'security_pwd', 'security_pattern', 'serial_no', 'status_id', 'delivery_date', 'estimated_cost', 'product_configuration', 'defects', 'product_condition', 'service_staff', 'pick_up_on_site_addr', 'comment_by_ss');

            if (!empty($input['delivery_date'])) {
                $input['delivery_date'] = $this->commonUtil->uf_date($input['delivery_date'], true);
            }

            if (!empty($input['estimated_cost'])) {
                $input['estimated_cost'] = $this->commonUtil->num_uf($input['estimated_cost']);
            }

            if (!empty($request->input('repair_checklist'))) {
                $input['checklist'] = $request->input('repair_checklist');
            }
            
            DB::beginTransaction();

            $job_sheet = JobSheet::where('business_id', $business_id)
                            ->findOrFail($id);
                            
            $job_sheet->update($input);

            //upload media
            Media::uploadMedia($business_id, $job_sheet, $request, 'images');
            
            if (!empty($request->input('send_notification')) && in_array('sms', $request->input('send_notification'))) {
                $status = RepairStatus::where('business_id', $business_id)
                            ->find($job_sheet->status_id);
                if (!empty($status->sms_template)) $this->repairUtil->sendJobSheetUpdateSmsNotification($status->sms_template, $job_sheet); 
            }
            
            if (!empty($request->input('send_notification')) && in_array('email', $request->input('send_notification'))) {
                $status = RepairStatus::where('business_id', $business_id)
                            ->find($job_sheet->status_id);
                $notification = [
                        'subject' => $status->email_subject,
                        'body' => $status->email_body
                    ];
                if (!empty($status->email_subject) && !empty($status->email_body)) $this->repairUtil->sendJobSheetUpdateEmailNotification($notification, $job_sheet); 
            }

            DB::commit();

            return redirect()
                ->action('\Modules\Repair\Http\Controllers\JobSheetController@show', [$job_sheet->id])
                ->with('status', ['success' => true,
                    'msg' => __("lang_v1.success")]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            return redirect()->back()
                ->with('status', ['success' => false,
                    'msg' => __('messages.something_went_wrong')
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && auth()->user()->can('job_sheet.delete')))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $job_sheet = JobSheet::where('business_id', $business_id)
                    ->findOrFail($id);

                $job_sheet->delete();
                $job_sheet->media()->delete();
                
                $output = ['success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong')
                ];
            }

            return $output;
        }
    }

    /**
     * Show the form for editing the status
     * @param int $id
     * @return Response
     */
    public function editStatus($id)
    {   
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {

            $guarantee = \DB::table('repair_guarantee')->where('business_id', $business_id)->where('id',$id)->first();
            $transaction_id=$guarantee->transaction_id;
            $status_dropdown = RepairStatus::forDropdown($business_id, true);
            $status_template_tags = $this->repairUtil->getRepairStatusTemplateTags();
            return view('repair::guarantee.partials.edit_status')
                ->with(compact('transaction_id','guarantee', 'status_dropdown', 'status_template_tags'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && (auth()->user()->can('job_sheet.create') || auth()->user()->can('job_sheet.edit'))))) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            try {
                $status_id = $request->input('status_id');
                $current_guarantee=\DB::table('repair_guarantee')->where('business_id', $business_id)->where('id',$id)->first();
                $current_status = RepairStatus::where('business_id', $business_id)->findOrFail($current_guarantee->status_id);
                
                if($current_status->return_product==1){
                     $output = ['success' => false,
                    'msg' => __('لايمكن سحب المنتج من المرتجعات مرة اخري')
                ];
                 return $output;
                }
                 \DB::table('repair_guarantee')->where('business_id', $business_id)->where('id',$id)
                ->update([
                        'status_id' => $status_id
                    ]);
                
                $status = RepairStatus::where('business_id', $business_id)->findOrFail($status_id);
                if($status->return_product==1){
                     $output = ['success' => 'return_product',
                    'msg' => __("جاري عمل ارتجاع للمنتج ")
                ];
                  return $output;
                }
                /*send job sheet updates
                if (!empty($request->input('send_sms'))) {
                    $sms_body = $request->input('sms_body');
                    $response = $this->repairUtil->sendJobSheetUpdateSmsNotification($sms_body, $job_sheet);
                }

                if (!empty($request->input('send_email'))) {
                        $subject = $request->input('sms_body');
                        $body = $request->input('sms_body');
                        $notification = [
                            'subject' => $subject,
                            'body' => $body
                        ];
                    if (!empty($subject) && !empty($body)) $this->repairUtil->sendJobSheetUpdateEmailNotification($notification, $job_sheet); 
                }

                activity()
                    ->performedOn($job_sheet)
                    ->withProperties(['update_note' => $request->input('update_note'), 'updated_status' => $status->name  ])
                    ->log('status_changed');
                */
                $output = ['success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (Exception $e) {
                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong')
                ];
            }

            return $output;
        }
    }

    public function deleteJobSheetImage(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (!(auth()->user()->can('superadmin') || ($this->moduleUtil->hasThePermissionInSubscription($business_id, 'repair_module') && (auth()->user()->can('job_sheet.view_assigned') || auth()->user()->can('job_sheet.view_all') || auth()->user()->can('job_sheet.create'))))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {

                Media::deleteMedia($business_id, $id);
                
                $output = ['success' => true,
                    'msg' => __("lang_v1.success")
                ];
            } catch (\Exception $e) {
                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong')
                ];
            }

            return $output;
        }
    }
}
