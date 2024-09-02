<?php

namespace App\Models;

use App\Services\LedgerService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntryLineItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit',
        'credit',
    ];

    protected static function booted()
    {
        static::created(function ($lineItem) {
            app(LedgerService::class)->updateOrCreateLedger($lineItem);
        });

        static::updated(function ($lineItem) {
            app(LedgerService::class)->updateOrCreateLedger($lineItem);
        });

        static::deleted(function ($lineItem) {
            $lineItem->removeLedgerEntry();
        });
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function removeLedgerEntry()
    {
        Ledger::where('account_id', $this->account_id)
            ->where('journal_entry_id', $this->journal_entry_id)
            ->delete();
    }

    public static function getTotalPaidCommission(int $customerId = null): float
    {
        $commissionAccount = Account::where('type', 'liability')
            ->where('name', 'Sales Commission')
            ->first();

        $query = self::where('account_id', $commissionAccount->id);

        // If a customer_id is provided, filter by customer_id
        if ($customerId !== null) {
            $query->whereHas('journalEntry', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId);
            });
        }

        // Sum the debit values for the given account_id and customer_id
        return $query->sum('debit');
    }
}
