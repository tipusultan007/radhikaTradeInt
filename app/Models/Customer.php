<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'type',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
    public function getBalanceAttribute()
    {
        $specificAccountIds = [1, 2, 6];
        return $this->journalEntries()->with(['lineItems' => function ($query) use ($specificAccountIds) {
            $query->whereIn('account_id', $specificAccountIds);
        }])->get()->reduce(function ($carry, $journalEntry) {
            foreach ($journalEntry->lineItems as $lineItem) {
                $carry += $lineItem->credit - $lineItem->debit;
            }
            return $carry;
        }, 0);
    }

}
