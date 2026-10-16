<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $type = request('type');
        $expenses = Expense::with('event')
            ->when($type, fn($q) => $q->where('type', $type))
            ->latest('expense_date')
            ->paginate(15);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $events = Event::whereIn('status', ['pending', 'confirmed'])->orderBy('event_date')->get();
        $today = Carbon::today()->format('Y-m-d');
        return view('expenses.create', compact('events', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:direct,normal',
            'category' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'event_id' => 'nullable|exists:events,id',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $events = Event::whereIn('status', ['pending', 'confirmed', 'completed'])->get();
        return view('expenses.edit', compact('expense', 'events'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'type' => 'required|in:direct,normal',
            'category' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'event_id' => 'nullable|exists:events,id',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
