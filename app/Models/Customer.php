<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use function Symfony\Component\Translation\t;

class Customer extends Model
{
    use HasFactory, LogsActivity;

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
        $commissionAccount = Account::where('type', 'liability')
            ->where('name', 'Sales Commission')
            ->first();

        if ($this->type != 'commission_agent') {
            $specificAccountIds = [1, 2, 6, 52, 51];
        }else{
            $specificAccountIds = [1, 2, 6,52];
        }

        return $this->journalEntries()->with(['lineItems' => function ($query) use ($specificAccountIds) {
            $query->whereIn('account_id', $specificAccountIds);
        }])->get()->reduce(function ($carry, $journalEntry) {
            foreach ($journalEntry->lineItems as $lineItem) {
                $carry += $lineItem->credit - $lineItem->debit;
            }
            return $carry;
        }, 0);
    }
    public function commissions()
    {
        return $this->hasMany(SaleCommission::class);
    }
    public function getCommissionAttribute()
    {

        return $this->commissions()->sum('commission') - $this->commissionWithdraws()->sum('amount');
    }

    public function commissionWithdraws()
    {
        return $this->hasMany(CommissionWithdraw::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Customer has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Customer has been {$eventName}");
    }
}
