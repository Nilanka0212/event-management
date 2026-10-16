<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $search = request('search');
        $customers = Customer::when($search, fn($q) => $q->where('name', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%"))
            ->latest()
            ->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'nic' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);
        $customer = Customer::create($validated);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'customer' => $customer]);
        }
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['events', 'orders.payments']);
        $totalBilled = $customer->orders()->sum('total_amount');
        $totalPaid = $customer->payments()->sum('amount');
        $totalBalance = $totalBilled - $totalPaid;
        return view('customers.show', compact('customer', 'totalBilled', 'totalPaid', 'totalBalance'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'nic' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        $customer = Customer::create($validated);
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $customers = Customer::where('name', 'like', "%$query%")
            ->orWhere('phone', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email']);
        return response()->json($customers);
    }
}
