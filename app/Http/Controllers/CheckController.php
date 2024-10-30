<?php

namespace App\Http\Controllers;

use App\Check;
use App\Account;
use App\Utils\BusinessUtil;
use App\CheckTransaction;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    protected $businessUtil;

    public function __construct(BusinessUtil $businessUtil)
    {
        $this->businessUtil = $businessUtil;
  
    }
    public function index(Request $request)
    {
        $checkType = $request->query('check_type', '');
        $issueDateFrom = $request->query('issue_date_from', '');
        $issueDateTo = $request->query('issue_date_to', '');
        $dueDateFrom = $request->query('due_date_from', '');
        $dueDateTo = $request->query('due_date_to', '');
        $account_name = $request->query('account_name', '');
    
        $query = Check::query();
    
        if ($checkType) {
            $query->where('check_type', $checkType);
        }
    
        if ($issueDateFrom) {
            $query->whereDate('issue_date', '>=', $issueDateFrom);
        }
    
        if ($issueDateTo) {
            $query->whereDate('issue_date', '<=', $issueDateTo);
        }
    
        if ($dueDateFrom) {
            $query->whereDate('due_date', '>=', $dueDateFrom);
        }
    
        if ($dueDateTo) {
            $query->whereDate('due_date', '<=', $dueDateTo);
        }
        if ($account_name) {
            $query->where('account_name', 'like', "%{$account_name}%");
        }
    
        // Paginate results
        $checks = $query->paginate(1000);
    
        return view('checks.Operations.index', [
            'checks' => $checks,
            'checkType' => $checkType,
            'issueDateFrom' => $issueDateFrom,
            'issueDateTo' => $issueDateTo,
            'dueDateFrom' => $dueDateFrom,
            'dueDateTo' => $dueDateTo,
            'account_name' => $account_name
        ]);
    }
    
    



    // public function createIssued()
    // {
    //     $business_id = session('business_id');

    //     $issuedCheckCount = Check::where('check_type', 'issued')->count();
    //     $banks = Account::forDropdown($business_id, false);
        
    //     return view('checks.Operations.create-issued', compact('issuedCheckCount', 'banks'));
    // }
    

    public function createIssued()
{
    if (auth()->check()) {
        $business_id = auth()->user()->business_id;
        
        // dd($business_id);

        $issuedCheckCount = Check::where('check_type', 'issued')->count();
        
        $banks = Account::forDropdown($business_id, true);

        $currencies = $this->businessUtil->allCurrencies();
        
        return view('checks.Operations.create-issued', compact('issuedCheckCount', 'banks','currencies'));
    } else {
        abort(403, 'Unauthorized action.');
    }
}


    public function storeIssued(Request $request)
    {
        $request->validate([
            'check_number' => 'required|string|unique:checks,check_number',
            'account_name' => 'required|string',
            'issue_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'check_value' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'issue_date.after_or_equal' => 'تاريخ التحرير يجب أن يكون اليوم أو بعده.',
            'due_date.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون اليوم أو بعده وتاريخ التحرير.',
            'check_value.min' => 'قيمة الشيك يجب أن تكون أكبر من أو تساوي 0.',
        ]);
    
        Check::create(array_merge($request->all(), ['check_type' => 'issued']));
    
        $issuedCheckCount = Check::where('check_type', 'issued')->count();
    
        return redirect()->route('checks.create.issued')
            ->with('success', 'تم إضافة الشيك الصادر بنجاح')
            ->with('issuedCheckCount', $issuedCheckCount);
    }
    
    
    public function createReceived()
    {
        $receivedCheckCount = Check::where('check_type', 'received')->count();
        return view('checks.Operations.create-received', compact('receivedCheckCount'));
    }

    public function storeReceived(Request $request)
    {
        $request->validate([
            'check_number' => 'required|string|unique:checks,check_number',
            'account_name' => 'required|string',
            'issue_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'check_value' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'notes' => 'nullable|string',
            'cost_center' => 'nullable|string',
        ], [
            'issue_date.after_or_equal' => 'تاريخ التحرير يجب أن يكون اليوم أو بعده.',
            'due_date.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون اليوم أو بعده وتاريخ التحرير.',
            'check_value.min' => 'قيمة الشيك يجب أن تكون أكبر من أو تساوي 0.',
        ]);
    
        // إضافة الشيك
        Check::create($request->except('cost_center') + ['check_type' => 'received']);
    
        // الحصول على عدد الشيكات الواردة، وبدء العد من 1
        $receivedCheckCount = Check::where('check_type', 'received')->count();
    
        return redirect()->route('checks.create.received')
            ->with('success', 'تم إضافة الشيك الوارد بنجاح')
            ->with('receivedCheckCount', $receivedCheckCount);
    }
    
    
    


    // عرض نموذج التعديل
public function edit($id)
{
    $check = Check::findOrFail($id);
    return view('checks.Operations.edit', compact('check'));
}

// تحديث الشيك في قاعدة البيانات
public function update(Request $request, $id)
{
    $request->validate([
        'account_name' => 'required|string|max:255',
        // 'cost_center' => 'required|string|max:255',
        'check_number' => 'required|string|max:255',
        'bank' => 'required|string|max:255',
        'issue_date' => 'required|date',
        'due_date' => 'required|date',
        'check_value' => 'required|numeric',
        'currency' => 'required|string|max:255',
        'notes' => 'nullable|string',
    ]);

    $check = Check::findOrFail($id);

    // تحقق من عدم تكرار رقم الشيك
    $existingCheck = Check::where('check_number', $request->check_number)
        ->where('id', '<>', $id) 
        ->first();

    if ($existingCheck) {
        return redirect()->back()->withErrors(['check_number' => 'رقم الشيك موجود بالفعل.'])->withInput();
    }

    // تحديث الشيك
    $check->update($request->all());

    return redirect()->route('checks.index')->with('success', 'تمت العملية بنجاح!');
}

    public function updateBank(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:checks,id',
            'bank' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $check = Check::find($request->id);

        if (!$check) {
            return response()->json(['error' => 'شيك غير موجود'], 404);
        }

        $check->bank = $request->bank;
        $check->save();

        return response()->json(['success' => 'تم تحديث اسم البنك بنجاح']);
    }


    public function destroy($id)
    {
        $check = Check::findOrFail($id);
        $check->delete();
        return redirect()->route('checks.index')->with('success', 'تم حذف الشيك بنجاح');
    }


    public function print($id)
    {
        $check = Check::findOrFail($id);
        return view('checks.Operations.print', compact('check'));
    }
}