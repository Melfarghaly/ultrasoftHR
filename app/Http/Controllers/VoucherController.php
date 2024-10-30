<?php

namespace App\Http\Controllers;

use App\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $voucherType = $request->query('voucher_type', '');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
    
        $query = Voucher::query();
    
        if ($voucherType) {
            $query->where('voucher_type', $voucherType);
        }
    
        if ($dateFrom) {
            $query->whereDate('voucher_date', '>=', $dateFrom);
        }
    
        if ($dateTo) {
            $query->whereDate('voucher_date', '<=', $dateTo);
        }
    
        $vouchers = $query->get();
    
        // Count cash receipts and disbursements
        $cashReceiptCount = Voucher::where('voucher_type', 'cash_receipt')->count();
        $cashDisbursementCount = Voucher::where('voucher_type', 'cash_disbursement')->count();
    
        return view('general_accounts.vouchers.index', compact('vouchers', 'cashReceiptCount', 'cashDisbursementCount'));
    }
    
    

    public function generateUniqueVoucherNumber()
    {
        $lastVoucher = Voucher::orderBy('voucher_number', 'desc')->first();
        $nextNumber = $lastVoucher ? (int)$lastVoucher->voucher_number + 1 : 1;
        
        while (Voucher::where('voucher_number', $nextNumber)->exists()) {
            $nextNumber++;
        }
        
        return str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // مثال: ملء الأصفار إلى 4 أرقام
    }
    public function createCashReceipt()
    {
        $receivedCashCount = Voucher::where('voucher_type', 'cash_receipt')->count();
        return view('general_accounts.vouchers.create_cash_receipt', compact('receivedCashCount'));
    }
    public function createCashDisbursement()
    {
        $disbursedCashCount = Voucher::where('voucher_type', 'cash_disbursement')->count();
        return view('general_accounts.vouchers.create_cash_disbursement', compact('disbursedCashCount'));
    }
    public function storeCashDisbursement(Request $request)
    {
        $request->validate([
            'voucher_date' => 'required|date',
            'currency' => 'required|string|max:10',
            'amount' => 'required|numeric',
            'cash_drawer' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);
    
        try {
            $voucherNumber = $this->generateUniqueVoucherNumber();
    
            Voucher::create([
                'voucher_number' => $voucherNumber,
                'voucher_date' => $request->voucher_date,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'cash_drawer' => $request->cash_drawer,
                'account_name' => $request->account_name,
                'voucher_type' => 'cash_disbursement',
                'notes' => $request->notes,
            ]);
    
            return response()->json(['success' => true, 'message' => 'سند الصرف تم حفظه بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حفظ السند.']);
        }
    }
    public function storeCashReceipt(Request $request)
    {
        $request->validate([
            'voucher_date' => 'required|date',
            'currency' => 'required|string|max:10',
            'amount' => 'required|numeric',
            'cash_drawer' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            $voucherNumber = $this->generateUniqueVoucherNumber();
            Voucher::create([
                'voucher_number' => $voucherNumber,
                'voucher_date' => $request->voucher_date,
                'currency' => $request->currency,
                'amount' => $request->amount,
                'cash_drawer' => $request->cash_drawer,
                'account_name' => $request->account_name,
                'voucher_type' => 'cash_receipt',
                'notes' => $request->notes,
            ]);

            return response()->json(['success' => true, 'message' => 'سند الاستلام تم حفظه بنجاح.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حفظ السند.']);
        }
    }

    public function edit(Voucher $voucher)
    {
        return view('general_accounts.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'voucher_number' => 'required|string|max:255',
            'voucher_date' => 'required|date',
            'currency' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'cash_drawer' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'voucher_type' => 'nullable|string|max:255',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());

        return response()->json(['success' => true, 'message' => 'تم تعديل السند بنجاح']);
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'تم حذف السند بنجاح.');
    }

    public function report(Request $request)
    {
        // Retrieve filter parameters from the request
        $voucherType = $request->query('voucher_type', '');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
    
        // Build the query based on filters
        $query = Voucher::query();
    
        if ($voucherType) {
            $query->where('voucher_type', $voucherType);
        }
    
        if ($dateFrom) {
            $query->whereDate('voucher_date', '>=', $dateFrom);
        }
    
        if ($dateTo) {
            $query->whereDate('voucher_date', '<=', $dateTo);
        }
    
        $vouchers = $query->get();
    
        // Add notes based on voucher type
        foreach ($vouchers as $voucher) {
            if ($voucher->voucher_type === 'cash_disbursement') {
                $voucher->notes = "تم إنشاء سند صرف بقيمة " . number_format($voucher->amount, 2) . " " . $voucher->currency;
            } elseif ($voucher->voucher_type === 'cash_receipt') {
                $voucher->notes = "تم إنشاء سند استلام بقيمة " . number_format($voucher->amount, 2) . " " . $voucher->currency;
            }
        }
    
        // Optionally, add additional data to be passed to the view
        $cashDisbursementCount = Voucher::where('voucher_type', 'cash_disbursement')->count();
        $cashReceiptCount = Voucher::where('voucher_type', 'cash_receipt')->count();
    
        return view('general_accounts.vouchers.report', compact('vouchers', 'cashDisbursementCount', 'cashReceiptCount'));
    }
    

    public function printReport(Request $request)
{
    $voucherType = $request->input('voucher_type');
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');

    $query = Voucher::query();

    if ($voucherType) {
        $query->where('voucher_type', $voucherType);
    }

    if ($dateFrom) {
        $query->whereDate('voucher_date', '>=', $dateFrom);
    }

    if ($dateTo) {
        $query->whereDate('voucher_date', '<=', $dateTo);
    }

    $vouchers = $query->get();

    // Add notes based on voucher type
    foreach ($vouchers as $voucher) {
        if ($voucher->voucher_type === 'cash_disbursement') {
            $voucher->notes = "تم إنشاء سند صرف بقيمة " . number_format($voucher->amount, 2) . " " . $voucher->currency;
        } elseif ($voucher->voucher_type === 'cash_receipt') {
            $voucher->notes = "تم إنشاء سند استلام بقيمة " . number_format($voucher->amount, 2) . " " . $voucher->currency;
        }
    }

    $totalDisbursements = $vouchers->where('voucher_type', 'cash_disbursement')->sum('amount');
    $totalReceipts = $vouchers->where('voucher_type', 'cash_receipt')->sum('amount');

    $initialBalance = 0;  // تعديل الرصيد السابق حسب الحاجة
    $balanceStartPeriod = $initialBalance + $totalReceipts;
    $balanceEndPeriod = $balanceStartPeriod - $totalDisbursements;

    return view('general_accounts.vouchers.print_report', compact(
        'vouchers',
        'totalDisbursements',
        'totalReceipts',
        'balanceStartPeriod',
        'balanceEndPeriod'
    ));
}

}
