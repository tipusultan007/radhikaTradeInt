<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JournalEntry extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'customer_id',
        'journalable_type',
        'journalable_id',
        'type',
        'date',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function journalable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function lineItems()
    {
        return $this->hasMany(JournalEntryLineItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Journal has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Journal has been {$eventName}");
    }
}
