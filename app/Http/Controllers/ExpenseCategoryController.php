<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Models\Account;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::with('parent')->get();
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
            'parent_id' => 'nullable|exists:expense_categories,id',
        ]);

        $category = ExpenseCategory::create($request->all());

        // Create a related account under "Expense Account" category
        $account = Account::create([
            'parent_id' => 7,
            'name' => $category->name,
            'type' => 'expense',
            'code' => 'EXP-' . strtoupper(str_replace(' ', '-', $category->name)),
        ]);

        // Associate the account with the category
        $category->update(['account_id' => $account->id]);

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category and related account created successfully.');
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
            'parent_id' => 'nullable|exists:expense_categories,id',
        ]);

        $expenseCategory->update($request->all());

        // Update the related account's name and code
        $account = $expenseCategory->account;
        if ($account) {
            $account->update([
                'name' => $expenseCategory->name,
                'code' => 'EXP-' . strtoupper(str_replace(' ', '-', $expenseCategory->name)),
            ]);
        }

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category and related account updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Delete the related account
        $account = $expenseCategory->account;
        if ($account) {
            $account->delete();
        }

        $expenseCategory->delete();

        return redirect()->route('expense_categories.index')
            ->with('success', 'Expense category and related account deleted successfully.');
    }
}
