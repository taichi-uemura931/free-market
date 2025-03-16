<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'payment_id', 'order_id');
    }
}
