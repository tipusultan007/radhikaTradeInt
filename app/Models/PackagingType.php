<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingType extends Model
{
    use HasFactory;

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}
