<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'reference',
        'supplier_id',
        'total_amount',
        'discount',
        'condition',
        'paid_amount',
        'due_amount',
        'status',
        'payment_method',
        'payment_status',
        'description',
    ];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
