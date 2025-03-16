<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

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
        'img_url',
        'category',
        'condition',
        'seller_id',
        'sale_status'
    ];

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'product_id', 'product_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'product_id', 'product_id');
    }

    public function favorites() {
        return $this->hasMany(Favorite::class, 'product_id', 'product_id');
    }

    public function isLikedBy($user) {
        return $this->favorites()->where('user_id', optional($user)->user_id)->exists();
    }

    public function isLikedByGuest()
    {
        $guestFavorites = session()->get('guest_favorites', []);
        return in_array($this->product_id, $guestFavorites);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }
}

