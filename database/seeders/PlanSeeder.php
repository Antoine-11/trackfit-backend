<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Plan Básico',
                'price' => 29.99,
                'duration_months' => 1,
                'description' => 'Acceso completo al gimnasio durante 1 mes. Incluye uso de todas las máquinas y pesas libres.'
            ],
            [
                'name' => 'Plan Premium',
                'price' => 79.99,
                'duration_months' => 3,
                'description' => 'Acceso completo por 3 meses + clases grupales ilimitadas + 1 sesión de entrenamiento personal.'
            ],
            [
                'name' => 'Plan VIP',
                'price' => 149.99,
                'duration_months' => 6,
                'description' => 'Acceso completo por 6 meses + clases grupales + 4 sesiones de entrenamiento personal + acceso a zona VIP.'
            ],
            [
                'name' => 'Plan Anual',
                'price' => 279.99,
                'duration_months' => 12,
                'description' => 'El mejor precio. Acceso completo por 12 meses + todos los beneficios + nutricionista incluido.'
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}