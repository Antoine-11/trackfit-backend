<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Crear una nueva reserva
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:gym_classes,id',
            'reservation_date' => 'required|date|after_or_equal:today',
        ]);

        $user = Auth::user();
        $class = GymClass::findOrFail($request->class_id);
        $reservationDate = Carbon::parse($request->reservation_date);

        // Verificar que el usuario tenga una suscripción activa
        if (!$user->activeSubscription()) {
            return response()->json([
                'message' => 'Necesitas una suscripción activa para hacer reservas'
            ], 403);
        }

        // Verificar que la fecha corresponda al día de la clase
        $dayOfWeek = strtolower($reservationDate->format('l'));
        if ($dayOfWeek !== $class->day_of_week) {
            return response()->json([
                'message' => 'La fecha seleccionada no corresponde al día de esta clase'
            ], 400);
        }

        // Verificar que el usuario no tenga ya una reserva para esta clase en esta fecha
        $existingReservation = $user->reservations()
            ->where('class_id', $class->id)
            ->where('reservation_date', $reservationDate)
            ->where('status', 'confirmed')
            ->first();

        if ($existingReservation) {
            return response()->json([
                'message' => 'Ya tienes una reserva para esta clase en esta fecha'
            ], 400);
        }

        // Verificar disponibilidad
        $reservationsCount = $class->reservations()
            ->where('reservation_date', $reservationDate)
            ->where('status', 'confirmed')
            ->count();

        if ($reservationsCount >= $class->max_capacity) {
            return response()->json([
                'message' => 'Esta clase está llena para la fecha seleccionada'
            ], 400);
        }

        // Crear la reserva
        $reservation = $user->reservations()->create([
            'class_id' => $class->id,
            'reservation_date' => $reservationDate,
            'status' => 'confirmed'
        ]);

        return response()->json([
            'reservation' => $reservation->load('gymClass'),
            'message' => 'Reserva creada exitosamente'
        ], 201);
    }

    // Obtener las reservas del usuario autenticado
    public function myReservations()
    {
        $user = Auth::user();
        
        $reservations = $user->reservations()
            ->with('gymClass')
            ->orderBy('reservation_date', 'asc')
            ->get();

        // Separar reservas futuras y pasadas
        $upcomingReservations = $reservations->filter(function ($reservation) {
            return Carbon::parse($reservation->reservation_date) >= Carbon::today();
        });

        $pastReservations = $reservations->filter(function ($reservation) {
            return Carbon::parse($reservation->reservation_date) < Carbon::today();
        });

        return response()->json([
            'upcoming_reservations' => $upcomingReservations->values(),
            'past_reservations' => $pastReservations->values()
        ]);
    }

    // Cancelar una reserva
    public function cancel($id)
    {
        $user = Auth::user();
        $reservation = $user->reservations()->findOrFail($id);

        // Verificar que la reserva sea futura
        if (Carbon::parse($reservation->reservation_date) < Carbon::today()) {
            return response()->json([
                'message' => 'No puedes cancelar una reserva pasada'
            ], 400);
        }

        // Verificar que la reserva no esté ya cancelada
        if ($reservation->status === 'cancelled') {
            return response()->json([
                'message' => 'Esta reserva ya está cancelada'
            ], 400);
        }

        $reservation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Reserva cancelada exitosamente'
        ]);
    }

    // Obtener todas las reservas (para administradores)
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'gymClass']);

        // Filtrar por fecha si se proporciona
        if ($request->has('date')) {
            $query->where('reservation_date', $request->date);
        }

        // Filtrar por clase si se proporciona
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filtrar por estado si se proporciona
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
            ->paginate(20);

        return response()->json($reservations);
    }

    // Obtener una reserva específica
    public function show($id)
    {
        $user = Auth::user();
        $reservation = $user->reservations()
            ->with('gymClass')
            ->findOrFail($id);

        return response()->json([
            'reservation' => $reservation
        ]);
    }
}