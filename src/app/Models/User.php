<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'email',
        'password',
        'address',
        'postal_code',
        'building_name',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products() {
        return $this->hasMany(Product::class, 'seller_id', 'user_id');
    }

    public function sessions() {
        return $this->hasMany(Session::class, 'user_id', 'user_id');
    }

    public function favorites() {
        return $this->hasMany(Favorite::class, 'user_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'buyer_id', 'user_id');
    }

    protected function initializeEmailVerification() {
        $this->notify(new VerifyEmail);
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && file_exists(public_path('storage/' . $this->profile_image))) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-avatar.png');
    }
}
