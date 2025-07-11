<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'cost_price',
        'selling_price',
        'quantity',
        'stock_alert',
        'description',
        'image',
        'condition',
        'category_id',
        'brand_id',
    ];

  
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        // old
        // return $this->belongsTo(Brand::class, 'brand_id');
         return $this->belongsTo(Brand::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
