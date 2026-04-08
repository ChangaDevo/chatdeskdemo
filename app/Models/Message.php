<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'prospect_id', 'sender_type', 'sender_user_id', 'content', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }

    public function senderUser()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
