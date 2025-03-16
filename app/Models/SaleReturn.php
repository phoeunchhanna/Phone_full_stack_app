<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 
        'reference', 
        'customer_id', 
        'sale_id',
        'total_amount', 
        'discount', 
        'paid_amount', 
        'due_amount', 
        'status', 
        'payment_method', 
        'payment_status', 
        'reason'
    ];

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with SaleReturnDetails
    public function details()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }

    // Relationship with SaleReturnPayments
    public function payments()
    {
        return $this->hasMany(SaleReturnPayment::class);
    }
    
    // Correct Relationship with Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
