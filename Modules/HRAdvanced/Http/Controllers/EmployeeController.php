<?php

namespace Modules\HRAdvanced\Http\Controllers;

use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRAdvanced\Entities\Job;
use Modules\HRAdvanced\Entities\Employee;
use Modules\HRAdvanced\Entities\SalaryItem;
use Illuminate\Contracts\Support\Renderable;
use Modules\HRAdvanced\Entities\EmpSalaryItem;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
     /**
     * All Utils instance.
     */
    protected $util;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
   
    }

    public function index(Request $request)
    {
    // Check if the request is an AJAX call
    $business_id=auth()->user()->business_id;
    if ($request->ajax()) {
        $employees = Employee::where('business_id',$business_id)->select(['id', 'employee_number', 'name_ar', 'name_en', 'national_id', 'nationality', 'occupation', 'phone_number', 'email']);

        return datatables()->of($employees)
            ->addColumn('action', function ($row) {
                // Add buttons for viewing, editing, deleting an employee
                $html ='<a href="' . route('employees.show', $row->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> View</a>';/*<a href="' . route('employees.edit', $row->id) . '" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                        <form action="' . route('employees.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                        </form>'*/;
                $html.='<a href="' . route('employees.salaryItems', $row->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i> بنود الراتب</a>
                        ';
                return $html;
            })
            ->rawColumns(['action']) // Enable HTML content in the action column
            ->make(true);
    }

    // If not an AJAX request, return the view
    return view('hradvanced::employee.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $jobs=Job::where('business_id',session('business.id'))->get();
       
        return view('hradvanced::employee.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        /*
        $request->validate([
            'employee_number' => 'required|numeric',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'national_id' => 'required|numeric',
            'entry_number' => 'nullable|numeric',
            'education_level' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
            'religion' => 'nullable|string|max:255',
            'marital_status' => 'required|string',
            'phone_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'contract_start_gregorian' => 'nullable|date',
            'contract_end_gregorian' => 'nullable|date',
            'contract_start_hijri' => 'nullable|string|max:255', // Hijri date
            'contract_end_hijri' => 'nullable|string|max:255',   // Hijri date
            'vacation_days' => 'nullable|numeric',
            'contract_duration_months' => 'nullable|numeric',
            'entitled_to_ticket' => 'required|boolean',
            'iqama' => 'nullable|file|mimes:jpeg,png,pdf|max:2048', // Validate file types
            'passport' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'new_passport' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'project_name' => 'nullable|string|max:255',
        ]);
    */
        // Handle file uploads
        $business_id=request()->session('business.id');
        $iqamaPath = $request->file('iqama') ? $request->file('iqama')->store('documents/iqama', 'public') : null;
        $passportPath = $request->file('passport') ? $request->file('passport')->store('documents/passport', 'public') : null;
        $newPassportPath = $request->file('new_passport') ? $request->file('new_passport')->store('documents/new_passport', 'public') : null;
        //dd($request->input('exclusion_date_gregorian'));
        // Create a new employee record
        $employee = Employee::create([
            'business_id'=>$business_id,
            'employee_number' => $request->input('employee_number'),
            'name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
            'national_id' => $request->input('national_id'),
            'entry_number' => $request->input('entry_number'),
            'education_level' => $request->input('education_level'),
            'nationality' => $request->input('nationality'),
            'occupation' => $request->input('occupation',null),
            'job_id'=>$request->input('job_id'),
            'birthdate' => $request->input('birthdate'),
            'religion' => $request->input('religion'),
            'marital_status' => $request->input('marital_status'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'contract_start_gregorian' => !empty($request->input('contract_start_gregorian')) ? $this->util->uf_date($request->input('contract_start_gregorian')) : null,
            'contract_end_gregorian' => !empty($request->input('contract_end_gregorian')) ? $this->util->uf_date($request->input('contract_end_gregorian')) : null,
            'contract_start_hijri' => $request->input('contract_start_hijri'),
            'contract_end_hijri' => $request->input('contract_end_hijri'),
            'vacation_days' => $request->input('vacation_days'),
            'contract_duration_months' => $request->input('contract_duration_months'),
            'entitled_to_ticket' => $request->input('entitled_to_ticket'),
            'iqama' => $iqamaPath,
            'passport' => $passportPath,
            'new_passport' => $newPassportPath,
            'project_id' => $request->input('project_name'),
            'project_code' => $request->input('project_code'),
            // Social Security Information
            'social_insurance_registration_gregorian' => !empty($request->input('social_insurance_registration_gregorian')) ? $this->util->uf_date($request->input('social_insurance_registration_gregorian')) : null,
            'social_insurance_registration_hijri' => $request->input('social_insurance_registration_hijri'),
            'employee_excluded_from_insurance' => $request->input('employee_excluded_from_insurance'),
            'exclusion_date_gregorian' => !empty($request->input('exclusion_date_gregorian')) ? $this->util->uf_date($request->input('exclusion_date_gregorian')) : null,
            'exclusion_date_hijri' => $request->input('exclusion_date_hijri'),
            'exclusion_reason' => $request->input('exclusion_reason'),

            // Notifications
            'requires_notification' => $request->input('requires_notification'),
            'last_updated_at' => \Carbon\Carbon::now(),
            'updated_time' => $request->input('updated_time',null),
            'created_by' => \Auth::user()->id,

            // Social Security Details
            'company_insurance_number' => $request->input('company_insurance_number'),
            'employee_insurance_number' => $request->input('employee_insurance_number'),
            'work_sponsor' => $request->input('work_sponsor'),
            'work_office_number' => $request->input('work_office_number'),
        ]);
        $output=[
            'success'=>true,
            'msg'=>'Employee Added Successfully',
        ];
       
        return redirect()->route('employees.index')->with('status',$output);
    }
    

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $employee=Employee::find($id);
        return view('hradvanced::employee.show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hradvanced::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function salaryItems($emp_id){
        
        $salaryItems=SalaryItem::all();
        foreach($salaryItems as $item){
           if(!EmpSalaryItem::where('employee_id',$emp_id)->where('salary_item_id',$item->id)->count()){
            EmpSalaryItem::insert([
                'employee_id'=>$emp_id,
                'salary_item_id'=>$item->id,
                'status'=>'opend',
                'amount'=>0,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
                'starts_at'=>date('Y-m-d H:i:s'),
            ]);
           }
        }
        $employee=Employee::find($emp_id);
        return view ('hradvanced::employee.salary',get_defined_vars());
    }
    public function updateSalaryItems(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        
        // Loop through each submitted salary item data
        foreach ($request->salary_items as $salaryItemId => $data) {
            $salaryItem = EmpSalaryItem::findOrFail($salaryItemId);
            if($data['amount'] != $salaryItem->amount){
                $salaryItem->starts_at = \Carbon\Carbon::now();
            }
           
            $salaryItem->amount = $data['amount'];
            $salaryItem->status = $data['status'];
           
            $salaryItem->save();
        }

        return redirect()->back()->with('success', 'تم تحديث بنود الراتب بنجاح');
    }
}
