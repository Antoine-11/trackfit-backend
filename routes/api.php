<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ReservationController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Obtener planes (público para mostrar en la página de tarifas)
Route::get('/subscriptions/plans', [SubscriptionController::class, 'getPlans']);

// Obtener clases (público para mostrar información básica)
Route::get('/classes', [ClassController::class, 'index']);
Route::get('/classes/{id}', [ClassController::class, 'show']);
Route::get('/classes-by-day', [ClassController::class, 'getByDay']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Entrenamientos
    Route::apiResource('workouts', WorkoutController::class);
    Route::get('/workout-stats', [WorkoutController::class, 'stats']);
    
    // Suscripciones
    Route::post('/subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('/subscriptions/active', [SubscriptionController::class, 'getActiveSubscription']);
    Route::get('/history/subscriptions', [SubscriptionController::class, 'getSubscriptionHistory']);
    Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancelSubscription']);
    
    // Clases (rutas protegidas)
    Route::get('/classes/{id}/schedules', [ClassController::class, 'getAvailableSchedules']);
    
    // Reservas
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/my-reservations', [ReservationController::class, 'myReservations']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    
    // Rutas administrativas (opcional para futuro)
    Route::get('/admin/reservations', [ReservationController::class, 'index']);
});