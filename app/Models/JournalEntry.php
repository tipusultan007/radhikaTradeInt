<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'journalable_type',
        'journalable_id',
        'type',
        'date',
        'description'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function journalable()
    {
        return $this->morphTo();
    }

    public function lineItems()
    {
        return $this->hasMany(JournalEntryLineItem::class);
    }

}
