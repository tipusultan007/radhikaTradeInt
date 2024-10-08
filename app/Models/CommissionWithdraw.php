<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CommissionWithdraw extends Model
{
    use HasFactory, LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Commission withdraw has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Commission withdraw has been {$eventName}");
    }

}
