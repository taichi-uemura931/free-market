<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $primaryKey = 'comment_id';
    protected $fillable = [
        'user_id',
        'product_id',
        'comment_text',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
