<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardIndex;
use App\Livewire\EquiposIndex;
use App\Livewire\EspecialistaIndex;
use App\Livewire\PacientesIndex;
use App\Livewire\ProcedimientosIndex;
use App\Livewire\RegistrosHolterIndex;
use App\Livewire\ReporteHolterIndex;
use Illuminate\Support\Facades\Auth;

// Redirección inicial al login
Route::get('/', function () {
    $ruta = (Auth::check()) ? 'dashboard' : 'login';
    return redirect()->route($ruta);
});

// Middleware de autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dashboard', DashboardIndex::class)->name('dashboard');

    // Rutas para ADMIN
    Route::middleware(['role:admin'])->group(function () {
        Route::get('pacientes', PacientesIndex::class)->name('pacientes');
        Route::get('especialistas', EspecialistaIndex::class)->name('especialistas');
        Route::get('dispositivos', EquiposIndex::class)->name('dispositivos');
    });

    // Rutas para USER
    Route::middleware(['role:user'])->group(function () {
        Route::get('procedimientos', ProcedimientosIndex::class)->name('procedimientos');
        Route::get('registros/{id}', RegistrosHolterIndex::class)
            ->where('id', '[0-9]+')
            ->name('registros');
    });

    // Rutas para CLIENTE
    Route::middleware(['role:cliente'])->group(function () {
        Route::get('reportes', ReporteHolterIndex::class)->name('reportes');
    });
});
