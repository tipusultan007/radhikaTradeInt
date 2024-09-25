<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payroll extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'salary',
        'account_id',
        'bonus',
        'deductions',
        'advance',
        'net_pay',
        'pay_date',
        'month',
    ];

    public $casts = [
        'pay_date' => 'date'
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

    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class, 'user_id', 'user_id');
    }

    public function getTotalAdvanceSalaryAttribute()
    {
        return $this->advanceSalaries()
            ->where('month', $this->month)
            ->sum('amount');
    }

    public function calculateNetPay()
    {
        $salary = $this->salary;
        $bonus = $this->bonus ?? 0;
        $deductions = $this->deductions ?? 0;
        $totalAdvanceSalary = $this->total_advance_salary;

        $this->net_pay = $salary + $bonus - $deductions - $totalAdvanceSalary;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Payroll has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Payroll has been {$eventName}");
    }

}
