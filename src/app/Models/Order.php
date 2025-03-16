<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'buyer_id',
        'product_id',
        'payment_id',
        'status',
        'payment_method',
        'created_at',
    ];

    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'order_id', 'payment_id');
    }
}
