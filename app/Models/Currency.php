<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['name', 'code', 'symbol', 'thousand_separator', 'decimal_separator', 'exchange_rate'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class);
    }
}
