<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Cheque;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function orderPL(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::today()->format('Y-m-d'));

        $orders = Order::with(['event.additionalServices', 'event.subCategory', 'event.expenses', 'services', 'payments', 'customer'])
            ->whereBetween('order_date', [$dateFrom, $dateTo])
            ->get()
            ->map(function ($order) {
                $revenue = $order->total_amount;
                $eventExpenses = $order->event ? $order->event->expenses()->sum('amount') : 0;
                $packageCost = $order->event ? $order->event->package_cost_price : 0;
                $additionalCost = $order->event ? $order->event->additionalServices->sum('total_price') : 0;
                $totalCost = $packageCost + $additionalCost + $eventExpenses;
                return [
                    'order' => $order,
                    'revenue' => $revenue,
                    'cost' => $totalCost,
                    'profit' => $revenue - $totalCost,
                    'margin' => $revenue > 0 ? round((($revenue - $totalCost) / $revenue) * 100, 1) : 0,
                ];
            });

        $totalRevenue = $orders->sum('revenue');
        $totalCost = $orders->sum('cost');
        $totalProfit = $orders->sum('profit');

        return view('reports.order-pl', compact('orders', 'totalRevenue', 'totalCost', 'totalProfit', 'dateFrom', 'dateTo'));
    }

    public function monthlyPL(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $months = [];

        for ($m = 1; $m <= 12; $m++) {
            $revenue = Payment::whereMonth('payment_date', $m)->whereYear('payment_date', $year)->sum('amount');
            $expenses = Expense::whereMonth('expense_date', $m)->whereYear('expense_date', $year)->sum('amount');
            $months[] = [
                'month' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'revenue' => round($revenue, 2),
                'expenses' => round($expenses, 2),
                'profit' => round($revenue - $expenses, 2),
            ];
        }

        $totalRevenue = array_sum(array_column($months, 'revenue'));
        $totalExpenses = array_sum(array_column($months, 'expenses'));
        $totalProfit = $totalRevenue - $totalExpenses;

        return view('reports.monthly-pl', compact('months', 'year', 'totalRevenue', 'totalExpenses', 'totalProfit'));
    }

    public function customerCredit(Request $request)
    {
        $search = $request->get('search');

        $customers = Customer::with(['orders', 'payments'])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->get()
            ->map(function ($customer) {
                $totalBilled = $customer->orders->sum('total_amount');
                $totalPaid = $customer->payments->sum('amount');
                $balance = $totalBilled - $totalPaid;
                return [
                    'customer' => $customer,
                    'total_billed' => $totalBilled,
                    'total_paid' => $totalPaid,
                    'balance' => $balance,
                    'status' => $balance <= 0 ? 'cleared' : 'outstanding',
                ];
            })
            ->filter(fn($c) => $c['total_billed'] > 0);

        $page = $request->get('page', 1);
        $perPage = 25;
        $paginatedCustomers = new \Illuminate\Pagination\LengthAwarePaginator(
            $customers->forPage($page, $perPage),
            $customers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('reports.customer-credit', ['customers' => $paginatedCustomers, 'search' => $search]);
    }

    public function orderHistory(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status');
        $customerId = $request->get('customer_id');

        $orders = Order::with(['customer', 'event.subCategory.category', 'payments'])
            ->when($dateFrom, fn($q) => $q->where('order_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->where('order_date', '<=', $dateTo))
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get(['id', 'name']);

        return view('reports.order-history', compact('orders', 'customers', 'dateFrom', 'dateTo', 'status', 'customerId'));
    }

    public function paymentsReport(Request $request)
    {
        $search = $request->get('search');
        $customerId = $request->get('customer_id');
        $paymentMethod = $request->get('payment_method');
        $paymentType = $request->get('payment_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Payment::with(['customer', 'order.event'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('payment_no', 'like', "%{$search}%")
                        ->orWhere('cheque_no', 'like', "%{$search}%")
                        ->orWhere('bank_name', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                        ->orWhereHas('order', fn($o) => $o->where('order_no', 'like', "%{$search}%"));
                });
            })
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->when($paymentMethod, fn($q) => $q->where('payment_method', $paymentMethod))
            ->when($paymentType, fn($q) => $q->where('payment_type', $paymentType))
            ->when($dateFrom, fn($q) => $q->whereDate('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('payment_date', '<=', $dateTo));

        // Summary KPI statistics based on current filtered query
        $totalCollected = (clone $query)->sum('amount');
        $cashTotal = (clone $query)->where('payment_method', 'cash')->sum('amount');
        $bankTotal = (clone $query)->where('payment_method', 'bank_transfer')->sum('amount');
        $chequeTotal = (clone $query)->where('payment_method', 'cheque')->sum('amount');
        $totalReceipts = (clone $query)->count();

        $payments = $query->latest('payment_date')->latest('id')->paginate(25)->withQueryString();

        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone']);

        return view('reports.payments', compact(
            'payments',
            'customers',
            'search',
            'customerId',
            'paymentMethod',
            'paymentType',
            'dateFrom',
            'dateTo',
            'totalCollected',
            'cashTotal',
            'bankTotal',
            'chequeTotal',
            'totalReceipts'
        ));
    }

    public function expensesReport(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $customerId = $request->get('customer_id');
        $paymentMethod = $request->get('payment_method');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $activeTab = $request->get('tab', 'direct'); // 'direct' or 'normal'

        // Base Query
        $baseQuery = Expense::with(['event', 'order.customer'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('expense_no', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('event', fn($e) => $e->where('event_name', 'like', "%{$search}%"))
                        ->orWhereHas('order.customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($paymentMethod, fn($q) => $q->where('payment_method', $paymentMethod))
            ->when($dateFrom, fn($q) => $q->whereDate('expense_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('expense_date', '<=', $dateTo));

        // Summary Statistics
        $totalAmount = (clone $baseQuery)->sum('amount');
        $directTotal = (clone $baseQuery)->where('type', 'direct')->sum('amount');
        $normalTotal = (clone $baseQuery)->where('type', 'normal')->sum('amount');
        $monthTotal = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        // Direct Expenses Query (Filtered by customer if provided)
        $directQuery = (clone $baseQuery)->where('type', 'direct')
            ->when($customerId, function ($q) use ($customerId) {
                $q->where(function ($sub) use ($customerId) {
                    $sub->whereHas('order', fn($o) => $o->where('customer_id', $customerId))
                        ->orWhereHas('event', fn($e) => $e->where('customer_id', $customerId));
                });
            });

        $directExpenses = $directQuery->latest('expense_date')->latest('id')->paginate(20, ['*'], 'direct_page')->withQueryString();
        $directCount = (clone $directQuery)->count();

        // Normal Expenses Query
        $normalQuery = (clone $baseQuery)->where('type', 'normal');
        $normalExpenses = $normalQuery->latest('expense_date')->latest('id')->paginate(20, ['*'], 'normal_page')->withQueryString();
        $normalCount = (clone $normalQuery)->count();

        // Filter Dropdowns
        $categories = Expense::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');
        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone']);

        return view('reports.expenses', compact(
            'directExpenses',
            'normalExpenses',
            'categories',
            'customers',
            'search',
            'category',
            'customerId',
            'paymentMethod',
            'dateFrom',
            'dateTo',
            'activeTab',
            'totalAmount',
            'directTotal',
            'normalTotal',
            'monthTotal',
            'directCount',
            'normalCount'
        ));
    }

    public function transactionsReport(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::today()->format('Y-m-d'));
        $type = $request->get('type', 'all');
        $method = $request->get('method', 'all');
        $bankFilter = $request->get('bank');
        $search = $request->get('search');

        $baseQuery = BankTransaction::query()
            ->when($dateFrom, fn($q) => $q->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('transaction_date', '<=', $dateTo));

        // Overall Period KPI Statistics
        $periodIncomeTotal = (float) (clone $baseQuery)->where('type', 'income')->sum('amount');
        $periodExpenseTotal = (float) (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $totalTransactionsCount = (int) (clone $baseQuery)->count();
        $netFlow = $periodIncomeTotal - $periodExpenseTotal;

        // Cash vs Bank breakdowns
        $cashInTotal = (float) (clone $baseQuery)->where('transaction_method', 'cash')->where('type', 'income')->sum('amount');
        $cashOutTotal = (float) (clone $baseQuery)->where('transaction_method', 'cash')->where('type', 'expense')->sum('amount');
        $bankInTotal = (float) (clone $baseQuery)->whereIn('transaction_method', ['bank_transfer', 'cheque'])->where('type', 'income')->sum('amount');
        $bankOutTotal = (float) (clone $baseQuery)->whereIn('transaction_method', ['bank_transfer', 'cheque'])->where('type', 'expense')->sum('amount');

        // Filtered transactions for the report table
        $query = (clone $baseQuery);

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        if ($method && in_array($method, ['cash', 'bank_transfer', 'cheque'])) {
            $query->where('transaction_method', $method);
        }

        if ($bankFilter) {
            $query->where('bank_name', 'like', "%{$bankFilter}%");
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('cheque_no', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('transaction_no', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(25)->withQueryString();
        $banks = Bank::orderBy('bank_name')->get();

        return view('reports.transactions', compact(
            'transactions',
            'dateFrom',
            'dateTo',
            'type',
            'method',
            'bankFilter',
            'search',
            'periodIncomeTotal',
            'periodExpenseTotal',
            'netFlow',
            'totalTransactionsCount',
            'cashInTotal',
            'cashOutTotal',
            'bankInTotal',
            'bankOutTotal',
            'banks'
        ));
    }

    /**
     * Cheques Audit & Clearance Ledger Report
     */
    public function chequesReport(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::today()->format('Y-m-d'));
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $bankId = $request->get('bank_id');
        $search = $request->get('search');

        $baseQuery = Cheque::with(['customer', 'order.event', 'bank', 'payment', 'expense'])
            ->whereBetween('received_or_issued_date', [$dateFrom, $dateTo]);

        // Totals across the period
        $periodChequesCount = (clone $baseQuery)->count();
        $inTotal = (float) (clone $baseQuery)->where('type', 'IN')->sum('amount');
        $outTotal = (float) (clone $baseQuery)->where('type', 'OUT')->sum('amount');
        $realizedTotal = (float) (clone $baseQuery)->where('status', 'Realized')->sum('amount');
        $pendingTotal = (float) (clone $baseQuery)->whereIn('status', ['In Hand', 'Deposited', 'Issued'])->sum('amount');
        $returnedTotal = (float) (clone $baseQuery)->where('status', 'Returned')->sum('amount');

        // Filtered query for display
        $query = (clone $baseQuery);

        if ($type && in_array(strtoupper($type), ['IN', 'OUT'])) {
            $query->where('type', strtoupper($type));
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($bankId) {
            $query->where('bank_id', $bankId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('cheque_number', 'like', "%{$search}%")
                  ->orWhere('payer_bank', 'like', "%{$search}%")
                  ->orWhere('payee_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('order', fn($o) => $o->where('order_no', 'like', "%{$search}%"));
            });
        }

        $cheques = $query->orderBy('received_or_issued_date', 'desc')->orderBy('id', 'desc')->paginate(25)->withQueryString();
        $banks = Bank::orderBy('bank_name')->get();

        return view('reports.cheques', compact(
            'cheques',
            'dateFrom',
            'dateTo',
            'type',
            'status',
            'bankId',
            'search',
            'periodChequesCount',
            'inTotal',
            'outTotal',
            'realizedTotal',
            'pendingTotal',
            'returnedTotal',
            'banks'
        ));
    }
}
