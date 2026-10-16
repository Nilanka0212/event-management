<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\OrderOtherCharge;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');

        $orders = Order::with(['customer', 'event'])
            ->when($search, fn($q) => $q->where('order_no', 'like', "%$search%")
                ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%")))
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        $events = Event::with('customer')->whereIn('status', ['pending', 'confirmed'])->orderBy('event_date')->get();

        $selectedEvent = null;
        if ($request->event_id) {
            $selectedEvent = Event::with(['customer', 'additionalServices', 'otherCharges', 'subCategory'])->find($request->event_id);
        }

        $today = Carbon::today()->format('Y-m-d');
        return view('orders.create', compact('customers', 'services', 'events', 'selectedEvent', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'other_charges' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated, &$order) {
            $order = Order::create([
                'event_id' => $validated['event_id'],
                'customer_id' => $validated['customer_id'],
                'order_date' => $validated['order_date'],
                'discount' => $validated['discount'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'sub_total' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'balance' => 0,
            ]);

            // Add services
            if ($request->has('services')) {
                foreach ($request->services as $svc) {
                    if (!empty($svc['description'])) {
                        OrderService::create([
                            'order_id' => $order->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'] ?? 0,
                            'total_price' => ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1),
                        ]);
                    }
                }
            }

            // Add other charges
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        OrderOtherCharge::create([
                            'order_id' => $order->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                    }
                }
            }

            $order->recalculateTotals();
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'event.subCategory.category', 'services.service', 'otherCharges', 'payments']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $services = Service::where('is_active', true)->get();
        $order->load(['services', 'otherCharges', 'event']);
        return view('orders.edit', compact('order', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, $order) {
            $order->update($validated);

            // Rebuild services
            $order->services()->delete();
            if ($request->has('services')) {
                foreach ($request->services as $svc) {
                    if (!empty($svc['description'])) {
                        OrderService::create([
                            'order_id' => $order->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'] ?? 0,
                            'total_price' => ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1),
                        ]);
                    }
                }
            }

            // Rebuild other charges
            $order->otherCharges()->delete();
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        OrderOtherCharge::create([
                            'order_id' => $order->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                    }
                }
            }

            $order->recalculateTotals();
        });

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }

    public function getByEvent($eventId)
    {
        $orders = Order::with('payments')->where('event_id', $eventId)->get();
        return response()->json($orders);
    }
}
