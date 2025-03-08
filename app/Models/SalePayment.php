<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    // Define the relationship with the Sale model
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
