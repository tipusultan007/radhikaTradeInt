<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'account_id'];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id')->with('children');
    }

    /**
     * Get the expenses associated with this category.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the account associated with this expense category.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
