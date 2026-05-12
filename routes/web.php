<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Petani\DashboardController     as PetaniDashboard;
use App\Http\Controllers\Petani\ProfilLahanController;
use App\Http\Controllers\Petani\RiwayatPanenController;
use App\Http\Controllers\Petani\SetoranController;
use App\Http\Controllers\RiceMill\DashboardController  as RiceMillDashboard;
use App\Http\Controllers\Packager\DashboardController  as PackagerDashboard;

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

// ── Petani Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [PetaniDashboard::class, 'index'])->name('dashboard');
    Route::resource('lahan',   ProfilLahanController::class);
    Route::resource('panen',   RiwayatPanenController::class);
    Route::resource('setoran', SetoranController::class)->except(['show']);
});

// ── Rice Mill Routes ───────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('ricemill')->name('ricemill.')->group(function () {
    Route::get('/dashboard', [RiceMillDashboard::class, 'index'])->name('dashboard');

    // Penerimaan Gabah (task 4.x)
    Route::get('/penerimaan-gabah',            fn() => view('ricemill.dashboard'))->name('penerimaan-gabah.index');
    Route::get('/penerimaan-gabah/create',     fn() => view('ricemill.dashboard'))->name('penerimaan-gabah.create');

    // Operasional Penggilingan (task 5.x)
    Route::get('/operasional',                 fn() => view('ricemill.dashboard'))->name('operasional.index');

    // Riwayat Produksi (task 6.x)
    Route::get('/produksi',                    fn() => view('ricemill.dashboard'))->name('produksi.index');

    // Pengiriman Beras ke Packager (task 7.x)
    Route::get('/pengiriman',                  fn() => view('ricemill.dashboard'))->name('pengiriman.index');

    // Keuangan (task 8.x)
    Route::get('/keuangan',                    fn() => view('ricemill.dashboard'))->name('keuangan.index');
});

// ── Packager Routes ────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('packager')->name('packager.')->group(function () {
    Route::get('/dashboard', [PackagerDashboard::class, 'index'])->name('dashboard');

    // Penerimaan Beras Putih (task 9.x)
    Route::get('/penerimaan-beras',            fn() => view('packager.dashboard'))->name('penerimaan-beras.index');
    Route::get('/penerimaan-beras/create',     fn() => view('packager.dashboard'))->name('penerimaan-beras.create');

    // Hasil Pengemasan (task 10.x)
    Route::get('/pengemasan',                  fn() => view('packager.dashboard'))->name('pengemasan.index');

    // Pesanan Masuk (task 11.x)
    Route::get('/pesanan',                     fn() => view('packager.dashboard'))->name('pesanan.index');
});