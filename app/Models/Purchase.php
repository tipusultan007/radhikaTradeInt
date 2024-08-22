<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity_kg',
        'purchase_price',
        'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
}
