<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Customer;
use App\Models\Event;
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

        return view('reports.customer-credit', compact('customers', 'search'));
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
            ->paginate(20);

        $customers = Customer::orderBy('name')->get(['id', 'name']);

        return view('reports.order-history', compact('orders', 'customers', 'dateFrom', 'dateTo', 'status', 'customerId'));
    }
}
