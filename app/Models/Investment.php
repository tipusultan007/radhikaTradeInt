<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Polymorphic relationship with JournalEntry
    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
