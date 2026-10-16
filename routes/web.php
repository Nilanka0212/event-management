<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventSubCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Masters
Route::resource('services', ServiceController::class);
Route::resource('event-categories', EventCategoryController::class);
Route::resource('event-sub-categories', EventSubCategoryController::class);
Route::get('event-sub-categories/{subCategory}/services', [EventSubCategoryController::class, 'getServices'])->name('event-sub-categories.services');
Route::get('api/sub-category/{id}', [EventSubCategoryController::class, 'apiGetSubCategory'])->name('api.sub-category');
Route::get('api/sub-categories/{categoryId}', [EventSubCategoryController::class, 'apiGetByCategory'])->name('api.sub-categories');

// Customers
Route::resource('customers', CustomerController::class);
Route::post('customers/quick-store', [CustomerController::class, 'quickStore'])->name('customers.quick-store');
Route::get('api/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');

// Events
Route::resource('events', EventController::class);
Route::get('events/{event}/create-order', [EventController::class, 'createOrder'])->name('events.create-order');

// Orders
Route::resource('orders', OrderController::class);
Route::get('api/orders/event/{eventId}', [OrderController::class, 'getByEvent'])->name('api.orders.event');

// Payments
Route::resource('payments', PaymentController::class);
Route::get('payments/create/{orderId}', [PaymentController::class, 'createForOrder'])->name('payments.create-for-order');

// Quotations
Route::resource('quotations', QuotationController::class);
Route::get('quotations/{quotation}/print', [QuotationController::class, 'print'])->name('quotations.print');
Route::get('quotations/{quotation}/convert-to-event', [QuotationController::class, 'convertToEvent'])->name('quotations.convert-to-event');

// Expenses
Route::resource('expenses', ExpenseController::class);

// Bank/Cash Book
Route::resource('bank-transactions', BankTransactionController::class);
Route::get('cash-book', [BankTransactionController::class, 'cashBook'])->name('cash-book');
Route::get('bank-book', [BankTransactionController::class, 'bankBook'])->name('bank-book');

// Calendar
Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('api/calendar/events', [CalendarController::class, 'getEvents'])->name('api.calendar.events');

// Reports
Route::get('reports/order-pl', [ReportController::class, 'orderPL'])->name('reports.order-pl');
Route::get('reports/monthly-pl', [ReportController::class, 'monthlyPL'])->name('reports.monthly-pl');
Route::get('reports/customer-credit', [ReportController::class, 'customerCredit'])->name('reports.customer-credit');
Route::get('reports/order-history', [ReportController::class, 'orderHistory'])->name('reports.order-history');

// Notifications
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
