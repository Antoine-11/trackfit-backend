<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Workout;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario de prueba principal
        $user = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@trackfit.com',
            'password' => Hash::make('password123'),
            'phone' => '+34 666 777 888',
            'birth_date' => '1990-05-15',
        ]);

        // Crear suscripción activa para el usuario
        $premiumPlan = Plan::where('name', 'Plan Premium')->first();
        if ($premiumPlan) {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $premiumPlan->id,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'status' => 'active'
            ]);
        }

        // Crear algunos entrenamientos de ejemplo
        $workoutTypes = ['Pecho y Tríceps', 'Espalda y Bíceps', 'Piernas', 'Hombros', 'Cardio', 'Full Body'];
        $comments = [
            'Buen entrenamiento. Press banca: 4x8 80kg, Fondos: 3x12, Extensiones tríceps: 3x15 25kg',
            'Dominadas: 4x6, Remo con barra: 4x8 70kg, Curl bíceps: 3x12 15kg cada brazo',
            'Sentadillas: 4x10 100kg, Peso muerto: 4x6 120kg, Extensiones cuádriceps: 3x15',
            'Press militar: 4x8 50kg, Elevaciones laterales: 3x12 12kg, Pájaros: 3x15 8kg',
            '30 minutos en cinta corriendo a 10km/h, 15 minutos en elíptica',
            'Circuito completo: burpees, flexiones, sentadillas, plancha. 4 rondas'
        ];

        for ($i = 0; $i < 10; $i++) {
            Workout::create([
                'user_id' => $user->id,
                'type' => $workoutTypes[array_rand($workoutTypes)],
                'duration' => rand(45, 120),
                'comments' => $comments[array_rand($comments)],
                'workout_date' => Carbon::now()->subDays(rand(1, 30))
            ]);
        }

        // Usuarios adicionales
        $additionalUsers = [
            [
                'name' => 'María García',
                'email' => 'maria@trackfit.com',
                'password' => Hash::make('password123'),
                'phone' => '+34 666 111 222',
                'birth_date' => '1985-08-22',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@trackfit.com',
                'password' => Hash::make('password123'),
                'phone' => '+34 666 333 444',
                'birth_date' => '1992-12-10',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@trackfit.com',
                'password' => Hash::make('password123'),
                'phone' => '+34 666 555 666',
                'birth_date' => '1988-03-18',
            ]
        ];

        foreach ($additionalUsers as $userData) {
            User::create($userData);
        }
    }
}