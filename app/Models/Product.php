<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','initial_stock_kg'];

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
