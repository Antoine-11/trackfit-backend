<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    use HasFactory;

    // Especificar explícitamente el nombre de la tabla
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'day_of_week',
        'max_capacity',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relación con reservas - especificar explícitamente las claves
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'class_id', 'id');
    }
}