<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GymClass;

class GymClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            // Lunes
            [
                'name' => 'Yoga Matutino',
                'description' => 'Clase de yoga relajante para empezar la semana con energía positiva.',
                'start_time' => '07:00:00',
                'end_time' => '08:00:00',
                'day_of_week' => 'monday',
                'max_capacity' => 20
            ],
            [
                'name' => 'CrossFit Intensivo',
                'description' => 'Entrenamiento funcional de alta intensidad.',
                'start_time' => '18:00:00',
                'end_time' => '19:00:00',
                'day_of_week' => 'monday',
                'max_capacity' => 15
            ],
            [
                'name' => 'Pilates',
                'description' => 'Fortalecimiento del core y mejora de la flexibilidad.',
                'start_time' => '19:30:00',
                'end_time' => '20:30:00',
                'day_of_week' => 'monday',
                'max_capacity' => 18
            ],

            // Martes
            [
                'name' => 'Spinning',
                'description' => 'Cardio intenso en bicicleta estática con música motivadora.',
                'start_time' => '06:30:00',
                'end_time' => '07:30:00',
                'day_of_week' => 'tuesday',
                'max_capacity' => 25
            ],
            [
                'name' => 'Zumba',
                'description' => 'Baile fitness divertido y energético.',
                'start_time' => '18:30:00',
                'end_time' => '19:30:00',
                'day_of_week' => 'tuesday',
                'max_capacity' => 30
            ],

            // Miércoles
            [
                'name' => 'Yoga Avanzado',
                'description' => 'Clase de yoga para practicantes con experiencia.',
                'start_time' => '07:00:00',
                'end_time' => '08:00:00',
                'day_of_week' => 'wednesday',
                'max_capacity' => 15
            ],
            [
                'name' => 'Entrenamiento Funcional',
                'description' => 'Ejercicios funcionales para mejorar la fuerza y coordinación.',
                'start_time' => '18:00:00',
                'end_time' => '19:00:00',
                'day_of_week' => 'wednesday',
                'max_capacity' => 20
            ],

            // Jueves
            [
                'name' => 'Aqua Aeróbicos',
                'description' => 'Ejercicios aeróbicos en el agua, ideal para todas las edades.',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'day_of_week' => 'thursday',
                'max_capacity' => 12
            ],
            [
                'name' => 'Boxing Fitness',
                'description' => 'Entrenamiento de boxeo sin contacto, perfecto para quemar calorías.',
                'start_time' => '19:00:00',
                'end_time' => '20:00:00',
                'day_of_week' => 'thursday',
                'max_capacity' => 16
            ],

            // Viernes
            [
                'name' => 'HIIT',
                'description' => 'Entrenamiento de intervalos de alta intensidad.',
                'start_time' => '07:30:00',
                'end_time' => '08:30:00',
                'day_of_week' => 'friday',
                'max_capacity' => 18
            ],
            [
                'name' => 'Yoga Restaurativo',
                'description' => 'Yoga relajante para terminar la semana.',
                'start_time' => '18:00:00',
                'end_time' => '19:00:00',
                'day_of_week' => 'friday',
                'max_capacity' => 22
            ],

            // Sábado
            [
                'name' => 'Spinning Weekend',
                'description' => 'Clase especial de spinning para el fin de semana.',
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'day_of_week' => 'saturday',
                'max_capacity' => 25
            ],
            [
                'name' => 'CrossFit Open',
                'description' => 'Clase abierta de CrossFit para todos los niveles.',
                'start_time' => '10:30:00',
                'end_time' => '11:30:00',
                'day_of_week' => 'saturday',
                'max_capacity' => 20
            ],

            // Domingo
            [
                'name' => 'Yoga Familiar',
                'description' => 'Clase de yoga para toda la familia.',
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'day_of_week' => 'sunday',
                'max_capacity' => 15
            ],
            [
                'name' => 'Stretching y Relajación',
                'description' => 'Sesión de estiramientos y relajación para terminar la semana.',
                'start_time' => '17:00:00',
                'end_time' => '18:00:00',
                'day_of_week' => 'sunday',
                'max_capacity' => 25
            ]
        ];

        foreach ($classes as $class) {
            GymClass::create($class);
        }
    }
}