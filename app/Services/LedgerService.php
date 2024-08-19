<?php

namespace App\Services;

use App\Models\Ledger;
use App\Models\JournalEntryLineItem;

class LedgerService
{
    public function updateOrCreateLedger(JournalEntryLineItem $lineItem)
    {
        Ledger::updateOrCreate(
            [
                'account_id' => $lineItem->account_id,
                'journal_entry_id' => $lineItem->journal_entry_id,
            ],
            [
                'date' => $lineItem->journalEntry->date,
                'description' => $lineItem->journalEntry->description,
                'debit' => $lineItem->debit,
                'credit' => $lineItem->credit,
                'balance' => $this->calculateBalance($lineItem),
            ]
        );
    }

    protected function calculateBalance(JournalEntryLineItem $lineItem)
    {
        $previousBalance = Ledger::where('account_id', $lineItem->account_id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first()
            ->balance ?? 0;

        return $previousBalance + $lineItem->debit - $lineItem->credit;
    }
}
