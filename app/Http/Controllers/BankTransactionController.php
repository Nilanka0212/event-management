<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    public function index()
    {
        $transactions = BankTransaction::latest('transaction_date')->paginate(20);
        return view('bank-transactions.index', compact('transactions'));
    }

    public function create()
    {
        $today = Carbon::today()->format('Y-m-d');
        return view('bank-transactions.create', compact('today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'transaction_method' => 'required|in:cash,cheque,bank_transfer',
            'account_type' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'reference_no' => 'nullable|string|max:50',
            'cheque_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['account_type'] = $validated['account_type'] ?? ($validated['transaction_method'] === 'cash' ? 'cash' : 'bank');

        BankTransaction::create($validated);
        return redirect()->route('bank-transactions.index')->with('success', 'Transaction recorded.');
    }

    public function show(BankTransaction $bankTransaction)
    {
        return view('bank-transactions.show', compact('bankTransaction'));
    }

    public function edit(BankTransaction $bankTransaction)
    {
        return view('bank-transactions.edit', compact('bankTransaction'));
    }

    public function update(Request $request, BankTransaction $bankTransaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'transaction_method' => 'required|in:cash,cheque,bank_transfer',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $bankTransaction->update($validated);
        return redirect()->route('bank-transactions.index')->with('success', 'Transaction updated.');
    }

    public function destroy(BankTransaction $bankTransaction)
    {
        $bankTransaction->delete();
        return redirect()->route('bank-transactions.index')->with('success', 'Transaction deleted.');
    }

    public function cashBook()
    {
        $dateFrom = request('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = request('date_to', Carbon::today()->format('Y-m-d'));

        $transactions = BankTransaction::where('transaction_method', 'cash')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date')
            ->get();

        $openingBalance = BankTransaction::where('transaction_method', 'cash')
            ->where('transaction_date', '<', $dateFrom)
            ->selectRaw('SUM(CASE WHEN type="income" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        return view('bank-transactions.cash-book', compact('transactions', 'dateFrom', 'dateTo', 'openingBalance'));
    }

    public function bankBook()
    {
        $dateFrom = request('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = request('date_to', Carbon::today()->format('Y-m-d'));

        $transactions = BankTransaction::whereIn('transaction_method', ['cheque', 'bank_transfer'])
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->orderBy('transaction_date')
            ->get();

        $openingBalance = BankTransaction::whereIn('transaction_method', ['cheque', 'bank_transfer'])
            ->where('transaction_date', '<', $dateFrom)
            ->selectRaw('SUM(CASE WHEN type="income" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        return view('bank-transactions.bank-book', compact('transactions', 'dateFrom', 'dateTo', 'openingBalance'));
    }
}
