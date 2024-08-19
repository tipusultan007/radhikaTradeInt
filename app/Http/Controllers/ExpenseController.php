<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Account;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $expenses = Expense::with('expenseCategory', 'account')->get();
        return view('expenses.index', compact('expenses','accounts','categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        return view('expenses.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        return view('expenses.edit', compact('expense', 'categories', 'accounts'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
