<?php

namespace App\Http\Controllers;

use App\CheckTransaction;
use Illuminate\Http\Request;
use App\Check;

class CheckTransactionController extends Controller
{
    public function index(Request $request)
{
    $transactions = CheckTransaction::all();
    $checkType = $request->query('check_type', '');
    
    $query = Check::query();
    
    if ($checkType) {
        $query->where('check_type', $checkType);
    }
    
    // تصفية الشيكات التي تحتوي على حركات
    $query->whereDoesntHave('transactions');
    
    if ($request->filled('status')) {
        $status = $request->query('status');
        $query->where('status', $status);
    }
    
    if ($request->filled('issue_date_from')) {
        $query->whereDate('issue_date', '>=', $request->query('issue_date_from'));
    }
    
    if ($request->filled('issue_date_to')) {
        $query->whereDate('issue_date', '<=', $request->query('issue_date_to'));
    }
    
    if ($request->filled('due_date_from')) {
        $query->whereDate('due_date', '>=', $request->query('due_date_from'));
    }
    
    if ($request->filled('due_date_to')) {
        $query->whereDate('due_date', '<=', $request->query('due_date_to'));
    }
    
    $checks = $query->with('transactions')->get();
    
    $issuedCheckCount = Check::where('check_type', 'issued')->whereDoesntHave('transactions')->count();
    $receivedCheckCount = Check::where('check_type', 'received')->whereDoesntHave('transactions')->count();
    
    return view('checks.check_movement.index', compact('checks', 'checkType', 'issuedCheckCount', 'receivedCheckCount', 'transactions'));
}

    
    public function save(Request $request)
    {
        $data = $request->input('checks', []);
    
        foreach ($data as $checkId => $fields) {
            $check = Check::find($checkId);
    
            if ($check) {
                if (isset($fields['action']) && $fields['action'] === 'cancel_check') {
                    // إلغاء حركة الشيك
                    CheckTransaction::where('check_id', $checkId)->delete();
                    $check->update(['status' => 'cancelled']); // تحديث الحالة إلى ملغاة
                }
    
                if (isset($fields['action']) && $fields['action'] === 'bank_receipt' && isset($fields['bank_name']) && !empty($fields['bank_name'])) {
                    $check->update([
                        'check_type' => 'under_collection',
                        'status' => 'processed', // تحديث الحالة إلى معالجة
                    ]);
    
                    CheckTransaction::updateOrCreate(
                        ['check_id' => $checkId],
                        [
                            'bank' => $fields['bank_name'] ?? null,
                            'cashbox' => $fields['vault_name'] ?? null,
                            'account' => $fields['account_type'] ?? null,
                            'amount' => $fields['amount'] ?? 0.00,
                            'type' => $fields['action'] ?? 'undefined',
                        ]
                    );
                    continue;
                }
    
                if (isset($fields['bank_name']) || isset($fields['vault_name']) || isset($fields['account_type']) || isset($fields['amount'])) {
                    $transactionType = $fields['action'] ?? 'undefined';
    
                    CheckTransaction::updateOrCreate(
                        ['check_id' => $checkId],
                        [
                            'bank' => $fields['bank_name'] ?? null,
                            'cashbox' => $fields['vault_name'] ?? null,
                            'account' => $fields['account_type'] ?? null,
                            'amount' => $fields['amount'] ?? 0.00,
                            'transaction_date' => now(),
                            'type' => $transactionType,
                        ]
                    );
    
                    $check->update([
                        'check_type' => $fields['check_type'] ?? $check->check_type,
                        'status' => 'processed', // تحديث الحالة إلى معالجة
                    ]);
                }
            }
        }
    
        return redirect()->route('checks.movementChecks')->with('success', 'تم حفظ التعديلات بنجاح');
    }
    
    public function report(Request $request)
    {
        $checkType = $request->query('check_type', '');
    
        $query = Check::query();
    
        if ($checkType) {
            $query->where('check_type', $checkType);
        }
    
        if ($request->filled('issue_date_from')) {
            $query->whereDate('issue_date', '>=', $request->input('issue_date_from'));
        }
    
        if ($request->filled('issue_date_to')) {
            $query->whereDate('issue_date', '<=', $request->input('issue_date_to'));
        }
    
        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->input('due_date_from'));
        }
    
        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->input('due_date_to'));
        }
    
        // استبعاد الشيكات التي لا تحتوي على حركة
        $query->whereHas('transactions');
    
        $checks = $query->with('transactions')->get();
    
        return view('checks.check_movement.movement-reports', compact('checks', 'checkType'));
    }
    
    public function getTransactionTypeText($type)
    {
        $types = [
            'issue_check' => 'صرف الشيك البنكي',
            'receive_check' => 'استلام الشيك النقدي',
            'cancel_check' => 'إلغاء الشيك',
            'bank_receipt' => 'حافظة بنك',
            'endorse_check' => 'تظهير'
        ];

        return $types[$type] ?? 'غير محدد';
    }

    public function destroy($id)
    {
        $transaction = CheckTransaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => 'تم حذف الحركة بنجاح.']);
    }
}
