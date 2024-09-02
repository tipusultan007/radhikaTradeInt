<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionWithdraw extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'customer_id',
        'account_id',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
}
