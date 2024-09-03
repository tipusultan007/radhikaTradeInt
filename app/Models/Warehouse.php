<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'packaging_type_id',
        'stock',
        'sale_price',
        'dealer_price',
        'commission_agent_price',
        'retailer_price',
        'retail_price',
        'wholesale_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packagingType()
    {
        return $this->belongsTo(PackagingType::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }


}
