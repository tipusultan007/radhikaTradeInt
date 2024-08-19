<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','packaging_type_id','stock','cost','sale_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packagingType()
    {
        return $this->belongsTo(PackagingType::class);
    }
}
