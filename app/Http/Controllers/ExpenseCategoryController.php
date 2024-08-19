<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('expense_categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('expense_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ExpenseCategory::create($request->all());

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        return view('expense_categories.show', compact('expenseCategory'));
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $categories = ExpenseCategory::all();
        return view('expense_categories.edit', compact('expenseCategory', 'categories'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $expenseCategory->update($request->all());

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category deleted successfully.');
    }
}
