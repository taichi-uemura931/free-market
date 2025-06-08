<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'transaction_id',
        'sender_id',
        'content',
        'is_read'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function isEditableBy(User $user)
    {
        return $this->sender_id === $user->id;
    }
}
