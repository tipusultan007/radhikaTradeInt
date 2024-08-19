<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'value',
        'purchase_date',
    ];

    public function journalEntry()
    {
        return $this->morphOne(JournalEntry::class, 'journalable');
    }
}
