<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    // Obtener todos los planes disponibles
    public function getPlans()
    {
        $plans = Plan::all();

        return response()->json([
            'plans' => $plans
        ]);
    }

    // Suscribirse a un plan
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|string', // Para futuras implementaciones de pago
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Verificar si el usuario ya tiene una suscripción activa
        $activeSubscription = $user->activeSubscription();
        
        if ($activeSubscription) {
            return response()->json([
                'message' => 'Ya tienes una suscripción activa'
            ], 400);
        }

        // Crear nueva suscripción
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($plan->duration_months);

        $subscription = $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active'
        ]);

        return response()->json([
            'subscription' => $subscription->load('plan'),
            'message' => 'Suscripción creada exitosamente'
        ], 201);
    }

    // Obtener la suscripción activa del usuario
    public function getActiveSubscription()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'message' => 'No tienes una suscripción activa'
            ], 404);
        }

        // Calcular días restantes
        $daysRemaining = Carbon::now()->diffInDays($subscription->end_date, false);
        
        return response()->json([
            'subscription' => $subscription->load('plan'),
            'days_remaining' => max(0, $daysRemaining),
            'is_expired' => $daysRemaining < 0
        ]);
    }

    // Obtener historial de suscripciones
    public function getSubscriptionHistory()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'subscriptions' => $subscriptions
        ]);
    }

    // Cancelar suscripción
    public function cancelSubscription()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'message' => 'No tienes una suscripción activa para cancelar'
            ], 404);
        }

        $subscription->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Suscripción cancelada exitosamente'
        ]);
    }
}