<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'code',
        'opening_balance',
        'opening_balance_date',
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalEntryLineItems()
    {
        return $this->hasMany(JournalEntryLineItem::class);
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    public function balance()
    {
        $debits = $this->journalEntryLineItems()->sum('debit');
        $credits = $this->journalEntryLineItems()->sum('credit');

        return match ($this->type) {
            'asset', 'expense' => $debits - $credits,
            'liability', 'equity', 'revenue' => $credits - $debits,
            default => 0,
        };
    }

    public function childrenDebits()
    {
        $debits = $this->children()->with('journalEntryLineItems')->get()->reduce(function ($carry, $child) {
            return $carry + $child->journalEntryLineItems->sum('debit') + $child->childrenDebits();
        }, 0);

        return $debits;
    }

    public function childrenCredits()
    {
        $credits = $this->children()->with('journalEntryLineItems')->get()->reduce(function ($carry, $child) {
            return $carry + $child->journalEntryLineItems->sum('credit') + $child->childrenCredits();
        }, 0);

        return $credits;
    }

    public function totalBalance()
    {
        $debits = $this->journalEntryLineItems()->sum('debit') + $this->childrenDebits();
        $credits = $this->journalEntryLineItems()->sum('credit') + $this->childrenCredits();
        return $debits - $credits;
    }

}
