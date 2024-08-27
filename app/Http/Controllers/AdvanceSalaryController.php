<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AdvanceSalary;
use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceSalaryController extends Controller
{
    // Display a listing of the advance salaries
    public function index()
    {
        $users = User::all();  // Retrieve all users
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get();  // Retrieve all accounts
        $advanceSalaries = AdvanceSalary::with('user')->paginate(10);
        return view('advance_salary.index', compact('advanceSalaries','accounts','users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required',
            'account_id' => 'required',
            'taken_on' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Create the advance salary entry
            $advanceSalary = new AdvanceSalary([
                'user_id' => $request->input('user_id'),
                'amount' => $request->input('amount'),
                'taken_on' => now(),
                'month' => $request->input('month'),
                'account_id' => $request->input('account_id'),
            ]);

            $advanceSalary->save();

            // Create a journal entry for the advance salary
            $journalEntry = JournalEntry::create([
                'journalable_type' => AdvanceSalary::class,
                'journalable_id' => $advanceSalary->id,
                'type' => 'advance_salary',
                'date' => now(),
                'description' => 'Advance salary taken by ' . $advanceSalary->user->name,
            ]);

            // Add line item to credit the Liability account (e.g., Salary Payable)
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => 8, // Assuming 9 is the Salary Payable account ID
                'debit' => $advanceSalary->amount,
                'credit' => 0,
            ]);

            // Add line item to debit the Asset account (e.g., Cash/Bank)
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $request->input('account_id'), // Assuming 8 is the Cash/Bank account ID
                'debit' => 0,
                'credit' => $advanceSalary->amount,
            ]);



            DB::commit();

            return redirect()->back()->with('success', 'Advance salary recorded successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error recording advance salary: ' . $exception->getMessage());
        }
    }
    // Show the form for editing the specified advance salary
    public function edit($id)
    {
        $users = User::all();  // Retrieve all users
        $accounts = Account::where('type','asset')->whereNotIn('id',[3,4])->get();
        $advanceSalary = AdvanceSalary::findOrFail($id);
        return view('advance_salary.edit', compact('advanceSalary','accounts','users'));
    }

    // Update the specified advance salary
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Find the existing advance salary
            $advanceSalary = AdvanceSalary::findOrFail($id);

            // Update the advance salary
            $advanceSalary->update($request->all());

            // Update the corresponding journal entry
            $journalEntry = $advanceSalary->journalEntry;
            $journalEntry->update([
                'date' => $request->input('taken_on'),
                'description' => 'Updated advance salary for ' . $advanceSalary->user->name,
            ]);

            $debitLineItem = $journalEntry->lineItems()->where('debit', '>', 0)->first();
            $creditLineItem = $journalEntry->lineItems()->where('credit', '>', 0)->first();

            if ($debitLineItem) {
                $debitLineItem->update([
                    'debit' => $advanceSalary->amount,
                    'credit' => 0,
                    'account_id' => 8,
                ]);
            }

            if ($creditLineItem) {
                $creditLineItem->update([
                    'debit' => 0,
                    'account_id' => $advanceSalary->account_id,
                    'credit' => $advanceSalary->amount,
                ]);
            }

            DB::commit();

            return redirect()->route('advance_salary.index')
                ->with('success', 'Advance salary updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating advance salary: ' . $exception->getMessage());
        }
    }

    // Remove the specified advance salary
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find the advance salary
            $advanceSalary = AdvanceSalary::findOrFail($id);

            // Delete the associated journal entry
            $journalEntry = $advanceSalary->journalEntry;
            if ($journalEntry) {
                $journalEntry->lineItems()->delete();
                $journalEntry->delete();
            }

            // Delete the advance salary
            $advanceSalary->delete();

            DB::commit();

            return redirect()->route('advance_salary.index')
                ->with('success', 'Advance salary deleted successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting advance salary: ' . $exception->getMessage());
        }
    }
}

