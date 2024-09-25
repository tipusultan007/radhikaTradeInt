<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductStock extends Model
{
    use HasFactory,LogsActivity;

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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Product Stock has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Product Stock has been {$eventName}");
    }
}
