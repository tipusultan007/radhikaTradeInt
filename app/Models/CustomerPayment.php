<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerPayment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'account_id',
        'date',
        'amount',
        'note'
    ];

    public $casts = [
        'date' => 'date',
    ];

    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Customer Payment has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Customer Payment has been {$eventName}");
    }
}
