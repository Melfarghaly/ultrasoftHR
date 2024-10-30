<?php

namespace Modules\HRAdvanced\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRAdvanced\Entities\Employee;
use Modules\HRAdvanced\Entities\Timesheet;
use Modules\HRAdvanced\Entities\Penalty;
use Illuminate\Contracts\Support\Renderable;
use Carbon;
use DB;
use DataTables;
class TimesheetController extends Controller
{
    public function index()
    {
        $business_id=auth()->user()->business_id;
       
        $employees = Employee::with('timesheets')
        ->where('business_id',$business_id)
        ->get();
        return view('hradvanced::timesheets.index', compact('employees'));
    }
    
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
        ]);
    
        // Calculate total hours if clock-out is present
        $totalHours = 0;
        if ($request->clock_out) {
            $start = Carbon::createFromFormat('H:i', $request->clock_in);
            $end = Carbon::createFromFormat('H:i', $request->clock_out);
            $totalHours = $end->diffInMinutes($start) / 60;
        }
    
        // Store or update the timesheet
        Timesheet::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ],
            [
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'total_hours' => $totalHours,
            ]
        );
        return redirect()->back()->with('success', 'Timesheet updated successfully!');
    }
    public function daily(Request $request){
        // Check if the request is an AJAX call
        $business_id=auth()->user()->business_id;
        if ($request->ajax()) {
            $timesheet = Timesheet::where('business_id',$business_id);
            if(request()->start_date){
                $timesheet->whereDate('date', '>=', request()->start_date);
            }
            if(request()->end_date){
                $timesheet->whereDate('date', '<=', request()->end_date);
            }
            if(request()->employee_id){
                $timesheet->where('employee_id', '=', request()->employee_id);
            }
            $timesheet->get();
     

            return datatables()->of($timesheet)
               
                
                ->addColumn('daily_delete', function ($row) {
                    return  '<input type="checkbox" class="row-select" value="'.$row->id.'">';
                })
                ->addColumn('name', function ($row) {
                    return $row->employee->name_ar ?? '' ;
                })
                ->rawColumns(['daily_delete']) // Enable HTML content in the action column
                ->make(true);
        }
    }
    public function weekly(Request $request){
        // Check if the request is an AJAX call
        $business_id=auth()->user()->business_id;
        if ($request->ajax()) {
            $employees = Employee::where('business_id',$business_id)
            ->select(['id',
               'employee_number', 
               'name_ar', 'name_en',
               'national_id', 'nationality',
               'occupation', 
               'phone_number', 
               'project_id',
               'email',
             //  'job_title'
            ]
        );

            return datatables()->of($employees)
               
                ->addColumn('job_title', function ($row) {
                    return $row->job->job_title ?? '' ;
                    return 'html';
                })
                ->addColumn('mass_delete', function ($row) {
                    return  '<input type="checkbox" class="row-select" value="'.$row->id.'">';
                })
                ->rawColumns(['mass_delete']) // Enable HTML content in the action column
                ->make(true);
        }

    }
    public function massAttend(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $ids = $request->selected_rows; // Array of employee IDs
        
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        foreach ($ids as $employee_id) {
            // Reset the date to start_date for each employee
            $currentDate = $start_date->copy();

            // Loop through each date from start_date to end_date
            while ($currentDate->lte($end_date)) {
                // Define your total_hours calculation logic here
                $totalHours = $this->calculateTotalHoursForDate($employee_id, $currentDate);

                Timesheet::updateOrCreate(
                    [
                        'employee_id' => $employee_id,
                        'date' => $currentDate->format('Y-m-d'),
                        'business_id' => $business_id,
                    ],
                    [
                        'total_hours' => $totalHours,
                        'send' => 1,
                        'created_by' => auth()->user()->id,
                        'status' => 'open'
                    ]
                );

                // Move to the next day
                $currentDate->addDay();
            }
        }

        return response()->json(['success' => 'Timesheet records updated successfully.']);
    }   

    public function massApproved(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $ids = $request->selected_rows; //
        foreach ($ids as $id) {
            Timesheet::where('id',$id)->update(
                [
                    'approved' => 1,
                ]
            );
        }

        return response()->json(['success' => 'Timesheet records updated successfully.']);
    }  
    public function massPosted(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $ids = $request->selected_rows; //
        foreach ($ids as $id) {
            Timesheet::where('id',$id)->update(
                [
                    'posted' => 1,
                ]
            );
        }

        return response()->json(['success' => 'Timesheet records updated successfully.']);
    }  
    private function calculateTotalHoursForDate($employee_id, $date)
    {
        // Your logic to calculate total hours worked on a particular date
        return 8; // Example: return 8 hours per day
    }


    public function monthly(Request $request){
        $date = $request->month ? Carbon::parse($request->month) : Carbon::now();
        $business_id = auth()->user()->business_id;
    
        $month =$date->month;
        $year = $date->year;

        $employees = Employee::select(
                'hr_employees.name_ar',

                'hr_employees.nationality',
                
                DB::raw("SUM(CASE WHEN MONTH(timesheets.date) = $month AND YEAR(timesheets.date) = $year THEN 1 ELSE 0 END) as work_days"),
                DB::raw("SUM(CASE WHEN MONTH(timesheets.date) = $month AND YEAR(timesheets.date) = $year THEN timesheets.overtime ELSE 0 END) as total_overtime"),
                DB::raw("SUM(CASE WHEN MONTH(timesheets.date) = $month AND YEAR(timesheets.date) = $year THEN timesheets.total_hours ELSE 0 END) as total_hours")
            )
            ->leftJoin('timesheets', 'hr_employees.id', '=', 'timesheets.employee_id')
            ->groupBy('hr_employees.id')
            ->get();

    
        return DataTables::of($employees)
            ->addColumn('penalties', function($employee) use ($date) {
                return Penalty::where('employee_id', $employee->id)
                    ->whereMonth('penalty_date', $date->month)
                    ->whereYear('penalty_date', $date->year)
                    ->count();
            })
            ->addColumn('absence_days', function($employee) use ($month) {
                //return Absence::where('employee_id', $employee->id)
                 //   ->whereMonth('date', $month->month)
                //    ->whereYear('date', $month->year)
                 //   ->count();
                 return 0;
            })
            ->addColumn('deduction_hours', function($employee) use ($month) {
               
              
                 return 0;
            })
            ->addColumn('notes', function($employee) use ($month) {
               
              
                return '';
           })
            ->addColumn('job_title', function($employee) use ($month) {
                //return Absence::where('employee_id', $employee->id)
                 //   ->whereMonth('date', $month->month)
                //    ->whereYear('date', $month->year)
                 //   ->count();
                 return 0;
            })
            ->make(true);
    }

}
