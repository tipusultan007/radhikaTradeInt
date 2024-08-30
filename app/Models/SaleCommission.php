<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'commission',
        'customer_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
}
