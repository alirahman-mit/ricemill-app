<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Petani\DashboardController;
use App\Http\Controllers\Petani\ProfilLahanController;
use App\Http\Controllers\Petani\RiwayatPanenController;
use App\Http\Controllers\Petani\SetoranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ── Petani Routes ──
Route::middleware(['auth'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lahan', ProfilLahanController::class);
    Route::resource('panen', RiwayatPanenController::class);
    Route::resource('setoran', SetoranController::class)->except(['show']);
});

// ── Rice Mill Routes (placeholder) ──
Route::middleware(['auth'])->prefix('ricemill')->name('ricemill.')->group(function () {
    Route::get('/dashboard', function () {
        return view('home'); // placeholder — replace with real controller later
    })->name('dashboard');
});

// ── Packager Routes (placeholder) ──
Route::middleware(['auth'])->prefix('packager')->name('packager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('home'); // placeholder — replace with real controller later
    })->name('dashboard');
});