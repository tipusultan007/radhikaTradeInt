<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdvanceSalary extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'taken_on',
        'month',
    ];

    public $casts = [
        'taken_on' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
            ->setDescriptionForEvent(fn(string $eventName) => "Advance Salary has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Advance Salary has been {$eventName}");
    }
}
