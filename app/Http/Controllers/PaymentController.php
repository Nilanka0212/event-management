<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Bank;
use App\Models\BankTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return $this->create($request);
    }

    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $orders = Order::with(['customer', 'event', 'payments'])->latest()->get();
        $banks = Bank::where('is_active', true)->orderBy('bank_name')->get();
        $selectedOrder = null;
        $selectedCustomer = null;

        if ($request->order_id) {
            $selectedOrder = Order::with(['customer', 'event', 'payments'])->find($request->order_id);
            $selectedCustomer = $selectedOrder?->customer;
        }

        $today = Carbon::today()->format('Y-m-d');
        return view('payments.create', compact('customers', 'orders', 'banks', 'selectedOrder', 'selectedCustomer', 'today'));
    }

    public function createForOrder($orderId)
    {
        return redirect()->route('payments.index', ['order_id' => $orderId]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:advance,partial,full',
            'payment_method' => 'required|in:cash,cheque,bank_transfer',
            'cheque_no' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $payment = Payment::create($validated);

            // Record in bank transactions
            BankTransaction::create([
                'type' => 'income',
                'transaction_method' => $validated['payment_method'],
                'account_type' => $validated['payment_method'] === 'cash' ? 'cash' : ($validated['bank_name'] ?? 'bank'),
                'amount' => $validated['amount'],
                'description' => 'Payment received - Order: ' . $payment->order->order_no,
                'cheque_no' => $validated['cheque_no'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'transaction_date' => $validated['payment_date'],
                'reference_no' => $payment->payment_no,
            ]);
        });

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['customer', 'order.event']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:advance,partial,full',
            'payment_method' => 'required|in:cash,cheque,bank_transfer',
            'cheque_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $payment->update($validated);
        $payment->order->recalculateTotals();
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $order = $payment->order;
        $payment->delete();
        $order->recalculateTotals();
        return redirect()->route('payments.index')->with('success', 'Payment deleted.');
    }
}
