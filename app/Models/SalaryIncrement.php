<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryIncrement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'new_salary',
        'increment_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
