<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BalanceTransfer;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use Illuminate\Http\Request;

class BalanceTransferController extends Controller
{
    /**
     * Display a listing of the balance transfers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BalanceTransfer::query();

        // Filter by account
        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('from_account_id', $request->account_id)
                    ->orWhere('to_account_id', $request->account_id);
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transfer_date', [$request->start_date, $request->end_date]);
        }

        // Fetch the balance transfers with pagination
        $balanceTransfers = $query->paginate(10); // 10 items per page
        $accounts = Account::all();
        return view('balance_transfers.index', compact('balanceTransfers','accounts'));
    }

    /**
     * Show the form for creating a new balance transfer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::all();
        return view('balance_transfers.create', compact('accounts'));
    }

    /**
     * Store a newly created balance transfer in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Ensure the from and to accounts are not the same
        if ($request->from_account_id == $request->to_account_id) {
            return redirect()->back()->withErrors(['from_account_id' => 'The from and to accounts cannot be the same.']);
        }

        // Create the balance transfer record
        $balanceTransfer = BalanceTransfer::create([
            'from_account_id' => $request->from_account_id,
            'to_account_id' => $request->to_account_id,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
            'description' => $request->description,
        ]);

        // Update the balances of the involved accounts
        $fromAccount = Account::find($request->from_account_id);
        $toAccount = Account::find($request->to_account_id);

        // Create the journal entry
        $journalEntry = JournalEntry::create([
            'journalable_type' => BalanceTransfer::class,
            'journalable_id' => $balanceTransfer->id,
            'type' => 'balance_transfer',
            'date' => $request->transfer_date,
            'description' => $request->description,
        ]);

        // Create the journal entry line items
        $journalEntry->lineItems()->createMany([
            [
                'account_id' => $request->to_account_id,
                'debit' => $request->amount,
                'credit' => 0
            ],
            [
            'account_id' => $request->from_account_id,
            'credit' => $request->amount,
            'debit' => 0
        ]
        ]);

        return redirect()->route('balance_transfers.index')->with('success', 'Balance transfer created successfully.');
    }

    /**
     * Display the specified balance transfer.
     *
     * @param  \App\Models\BalanceTransfer  $balanceTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(BalanceTransfer $balanceTransfer)
    {
        return view('balance_transfers.show', compact('balanceTransfer'));
    }

    /**
     * Show the form for editing the specified balance transfer.
     *
     * @param  \App\Models\BalanceTransfer  $balanceTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(BalanceTransfer $balanceTransfer)
    {
        $accounts = Account::all();
        return view('balance_transfers.edit', compact('balanceTransfer', 'accounts'));
    }

    /**
     * Update the specified balance transfer in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BalanceTransfer  $balanceTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BalanceTransfer $balanceTransfer)
    {
        // Validate the incoming request
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Ensure the from and to accounts are not the same
        if ($request->from_account_id == $request->to_account_id) {
            return redirect()->back()->withErrors(['from_account_id' => 'The from and to accounts cannot be the same.']);
        }

        // Revert the original balance transfer
        $originalFromAccount = $balanceTransfer->fromAccount;
        $originalToAccount = $balanceTransfer->toAccount;

        // Update the balance transfer record
        $balanceTransfer->update([
            'from_account_id' => $request->from_account_id,
            'to_account_id' => $request->to_account_id,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
            'description' => $request->description,
        ]);

        // Adjust the balances based on the new transfer
        $fromAccount = Account::find($request->from_account_id);
        $toAccount = Account::find($request->to_account_id);

        // Update the corresponding journal entry
        $journalEntry = $balanceTransfer->journalEntry;
        if ($journalEntry) {
            $journalEntry->update([
                'date' => $request->transfer_date,
                'description' => $request->description,
            ]);
        }else{
            $journalEntry = JournalEntry::create([
                'journalable_type' => BalanceTransfer::class,
                'journalable_id' => $balanceTransfer->id,
                'type' => 'balance_transfer',
                'date' => $request->transfer_date,
                'description' => $request->description,
            ]);
        }

        // Update the journal entry line items
        $journalEntry->lineItems()->delete();

        $journalEntry->lineItems()->createMany([
            [
                'account_id' => $request->to_account_id,
                'debit' => $request->amount,
                'credit' => 0
            ],
            [
                'account_id' => $request->from_account_id,
                'credit' => $request->amount,
                'debit' => 0
            ]
        ]);

        return redirect()->route('balance_transfers.index')->with('success', 'Balance transfer updated successfully.');
    }

    /**
     * Remove the specified balance transfer from the database.
     *
     * @param  \App\Models\BalanceTransfer  $balanceTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(BalanceTransfer $balanceTransfer)
    {
        // Revert the balance transfer
        $fromAccount = $balanceTransfer->fromAccount;
        $toAccount = $balanceTransfer->toAccount;

        // Delete the related journal entry and line items
        $balanceTransfer->journalEntry->lineItems()->delete();
        $balanceTransfer->journalEntry->delete();

        // Delete the balance transfer record
        $balanceTransfer->delete();

        return redirect()->route('balance_transfers.index')->with('success', 'Balance transfer deleted successfully.');
    }
}
