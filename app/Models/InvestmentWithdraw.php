<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InvestmentWithdraw extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'investment_id',
        'account_id',
        'amount',
        'profit',
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

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Investment withdraw has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Investment withdraw has been {$eventName}");
    }
}
