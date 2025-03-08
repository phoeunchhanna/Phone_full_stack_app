<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
    ];

    // Define the relationship with the Purchase model
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
