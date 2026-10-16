<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationService;
use App\Models\QuotationOtherCharge;
use App\Models\Customer;
use App\Models\EventSubCategory;
use App\Models\EventCategory;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->paginate(15);
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $categories = EventCategory::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $today = Carbon::today()->format('Y-m-d');
        return view('quotations.create', compact('customers', 'categories', 'services', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'event_sub_category_id' => 'nullable|exists:event_sub_categories,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'event_name' => 'nullable|string|max:255',
            'event_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, &$quotation) {
            $quotation = Quotation::create($validated);

            $total = 0;
            if ($request->has('services')) {
                foreach ($request->services as $svc) {
                    if (!empty($svc['description'])) {
                        $lineTotal = ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1);
                        QuotationService::create([
                            'quotation_id' => $quotation->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'] ?? 0,
                            'total_price' => $lineTotal,
                        ]);
                        $total += $lineTotal;
                    }
                }
            }

            $chargesTotal = 0;
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        QuotationOtherCharge::create([
                            'quotation_id' => $quotation->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                        $chargesTotal += $charge['amount'] ?? 0;
                    }
                }
            }

            $subTotal = $total + $chargesTotal;
            $discount = $validated['discount'] ?? 0;
            $quotation->update([
                'sub_total' => $subTotal,
                'total_amount' => $subTotal - $discount,
            ]);
        });

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation created!');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'subCategory.category', 'services.service', 'otherCharges']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $categories = EventCategory::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $quotation->load(['services', 'otherCharges']);
        return view('quotations.edit', compact('quotation', 'customers', 'categories', 'services'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'valid_until' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,accepted,rejected',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $validated, $quotation) {
            $quotation->services()->delete();
            $quotation->otherCharges()->delete();

            $total = 0;
            if ($request->has('services')) {
                foreach ($request->services as $svc) {
                    if (!empty($svc['description'])) {
                        $lineTotal = ($svc['unit_price'] ?? 0) * ($svc['qty'] ?? 1);
                        QuotationService::create([
                            'quotation_id' => $quotation->id,
                            'service_id' => $svc['service_id'] ?? null,
                            'description' => $svc['description'],
                            'qty' => $svc['qty'] ?? 1,
                            'unit_price' => $svc['unit_price'] ?? 0,
                            'total_price' => $lineTotal,
                        ]);
                        $total += $lineTotal;
                    }
                }
            }

            $chargesTotal = 0;
            if ($request->has('other_charges')) {
                foreach ($request->other_charges as $charge) {
                    if (!empty($charge['description'])) {
                        QuotationOtherCharge::create([
                            'quotation_id' => $quotation->id,
                            'description' => $charge['description'],
                            'amount' => $charge['amount'] ?? 0,
                        ]);
                        $chargesTotal += $charge['amount'] ?? 0;
                    }
                }
            }

            $subTotal = $total + $chargesTotal;
            $discount = $validated['discount'] ?? 0;
            $validated['sub_total'] = $subTotal;
            $validated['total_amount'] = $subTotal - $discount;
            $quotation->update($validated);
        });

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated!');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted.');
    }

    public function print(Quotation $quotation)
    {
        $quotation->load(['customer', 'subCategory.category', 'services.service', 'otherCharges']);
        return view('quotations.print', compact('quotation'));
    }

    public function convertToEvent(Quotation $quotation)
    {
        $quotation->update(['status' => 'accepted']);
        return redirect()->route('events.create', ['quotation_id' => $quotation->id])
            ->with('info', 'Quotation loaded. Complete event registration.');
    }
}
