<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Translation\t;

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
        $commissionAccount = Account::where('type', 'liability')
            ->where('name', 'Sales Commission')
            ->first();

        $specificAccountIds = [1, 2, 6, $commissionAccount->id];
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

}
