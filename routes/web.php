<?php

use App\Http\Controllers\TesteTabelasController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::get('/testeTabelas', TesteTabelasController::class)->name('testeTabelas');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});
require __DIR__.'/settings.php';
