<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'case_description', 'status',
        'case_type', 'assigned_to', 'ai_score', 'ai_summary',
        'bot_active', 'widget_token', 'last_message_at',
    ];

    protected $casts = [
        'bot_active' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    public function advisor()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class)->where('is_read', false)->where('sender_type', 'client');
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'new'           => 'Nuevo',
            'in_progress'   => 'En Proceso',
            'qualified'     => 'Calificado',
            'disqualified'  => 'Descartado',
            'converted'     => 'Cliente',
            default         => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'new'           => 'primary',
            'in_progress'   => 'warning',
            'qualified'     => 'success',
            'disqualified'  => 'danger',
            'converted'     => 'info',
            default         => 'secondary',
        };
    }
}
