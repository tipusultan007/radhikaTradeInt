<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'account_id',
        'subtotal',
        'customer_delivery_cost',
        'owner_delivery_cost',
        'discount',
        'paid_amount',
        'referrer_id',
        'total',
        'note',
        'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function referrer()
    {
        return $this->belongsTo(Customer::class,'referrer_id');
    }
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
}
