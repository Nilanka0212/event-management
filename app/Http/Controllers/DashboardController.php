<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Summary stats
        $totalCustomers = Customer::count();
        $totalEvents = Event::count();
        $totalOrders = Order::count();
        $pendingPayments = Order::where('payment_status', '!=', 'paid')->sum('balance');

        // This month revenue
        $monthlyRevenue = Payment::whereMonth('payment_date', $today->month)
            ->whereYear('payment_date', $today->year)
            ->sum('amount');

        // This month expenses
        $monthlyExpenses = Expense::whereMonth('expense_date', $today->month)
            ->whereYear('expense_date', $today->year)
            ->sum('amount');

        // Upcoming events (next 30 days)
        $upcomingEvents = Event::with(['customer', 'subCategory.category'])
            ->whereBetween('event_date', [$today, $today->copy()->addDays(30)])
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date')
            ->limit(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['customer', 'event'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::with(['customer', 'order'])
            ->latest()
            ->limit(5)
            ->get();

        // Event notifications (7 days and 3 days)
        $notifications7Days = Event::with('customer')
            ->whereDate('event_date', $today->copy()->addDays(7))
            ->where('notification_7_sent', false)
            ->where('status', '!=', 'cancelled')
            ->get();

        $notifications3Days = Event::with('customer')
            ->whereDate('event_date', $today->copy()->addDays(3))
            ->where('notification_3_sent', false)
            ->where('status', '!=', 'cancelled')
            ->get();

        $notificationCount = $notifications7Days->count() + $notifications3Days->count();

        // Monthly revenue chart (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $revenue = Payment::whereMonth('payment_date', $month->month)
                ->whereYear('payment_date', $month->year)
                ->sum('amount');
            $expenses = Expense::whereMonth('expense_date', $month->month)
                ->whereYear('expense_date', $month->year)
                ->sum('amount');
            $chartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => round($revenue, 2),
                'expenses' => round($expenses, 2),
                'profit' => round($revenue - $expenses, 2),
            ];
        }

        return view('dashboard', compact(
            'totalCustomers', 'totalEvents', 'totalOrders', 'pendingPayments',
            'monthlyRevenue', 'monthlyExpenses', 'upcomingEvents', 'recentOrders',
            'recentPayments', 'notifications7Days', 'notifications3Days',
            'notificationCount', 'chartData'
        ));
    }
}
