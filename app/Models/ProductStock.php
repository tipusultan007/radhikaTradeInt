<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'quantity',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
