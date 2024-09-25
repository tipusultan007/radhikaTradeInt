<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Sale extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'customer_id',
        'account_id',
        'invoice_no',
        'subtotal',
        'customer_delivery_cost',
        'owner_delivery_cost',
        'discount',
        'paid_amount',
        'referrer_id',
        'total',
        'note',
        'date',
        'payment_details',
        'status',
        'dispatched_at',
        'delivered_at',
        'dispatched_by',
        'delivered_by',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'dispatched_at' => 'date',
        'delivered_at' => 'date',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
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

    public function dispatchedBy()
    {
        return $this->belongsTo(User::class,'dispatched_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class,'delivered_by');
    }

    public static function pendingSale()
    {
        return Sale::where('status','pending')->count();
    }

    public static function dispatchedSale()
    {
        return Sale::where('status','dispatched')->count();
    }
    public static function deliveredSale()
    {
        return Sale::where('status','delivered')->count();
    }
    // Only log the changed (dirty) attributes
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Sale has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Sale has been {$eventName}");
    }
}
