<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventService;
use App\Models\EventOtherCharge;
use App\Models\EventCategory;
use App\Models\EventSubCategory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');

        $events = Event::with(['customer', 'subCategory.category'])
            ->when($search, fn($q) => $q->where('event_name', 'like', "%$search%")
                ->orWhere('event_no', 'like', "%$search%")
                ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%")))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        $today = Carbon::today()->format('Y-m-d');
        return view('events.create', compact('categories', 'customers', 'services', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_sub_category_id' => 'required|exists:event_sub_categories,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'today_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'package_sell_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $subCategory = EventSubCategory::findOrFail($validated['event_sub_category_id']);

        $validated['package_cost_price'] = $subCategory->cost_price;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['advance_payment'] = $validated['advance_payment'] ?? 0;

        DB::transaction(function () use ($request, $validated, &$event) {
            $event = Event::create($validated);

            // Add additional services
            if ($request->has('additional_services')) {
                foreach ($request->additional_services as $svc) {
                    if (!empty($svc['description'])) {
                        EventService::create([
                            'event_id' => $event->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'],
                            'total_price' => ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1),
                        ]);
                    }
                }
            }

            // Add other charges
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        EventOtherCharge::create([
                            'event_id' => $event->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                    }
                }
            }

            $event->recalculateTotals();

            // Auto-create order if advance payment > 0
            if ($validated['advance_payment'] > 0) {
                $this->autoCreateOrder($event, $validated['advance_payment']);
            }
        });

        return redirect()->route('events.show', $event)->with('success', 'Event registered successfully!');
    }

    public function show(Event $event)
    {
        $event->load(['customer', 'subCategory.category', 'additionalServices.service', 'otherCharges', 'orders.payments']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = EventCategory::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->get();
        $event->load(['subCategory', 'additionalServices.service', 'otherCharges']);
        return view('events.edit', compact('event', 'categories', 'customers', 'services'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_sub_category_id' => 'required|exists:event_sub_categories,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'today_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'package_sell_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $subCategory = EventSubCategory::findOrFail($validated['event_sub_category_id']);
        $validated['package_cost_price'] = $subCategory->cost_price;

        DB::transaction(function () use ($request, $validated, $event) {
            $event->update($validated);

            // Rebuild additional services
            $event->additionalServices()->delete();
            if ($request->has('additional_services')) {
                foreach ($request->additional_services as $svc) {
                    if (!empty($svc['description'])) {
                        EventService::create([
                            'event_id' => $event->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'],
                            'total_price' => ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1),
                        ]);
                    }
                }
            }

            // Rebuild other charges
            $event->otherCharges()->delete();
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        EventOtherCharge::create([
                            'event_id' => $event->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                    }
                }
            }

            $event->recalculateTotals();
        });

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }

    public function createOrder(Event $event)
    {
        $existingOrder = $event->orders()->where('is_auto_created', false)->first();
        if ($existingOrder) {
            return redirect()->route('orders.show', $existingOrder)
                ->with('info', 'An order already exists for this event.');
        }
        return redirect()->route('orders.create', ['event_id' => $event->id]);
    }

    private function autoCreateOrder(Event $event, float $advanceAmount): Order
    {
        $order = Order::create([
            'event_id' => $event->id,
            'customer_id' => $event->customer_id,
            'order_date' => $event->today_date,
            'sub_total' => $event->total_amount,
            'discount' => 0,
            'total_amount' => $event->total_amount,
            'paid_amount' => $advanceAmount,
            'balance' => $event->total_amount - $advanceAmount,
            'payment_status' => $advanceAmount >= $event->total_amount ? 'paid' : 'partial',
            'notes' => 'Auto-created from event registration advance payment.',
            'is_auto_created' => true,
        ]);

        // Record payment
        \App\Models\Payment::create([
            'order_id' => $order->id,
            'customer_id' => $event->customer_id,
            'amount' => $advanceAmount,
            'payment_type' => 'advance',
            'payment_method' => 'cash',
            'payment_date' => $event->today_date,
            'notes' => 'Advance payment during event registration.',
        ]);

        return $order;
    }
}
