<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'date', 'reference', 'amount', 'details'];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
