<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::withCount('subCategories')->latest()->paginate(25)->withQueryString();
        return view('event-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('event-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = $request->has('is_active');
        EventCategory::create($validated);
        return redirect()->route('event-categories.index')->with('success', 'Event category created successfully.');
    }

    public function show(EventCategory $eventCategory)
    {
        $eventCategory->load('subCategories');
        return view('event-categories.show', compact('eventCategory'));
    }

    public function edit(EventCategory $eventCategory)
    {
        return view('event-categories.edit', compact('eventCategory'));
    }

    public function update(Request $request, EventCategory $eventCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $eventCategory->update($validated);
        return redirect()->route('event-categories.index')->with('success', 'Event category updated successfully.');
    }

    public function destroy(EventCategory $eventCategory)
    {
        $eventCategory->delete();
        return redirect()->route('event-categories.index')->with('success', 'Event category deleted.');
    }
}
