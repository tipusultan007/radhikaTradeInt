<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalEntryLineItem;
use App\Models\User;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    // Show the form to create a new payroll entry
    public function create()
    {
        $users = User::all();
        return view('payroll.create', compact('users'));
    }

    // Store the new payroll entry in the database
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure user ID is valid
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
            'month' => 'required|string',
        ]);

        // Get the user based on user_id from the request
        $user = User::findOrFail($request->input('user_id'));
        $basicSalary = $user->getLastIncrementedSalary();

        // Check if the user has already been paid for the given month
        $existingPayroll = Payroll::where('user_id', $user->id)
            ->where('month', $request->input('month'))
            ->first();

        if ($existingPayroll) {
            return redirect()->route('payroll.create')
                ->with('error', 'This user has already been paid for this month.');
        }

        DB::beginTransaction();
        try {
            // Calculate net pay
            $bonus = $request->input('bonus', 0);
            $deductions = $request->input('deductions', 0);
            $netPay = $basicSalary + $bonus - $deductions;

            // Create the payroll entry
            $payroll = new Payroll([
                'user_id' => $request->user_id,
                'account_id' => $request->account_id,
                'salary' => $basicSalary,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_pay' => $netPay,
                'pay_date' => $request->input('pay_date'),
                'month' => $request->input('month'), // Store month
            ]);

            $payroll->save();

            $journalEntry = JournalEntry::create([
                'journalable_type' => Payroll::class,
                'journalable_id' => $payroll->id,
                'type' => 'salary',
                'date' => $payroll->pay_date,
                'description' => 'Giving salary of ' . $payroll->user->name,
            ]);

            // Add line item to debit the Asset account
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => 8,
                'debit' => $payroll->net_pay,
                'credit' => 0,
            ]);

            // Add line item to credit the Cash/Bank account
            JournalEntryLineItem::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => $payroll->account_id,
                'debit' => 0,
                'credit' => $payroll->net_pay,
            ]);

            DB::commit();
            return redirect()->route('payroll.index')
                ->with('success', 'Payroll entry created successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('payroll.create')->with('error', $exception->getMessage());
        }
    }

    public function index()
    {
        $payrolls = Payroll::latest()->with('user')->get(); // Load all payrolls with the associated user

        return view('payroll.index', compact('payrolls'));
    }

    // Show a form to edit a payroll entry
    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        $users = User::all(); // Get all users for selection

        return view('payroll.edit', compact('payroll', 'users'));
    }

    // Update a payroll entry
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bonus' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'pay_date' => 'required|date',
            'month' => 'required|string',
            'account_id' => 'required',
        ]);

        // Find the existing payroll entry
        $payroll = Payroll::findOrFail($id);

        // Ensure the payroll month matches the original
        if ($payroll->month !== $request->input('month')) {
            return redirect()->route('payroll.edit', $id)
                ->with('error', 'You cannot change the payroll month.');
        }

        DB::beginTransaction();

        try {
            // Get the user
            $user = User::findOrFail($request->input('user_id'));
            $basicSalary = $user->getLastIncrementedSalary();

            // Calculate net pay
            $bonus = $request->input('bonus', 0);
            $deductions = $request->input('deductions', 0);
            $netPay = $basicSalary + $bonus - $deductions;

            // Update the payroll entry
            $payroll->update([
                'user_id' => $request->input('user_id'),
                'account_id' => $request->input('account_id'),
                'salary' => $basicSalary,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'net_pay' => $netPay,
                'pay_date' => $request->input('pay_date'),
            ]);

            // Update the associated journal entry
            $journalEntry = $payroll->journalEntry;
            $journalEntry->update([
                'date' => $payroll->pay_date,
                'description' => 'Updated salary of ' . $payroll->user->name,
            ]);

            // Update line items
            $debitLineItem = $journalEntry->lineItems()->where('debit', '>', 0)->first();
            $creditLineItem = $journalEntry->lineItems()->where('credit', '>', 0)->first();

            if ($debitLineItem) {
                $debitLineItem->update([
                    'debit' => $netPay,
                    'credit' => 0,
                    'account_id' => 8,
                ]);
            }

            if ($creditLineItem) {
                $creditLineItem->update([
                    'debit' => 0,
                    'account_id' => $payroll->account_id,
                    'credit' => $netPay,
                ]);
            }

            DB::commit();
            return redirect()->route('payroll.index')
                ->with('success', 'Payroll entry updated successfully.');

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('payroll.edit', $id)->with('error', $exception->getMessage());
        }

    }


    // Delete a payroll entry
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find the payroll entry by ID
            $payroll = Payroll::findOrFail($id);

            // Delete the associated journal entry and its line items
            $journalEntry = $payroll->journalEntry;
            if ($journalEntry) {
                // Delete all line items associated with the journal entry
                $journalEntry->lineItems()->delete();

                // Delete the journal entry itself
                $journalEntry->delete();
            }

            // Delete the payroll entry
            $payroll->delete();

            DB::commit();
            return redirect()->route('payroll.index')
                ->with('success', 'Payroll entry deleted successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('payroll.index')->with('error', $exception->getMessage());
        }

    }

}
