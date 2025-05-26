<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassController extends Controller
{
    // Obtener todas las clases disponibles
    public function index(Request $request)
    {
        $query = GymClass::query();

        // Filtrar por día de la semana si se proporciona
        if ($request->has('day')) {
            $query->where('day_of_week', $request->day);
        }

        // Filtrar por nombre de clase si se proporciona
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $classes = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Agregar información de disponibilidad para cada clase
        $classesWithAvailability = $classes->map(function ($class) {
            $reservationsCount = $class->reservations()
                ->where('reservation_date', '>=', Carbon::today())
                ->where('status', 'confirmed')
                ->count();

            $class->available_spots = $class->max_capacity - $reservationsCount;
            $class->is_full = $class->available_spots <= 0;

            return $class;
        });

        return response()->json([
            'classes' => $classesWithAvailability
        ]);
    }

    // Obtener una clase específica con su disponibilidad
    public function show($id)
    {
        $class = GymClass::findOrFail($id);
        
        // Calcular disponibilidad para los próximos 7 días
        $availability = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayOfWeek = strtolower($date->format('l'));
            
            if ($dayOfWeek === $class->day_of_week) {
                $reservationsCount = $class->reservations()
                    ->where('reservation_date', $date)
                    ->where('status', 'confirmed')
                    ->count();

                $availability[] = [
                    'date' => $date->format('Y-m-d'),
                    'day_name' => $date->format('l'),
                    'available_spots' => $class->max_capacity - $reservationsCount,
                    'is_full' => ($class->max_capacity - $reservationsCount) <= 0
                ];
            }
        }

        return response()->json([
            'class' => $class,
            'availability' => $availability
        ]);
    }

    // Obtener clases agrupadas por día de la semana
    public function getByDay()
    {
        $classesByDay = GymClass::all()
            ->groupBy('day_of_week')
            ->map(function ($classes) {
                return $classes->sortBy('start_time')->values();
            });

        return response()->json([
            'classes_by_day' => $classesByDay
        ]);
    }

    // Obtener horarios disponibles para una clase específica
    public function getAvailableSchedules($id, Request $request)
    {
        $class = GymClass::findOrFail($id);
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $requestedDate = Carbon::parse($date);
        $dayOfWeek = strtolower($requestedDate->format('l'));

        if ($dayOfWeek !== $class->day_of_week) {
            return response()->json([
                'message' => 'Esta clase no está disponible en el día seleccionado'
            ], 400);
        }

        $reservationsCount = $class->reservations()
            ->where('reservation_date', $requestedDate)
            ->where('status', 'confirmed')
            ->count();

        $availableSpots = $class->max_capacity - $reservationsCount;

        return response()->json([
            'class' => $class,
            'date' => $requestedDate->format('Y-m-d'),
            'available_spots' => $availableSpots,
            'is_available' => $availableSpots > 0 && $requestedDate >= Carbon::today()
        ]);
    }
}