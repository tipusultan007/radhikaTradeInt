<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Investment;
use App\Models\InvestmentWithdraw;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use Illuminate\Http\Request;

class InvestmentWithdrawController extends Controller
{
    /**
     * Display a listing of the withdrawals.
     */
    public function index()
    {
        $withdrawals = InvestmentWithdraw::all();
        return view('investment_withdraws.index', compact('withdrawals'));
    }

    /**
     * Show the form for creating a new withdrawal.
     */
    public function create()
    {
        $investments = Investment::orderBy('date','asc')->get(); // Fetch all investments
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get();       // Fetch all accounts
        return view('investment_withdraws.create', compact('investments', 'accounts'));
    }


    /**
     * Store a newly created withdrawal in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|integer',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $withdraw = InvestmentWithdraw::create($request->all());

        $journalEntry = $withdraw->journalEntry()->create([
            'type' => 'investment_withdraw',
            'date' => $request->date,
            'description' => 'Withdrawal',
        ]);

        $investmentAccount = Account::where('name','Capital')->first();

        if ($withdraw->amount>0) {
            // Debit the Investment Account
            $journalEntry->lineItems()->create([
                'account_id' => $investmentAccount->id, // Investment Account
                'debit' => $request->amount,
                'credit' => 0,
            ]);
        }
        if ($withdraw->profit>0) {
            $journalEntry->lineItems()->create([
                'account_id' => 7,
                'debit' => $request->profit,
                'credit' => 0,
            ]);
        }

        // Credit the Cash Account
        $journalEntry->lineItems()->create([
            'account_id' => $request->account_id, // Cash Account
            'debit' => 0,
            'credit' => $request->input('amount',0) + $request->input('profit',0),
        ]);

        return redirect()->route('investment_withdraws.index')->with('success', 'Investment withdrawal recorded successfully.');
    }

    /**
     * Show the form for editing the specified withdrawal.
     */
    public function edit(InvestmentWithdraw $investmentWithdraw)
    {
        $investments = Investment::orderBy('date','asc')->get(); // Fetch all investments
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get();

        return view('investment_withdraws.edit', compact('investmentWithdraw','investments','accounts'));
    }

    /**
     * Update the specified withdrawal in storage.
     */
    public function update(Request $request, InvestmentWithdraw $investmentWithdraw)
    {
        $request->validate([
            'investment_id' => 'required|integer',
            'account_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $investmentWithdraw->update($request->all());

        $journalEntry = $investmentWithdraw->journalEntry;
        $journalEntry->update([
            'date' => $request->date,
            'description' => 'Withdrawal Update',
        ]);

        $journalEntry->lineItems()->delete();
        $investmentAccount = Account::where('name','Capital')->first();

        if ($investmentWithdraw->amount>0) {
            // Debit the Investment Account
            $journalEntry->lineItems()->create([
                'account_id' => $investmentAccount->id, // Investment Account
                'debit' => $request->amount,
                'credit' => 0,
            ]);
        }
        if ($investmentWithdraw->profit>0) {
            $journalEntry->lineItems()->create([
                'account_id' => 7,
                'debit' => $request->profit,
                'credit' => 0,
            ]);
        }

        // Credit the Cash Account
        $journalEntry->lineItems()->create([
            'account_id' => $request->account_id, // Cash Account
            'debit' => 0,
            'credit' => $request->input('amount',0) + $request->input('profit',0),
        ]);

        return redirect()->route('investment_withdraws.index')->with('success', 'Investment withdrawal updated successfully.');
    }

    /**
     * Remove the specified withdrawal from storage.
     */
    public function destroy(InvestmentWithdraw $investmentWithdraw)
    {
        $investmentWithdraw->journalEntry->lineItems()->delete();
        $investmentWithdraw->journalEntry()->delete();
        $investmentWithdraw->delete();
        return redirect()->route('investment_withdraws.index')->with('success', 'Investment withdrawal deleted successfully.');
    }
}
