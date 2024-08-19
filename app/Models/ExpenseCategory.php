<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id','name'];
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    // Define the relationship to the child categories
    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id')->with('children');
    }
}
