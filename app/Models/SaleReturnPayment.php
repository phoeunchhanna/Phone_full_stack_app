<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_return_id', 
        'amount', 
        'date', 
        'reference', 
        'payment_method', 
        'note'
    ];

    // Relationship with SaleReturn
    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }
}
