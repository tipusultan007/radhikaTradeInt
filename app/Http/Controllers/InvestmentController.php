<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Investment;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the investments.
     */
    public function index()
    {
        $investments = Investment::all();
        return view('investments.index', compact('investments'));
    }

    /**
     * Show the form for creating a new investment.
     */
    public function create()
    {
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get(); // Fetch all accounts
        return view('investments.create', compact('accounts'));
    }

    /**
     * Store a newly created investment in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $investmentAccount = Account::where('name','Capital')->first();

        // Create the investment
        $investment = Investment::create($request->all());

        // Create the journal entry for the investment
        $journalEntry = $investment->journalEntry()->create([
            'type' => 'investment',
            'date' => $request->date,
            'description' => $request->description ?: 'Investment',
        ]);

        // Add the debit and credit journal entry line items
        // 1. Debit the Cash Account
        $journalEntry->lineItems()->create([
            'account_id' => $request->account_id, // Cash Account
            'debit' => $request->amount,
            'credit' => 0,
        ]);

        // 2. Credit the Investment Account
        $journalEntry->lineItems()->create([
            'account_id' => $investmentAccount->id, // Investment Account
            'debit' => 0,
            'credit' => $request->amount,
        ]);

        return redirect()->route('investments.index')->with('success', 'Investment and journal entry recorded successfully.');
    }

    /**
     * Show the form for editing the specified investment.
     */
    public function edit(Investment $investment)
    {
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get();
        return view('investments.edit', compact('investment','accounts'));
    }

    /**
     * Update the specified investment in storage.
     */
    public function update(Request $request, Investment $investment)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $investment->update($request->all());

        // Update the journal entry
        $journalEntry = $investment->journalEntry;
        $journalEntry->update([
            'date' => $request->date,
            'description' => $request->description ?: 'Investment Update',
        ]);

        // Update line items
        $journalEntry->lineItems()->first()->update([
            'debit' => $request->amount,
        ]);

        $journalEntry->lineItems()->last()->update([
            'credit' => $request->amount,
        ]);

        return redirect()->route('investments.index')->with('success', 'Investment updated successfully.');
    }

    /**
     * Remove the specified investment from storage.
     */
    public function destroy(Investment $investment)
    {
        $investment->journalEntry->lineItems()->delete();
        $investment->journalEntry()->delete();
        $investment->delete();
        return redirect()->route('investments.index')->with('success', 'Investment deleted successfully.');
    }
}
