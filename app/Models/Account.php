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
}
