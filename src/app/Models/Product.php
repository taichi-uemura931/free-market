<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'brand_name',
        'price',
        'description',
        'status',
        'category',
        'condition',
        'seller_id',
    ];

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'product_id', 'product_id');
    }

    public function favorites() {
        return $this->hasMany(Favorite::class, 'product_id', 'product_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'product_id', 'product_id');
    }
}

