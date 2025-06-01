<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'reservation_date',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    // Valores permitidos para status
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id', 'id');
    }

    // Scope para reservas confirmadas
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    // Scope para reservas canceladas
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }
}