<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CommissionWithdraw;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commissionWithdraws = CommissionWithdraw::orderByDesc('date')->paginate(10);
        return view('commission_withdraw.index',compact('commissionWithdraws'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $commissionWithdraw = CommissionWithdraw::create(request()->all());

        $journalEntry = new JournalEntry([
            'customer_id' => $request->get('customer_id'),
            'description' => 'Sales Commission Withdraw',
            'type' => 'commission_withdraw',
            'date' => $request->date,
        ]);
        $commissionWithdraw->journalEntry()->save($journalEntry);

        $commisionAccount = Account::where('name','Sales Commission')->first();
        // Create Journal Entry Line Items
        $journalEntry->lineItems()->createMany([
            [
                'account_id' => $commisionAccount->id, // Debit account
                'debit' => $request->amount,
                'credit' => 0,
            ],
            [
                'account_id' => $commissionWithdraw->account_id, // Credit account
                'credit' => $request->amount,
                'debit' => 0,
            ],

        ]);

        return redirect()->route('commission-withdraw.index')->with('success', 'Commission withdrawn successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CommissionWithdraw $commissionWithdraw)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommissionWithdraw $commissionWithdraw)
    {
        return view('commission_withdraw.edit', compact('commissionWithdraw'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommissionWithdraw $commissionWithdraw)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $commissionWithdraw) {
            // Update Expense
            $commissionWithdraw->update($request->all());

            // Update Journal Entry
            $journalEntry = $commissionWithdraw->journalEntry;
            $journalEntry->update([
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Update Journal Entry Line Items
            $journalEntry->lineItems()->delete();

            $commisionAccount = Account::where('name','Sales Commission')->first();

            $journalEntry->lineItems()->createMany([

                [
                    'account_id' => $commisionAccount->id, // Debit account
                    'debit' => $request->amount,
                    'credit' => 0,
                ],
                [
                    'account_id' => $commissionWithdraw->account_id, // Credit account
                    'credit' => $request->amount,
                    'debit' => 0,
                ],
            ]);
        });

        return redirect()->route('commission-withdraw.index')
            ->with('success', 'Commission withdraw updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommissionWithdraw $commissionWithdraw)
    {
        DB::transaction(function () use ($commissionWithdraw) {
            // Delete related journal entry and line items
            $commissionWithdraw->journalEntry->lineItems()->delete();
            $commissionWithdraw->journalEntry()->delete();

            // Delete Expense
            $commissionWithdraw->delete();
        });

        return redirect()->route('commission-withdraw.index')
            ->with('success', 'Commission withdraw deleted successfully.');
    }
}
