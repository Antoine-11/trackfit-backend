<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    // Obtener todos los entrenamientos del usuario autenticado
    public function index()
    {
        $workouts = Auth::user()->workouts()
            ->orderBy('workout_date', 'desc')
            ->get();

        return response()->json([
            'workouts' => $workouts,
            'message' => 'Entrenamientos obtenidos exitosamente'
        ]);
    }

    // Crear un nuevo entrenamiento
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:600', // máximo 10 horas
            'comments' => 'nullable|string|max:1000',
            'workout_date' => 'required|date|before_or_equal:today',
        ]);

        $workout = Auth::user()->workouts()->create([
            'type' => $request->type,
            'duration' => $request->duration,
            'comments' => $request->comments,
            'workout_date' => $request->workout_date,
        ]);

        return response()->json([
            'workout' => $workout,
            'message' => 'Entrenamiento registrado exitosamente'
        ], 201);
    }

    // Obtener un entrenamiento específico
    public function show($id)
    {
        $workout = Auth::user()->workouts()->findOrFail($id);

        return response()->json([
            'workout' => $workout
        ]);
    }

    // Actualizar un entrenamiento
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'sometimes|required|string|max:255',
            'duration' => 'sometimes|required|integer|min:1|max:600',
            'comments' => 'nullable|string|max:1000',
            'workout_date' => 'sometimes|required|date|before_or_equal:today',
        ]);

        $workout = Auth::user()->workouts()->findOrFail($id);
        $workout->update($request->only(['type', 'duration', 'comments', 'workout_date']));

        return response()->json([
            'workout' => $workout,
            'message' => 'Entrenamiento actualizado exitosamente'
        ]);
    }

    // Eliminar un entrenamiento
    public function destroy($id)
    {
        $workout = Auth::user()->workouts()->findOrFail($id);
        $workout->delete();

        return response()->json([
            'message' => 'Entrenamiento eliminado exitosamente'
        ]);
    }

    // Obtener estadísticas de entrenamientos
    public function stats()
    {
        $user = Auth::user();
        
        $totalWorkouts = $user->workouts()->count();
        $totalMinutes = $user->workouts()->sum('duration');
        $averageDuration = $totalWorkouts > 0 ? round($totalMinutes / $totalWorkouts) : 0;
        
        // Entrenamientos por tipo
        $workoutsByType = $user->workouts()
            ->selectRaw('type, COUNT(*) as count, SUM(duration) as total_duration')
            ->groupBy('type')
            ->get();

        // Entrenamientos del mes actual
        $thisMonthWorkouts = $user->workouts()
            ->whereMonth('workout_date', now()->month)
            ->whereYear('workout_date', now()->year)
            ->count();

        return response()->json([
            'stats' => [
                'total_workouts' => $totalWorkouts,
                'total_minutes' => $totalMinutes,
                'average_duration' => $averageDuration,
                'this_month_workouts' => $thisMonthWorkouts,
                'workouts_by_type' => $workoutsByType
            ]
        ]);
    }
}