<?php

use App\Http\Controllers\ComputadorController;
use App\Http\Controllers\DashboardController;
use App\Services\PcParts\PcPartsClient;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pcapi/teste', fn (PcPartsClient $pcParts) => response()->json($pcParts->parts(['limit' => 10])))
        ->name('pcapi.teste');
    Route::get('/computador', [ComputadorController::class, 'index'])->name('computador.index');
    Route::get('/computador/create', [ComputadorController::class, 'create'])->name('computador.create');
    Route::post('/computador/store', [ComputadorController::class, 'store'])->name('computador.store');
    Route::get('/computador/pecas/buscar', [ComputadorController::class, 'buscarPecas'])->name('computador.pecas.buscar');
    Route::get('/computador/edit/{computador}', [ComputadorController::class, 'edit'])->name('computador.edit');
    Route::put('/computador/update/{computador}', [ComputadorController::class, 'update'])->name('computador.update');
    Route::delete('/computador/delete/{computador}', [ComputadorController::class, 'destroy'])->name('computador.delete');
    Route::get('/computador/show/{computador}', [ComputadorController::class, 'show'])->name('computador.show');
});
require __DIR__.'/settings.php';
