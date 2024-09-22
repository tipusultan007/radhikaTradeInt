<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Sale;
use App\Models\SaleCommission;
use App\Models\SaleDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        $journalEntries = JournalEntry::with('lineItems', 'journalable')
            ->orderBy('date','desc')->paginate(30);
        return view('journals.index', compact('journalEntries'));
    }

    private function createOrUpdateJournalEntryForSale(Sale $sale, $commission = 0)
    {
        // Retrieve accounts
        $salesAccount = 6;
        $receivableAccount = 3;

        $paidAmount = $sale->paid_amount;
        $totalAmount = $sale->total;
        $discountAmount = $sale->discount ?? 0; // If no discount, assume 0
        $dueAmount = $totalAmount - $paidAmount;

        if ($sale->referrer_id != '') {
            $commissionAccount = Account::where('name','Sales Commission')->first();
            $salesCommission = SaleCommission::updateOrCreate(
                [
                    'sale_id' => $sale->id,
                ],
                [
                    'customer_id' => $sale->referrer_id,
                    'commission' => $commission
                ]
            );

        }else{
            SaleCommission::where('sale_id', $sale->id)->delete();
        }

        // Find or create the Journal Entry for the sale
        $journalEntry = $sale->journalEntry()->first(); // Get the existing journal entry if it exists

        if ($journalEntry) {
            // Update the existing journal entry
            $journalEntry->update([
                'customer_id' => $sale->customer_id,
                'date' => $sale->date,
            ]);
            // Clear previous line items
            $journalEntry->lineItems()->delete();
        } else {
            // Create a new journal entry
            $journalEntry = $sale->journalEntry()->create([
                'customer_id' => $sale->customer_id,
                'type' => 'sale',
                'date' => $sale->date,
                'description' => 'Sale entry for sale ID: ' . $sale->id,
            ]);
        }

        // Scenario 1: Full Payment
        if ($paidAmount == $totalAmount) {
            // Debit the Cash/Bank account
            $journalEntry->lineItems()->create([
                'account_id' => $sale->account_id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission >0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }

            // Scenario 2: Partial Payment
        } elseif ($paidAmount > 0 && $paidAmount < $totalAmount) {
            // Debit the Cash/Bank account for the paid amount
            $journalEntry->lineItems()->create([
                'account_id' => $sale->account_id,
                'debit' => $paidAmount,
                'credit' => 0,
            ]);

            // Debit the Receivables account for the remaining due amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount,
                'debit' => $dueAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission >0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }

            // Scenario 3: Full Due
        } else {
            // Debit the Receivables account for the full amount
            $journalEntry->lineItems()->create([
                'account_id' => $receivableAccount,
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Credit the Sales account (minus discount)
            $journalEntry->lineItems()->create([
                'account_id' => $salesAccount,
                'debit' => 0,
                'credit' => $totalAmount - $commission,
            ]);

            if ($sale->referrer_id != '' && $commission >0){
                $journalEntry->lineItems()->create([
                    'account_id' => $commissionAccount->id,
                    'debit' => 0,
                    'credit' => $commission,
                ]);
            }
        }
    }
}
