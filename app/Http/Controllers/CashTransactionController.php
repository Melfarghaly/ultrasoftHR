<?php

namespace App\Http\Controllers;

use App\CashTransaction;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactionType = $request->input('transaction_type');
        $documentDateFrom = $request->input('document_date_from');
        $documentDateTo = $request->input('document_date_to');

        $query = CashTransaction::query();

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        if ($documentDateFrom) {
            $query->whereDate('document_date', '>=', $documentDateFrom);
        }

        if ($documentDateTo) {
            $query->whereDate('document_date', '<=', $documentDateTo);
        }

        $transactions = $query->get();

        $cashDepositCount = CashTransaction::where('transaction_type', 'deposit')->count();
        $cashWithdrawalCount = CashTransaction::where('transaction_type', 'withdrawal')->count();

        return view('general_accounts.cash_transactions.index', compact('transactions', 'cashDepositCount', 'cashWithdrawalCount'));
    }


    private function generateDocumentNumber($type)
    {
        // Generate a unique document number
        $lastTransaction = CashTransaction::where('transaction_type', $type)
            ->orderBy('document_number', 'desc')
            ->first();

        $lastNumber = $lastTransaction ? intval(substr($lastTransaction->document_number, -4)) : 0;
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $type . '-' . date('Ymd') . '-' . $nextNumber;
    }

    public function createDeposit()
    {
        $documentNumber = $this->generateDocumentNumber('deposit');

        $cashDepositCount = CashTransaction::where('transaction_type', 'deposit')->count();

        $cashWithdrawalCount = CashTransaction::where('transaction_type', 'withdrawal')->count();

        return view('general_accounts.cash_transactions.create_cash_deposit', compact('documentNumber', 'cashDepositCount', 'cashWithdrawalCount'));
    }

    public function createWithdrawal()
    {
        $cashDepositCount = CashTransaction::where('transaction_type', 'deposit')->count();

        $cashWithdrawalCount = CashTransaction::where('transaction_type', 'withdrawal')->count();

        return view('general_accounts.cash_transactions.create_cash_withdrawal', compact('cashDepositCount', 'cashWithdrawalCount'));
    }

    public function storeDeposit(Request $request)
    {
        $validated = $request->validate([
            'document_date' => 'required|date',
            'currency' => 'required|string',
            'amount' => 'required|numeric',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $documentNumber = $this->generateDocumentNumber('deposit');
            $deposit = CashTransaction::create(array_merge($validated, [
                'transaction_type' => 'deposit',
                'document_number' => $documentNumber
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الإيداع النقدي بنجاح.',
                'documentNumber' => $documentNumber,
                'depositCount' => CashTransaction::where('transaction_type', 'deposit')->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الإيداع: ' . $e->getMessage()
            ]);
        }
    }

    public function storeWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'document_date' => 'required|date',
            'currency' => 'required|string',
            'amount' => 'required|numeric',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $documentNumber = $this->generateDocumentNumber('withdrawal');
            $withdrawal = CashTransaction::create(array_merge($validated, [
                'transaction_type' => 'withdrawal',
                'document_number' => $documentNumber
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ السحب النقدي بنجاح.',
                'documentNumber' => $documentNumber,
                'withdrawalCount' => CashTransaction::where('transaction_type', 'withdrawal')->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ السحب: ' . $e->getMessage()
            ]);
        }
    }

    public function show(CashTransaction $cashTransaction)
    {
        return view('general_accounts.cash_transactions.show', compact('cashTransaction'));
    }

    public function edit(CashTransaction $cashTransaction, $actionType = null)
    {
        $cashDepositCount = CashTransaction::where('transaction_type', 'deposit')->count();
        $cashWithdrawalCount = CashTransaction::where('transaction_type', 'withdrawal')->count();
        
        // Determine the transaction type based on the actionType or the cashTransaction itself
        $transactionType = $actionType ? $actionType : $cashTransaction->transaction_type;
    
        return view('general_accounts.cash_transactions.edit', compact('cashTransaction', 'transactionType', 'cashDepositCount', 'cashWithdrawalCount'));
    }
    


    public function update(Request $request, $id)
    {
        $request->validate([
            // 'document_number' => 'required|unique:cash_transactions,document_number,' . $cashTransaction->id,
            'document_date' => 'required|date',
            'currency' => 'nullable|string',
            'amount' => 'required|numeric',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cashTransaction = cashTransaction::findOrFail($id);
        $cashTransaction->update($request->all());

        return redirect()->route('cash_transactions.index')->with('success', 'تم تعديل المعاملة بنجاح.');
    }


    public function destroy(CashTransaction $cashTransaction)
    {
        $cashTransaction->delete();

        return redirect()->route('cash_withdrawals.index')->with('success', 'تم حذف المعاملة بنجاح.');
    }


    public function report(Request $request)
    {
        // Retrieve filter parameters from the request
        $transactionType = $request->input('transaction_type');
        $documentDateFrom = $request->input('document_date_from');
        $documentDateTo = $request->input('document_date_to');
        $bankName = $request->input('bank_name');

        $query = CashTransaction::query();

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        if ($documentDateFrom) {
            $query->whereDate('document_date', '>=', $documentDateFrom);
        }

        if ($documentDateTo) {
            $query->whereDate('document_date', '<=', $documentDateTo);
        }

        if ($bankName) {
            $query->where('bank_name', $bankName);
        }

        $transactions = $query->get();

        // Add notes based on transaction type
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === 'deposit') {
                $transaction->notes = "تم إنشاء إيداع في البنك بقيمة " . number_format($transaction->amount, 2) . " " . $transaction->currency;
            } elseif ($transaction->transaction_type === 'withdrawal') {
                $transaction->notes = "تم إنشاء سحب من البنك بقيمة " . number_format($transaction->amount, 2) . " " . $transaction->currency;
            }
        }

        // Optionally, add additional data to be passed to the view
        $cashDepositCount = CashTransaction::where('transaction_type', 'deposit')->count();
        $cashWithdrawalCount = CashTransaction::where('transaction_type', 'withdrawal')->count();
        $bankNames = CashTransaction::distinct()->pluck('bank_name');

        return view('general_accounts.cash_transactions.report', compact('transactions', 'cashDepositCount', 'cashWithdrawalCount', 'bankNames'));
    }
    public function printReport(Request $request)
    {
        $transactionType = $request->input('transaction_type');
        $documentDateFrom = $request->input('document_date_from');
        $documentDateTo = $request->input('document_date_to');
        $bankName = $request->input('bank_name');

        $query = CashTransaction::query();

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        if ($documentDateFrom) {
            $query->whereDate('document_date', '>=', $documentDateFrom);
        }

        if ($documentDateTo) {
            $query->whereDate('document_date', '<=', $documentDateTo);
        }

        if ($bankName) {
            $query->where('bank_name', $bankName);
        }

        $transactions = $query->get();

        // Add notes based on transaction type
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === 'deposit') {
                $transaction->notes = "تم إنشاء إيداع في البنك بقيمة " . number_format($transaction->amount, 2) . " " . $transaction->currency;
            } elseif ($transaction->transaction_type === 'withdrawal') {
                $transaction->notes = "تم إنشاء سحب من البنك بقيمة " . number_format($transaction->amount, 2) . " " . $transaction->currency;
            }
        }

        $totalDeposits = $transactions->where('transaction_type', 'deposit')->sum('amount');
        $totalWithdrawals = $transactions->where('transaction_type', 'withdrawal')->sum('amount');

        $initialBalance = 0;  // تعديل الرصيد السابق حسب الحاجة
        $balanceStartPeriod = $initialBalance + $totalDeposits;
        $balanceEndPeriod = $balanceStartPeriod - $totalWithdrawals;

        return view('general_accounts.cash_transactions.print_report', compact(
            'transactions',
            'totalDeposits',
            'totalWithdrawals',
            'balanceStartPeriod',
            'balanceEndPeriod'
        ));
    }
}
