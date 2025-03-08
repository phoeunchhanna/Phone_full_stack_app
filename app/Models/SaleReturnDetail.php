<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_return_id', 
        'product_id', 
        'unit_price', 
        'quantity', 
        'discount', 
        'total_price'
    ];

    // Relationship with SaleReturn
    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
