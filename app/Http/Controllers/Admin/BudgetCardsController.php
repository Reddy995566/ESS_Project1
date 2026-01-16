<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetCard;
use Illuminate\Http\Request;

class BudgetCardsController extends Controller
{
    public function index()
    {
        $budgetCards = BudgetCard::ordered()->get();
        return view('admin.budget-cards.index', compact('budgetCards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'subtitle' => 'required|string|max:50',
            'price' => 'required|integer|min:1',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? BudgetCard::max('sort_order') + 1;

        $budgetCard = BudgetCard::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Budget card created successfully!',
            'data' => $budgetCard
        ]);
    }

    public function update(Request $request, BudgetCard $budgetCard)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'subtitle' => 'required|string|max:50',
            'price' => 'required|integer|min:1',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $budgetCard->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Budget card updated successfully!',
            'data' => $budgetCard
        ]);
    }

    public function destroy(BudgetCard $budgetCard)
    {
        $budgetCard->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budget card deleted successfully!'
        ]);
    }

    public function toggleStatus(BudgetCard $budgetCard)
    {
        $budgetCard->update(['is_active' => !$budgetCard->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'is_active' => $budgetCard->is_active
        ]);
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);
        
        foreach ($order as $index => $id) {
            BudgetCard::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!'
        ]);
    }
}
