<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'date',
        'reference',
        'customer_id',
        'user_id',
        'total_amount',
        'discount',
        'paid_amount',
        'due_amount',
        'status',
        'payment_method',
        'payment_status',
        'description',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
