<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'buyer_id',
        'product_id',
        'payment_id',
        'status',
        'payment_method',
        'created_at',
    ];

    public function buyer() {
        return $this->belongsTo(User::class, 'buyer_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
