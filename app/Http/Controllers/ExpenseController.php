<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        $accounts = Account::all();
        $expenses = Expense::with('expenseCategory', 'account', 'journalEntry')->orderByDesc('date')->paginate(10);
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

        DB::transaction(function () use ($request) {
            // Create Expense
            $expense = Expense::create($request->all());

            // Create Journal Entry
            $journalEntry = new JournalEntry([
                'description' => $request->description,
                'type' => 'expense',
                'date' => $request->date,
            ]);
            $expense->journalEntry()->save($journalEntry);

            // Create Journal Entry Line Items
            $journalEntry->lineItems()->createMany([
                [
                    'account_id' => $expense->expenseCategory->account_id, // Debit account
                    'debit' => $request->amount,
                    'credit' => 0,
                ],
                [
                    'account_id' => $expense->account_id, // Credit account
                    'credit' => $request->amount,
                    'debit' => 0,
                ],

            ]);
        });

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

        DB::transaction(function () use ($request, $expense) {
            // Update Expense
            $expense->update($request->all());

            // Update Journal Entry
            $journalEntry = $expense->journalEntry;
            $journalEntry->update([
                'description' => $request->description,
                'type' => 'expense',
                'date' => $request->date,
            ]);

            // Update Journal Entry Line Items
            $journalEntry->lineItems()->delete();

            $journalEntry->lineItems()->createMany([

                [
                    'account_id' => $expense->expenseCategory->account_id, // Debit account
                    'debit' => $request->amount,
                    'credit' => 0,
                ],
                [
                    'account_id' => $expense->account_id, // Credit account
                    'credit' => $request->amount,
                    'debit' => 0,
                ],
            ]);
        });

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        DB::transaction(function () use ($expense) {
            // Delete related journal entry and line items
            $expense->journalEntry->lineItems()->delete();
            $expense->journalEntry()->delete();

            // Delete Expense
            $expense->delete();
        });

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
