<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Petani\DashboardController     as PetaniDashboard;
use App\Http\Controllers\Petani\ProfilLahanController;
use App\Http\Controllers\Petani\RiwayatPanenController;
use App\Http\Controllers\Petani\SetoranController;
use App\Http\Controllers\RiceMill\DashboardController  as RiceMillDashboard;
use App\Http\Controllers\RiceMill\PenerimaanGabahController;
use App\Http\Controllers\RiceMill\OperasionalController;
use App\Http\Controllers\RiceMill\ProduksiController;
use App\Http\Controllers\RiceMill\PengirimanController;
use App\Http\Controllers\RiceMill\KeuanganController;
use App\Http\Controllers\Packager\DashboardController  as PackagerDashboard;
use App\Http\Controllers\Packager\PenerimaanBerasController;
use App\Http\Controllers\Packager\PengemasanController;
use App\Http\Controllers\Packager\PesananController;

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

    // Penerimaan Gabah
    Route::get('/penerimaan-gabah',            [PenerimaanGabahController::class, 'index'])->name('penerimaan-gabah.index');
    Route::get('/penerimaan-gabah/create',     [PenerimaanGabahController::class, 'create'])->name('penerimaan-gabah.create');

    // Operasional Penggilingan
    Route::get('/operasional',                 [OperasionalController::class, 'index'])->name('operasional.index');

    // Riwayat Produksi
    Route::get('/produksi',                    [ProduksiController::class, 'index'])->name('produksi.index');

    // Pengiriman Beras ke Packager
    Route::get('/pengiriman',                  [PengirimanController::class, 'index'])->name('pengiriman.index');

    // Keuangan
    Route::get('/keuangan',                    [KeuanganController::class, 'index'])->name('keuangan.index');
});

// ── Packager Routes ────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('packager')->name('packager.')->group(function () {
    Route::get('/dashboard', [PackagerDashboard::class, 'index'])->name('dashboard');

    // Penerimaan Beras Putih
    Route::get('/penerimaan-beras',            [PenerimaanBerasController::class, 'index'])->name('penerimaan-beras.index');
    Route::get('/penerimaan-beras/create',     [PenerimaanBerasController::class, 'create'])->name('penerimaan-beras.create');

    // Hasil Pengemasan
    Route::get('/pengemasan',                  [PengemasanController::class, 'index'])->name('pengemasan.index');

    // Pesanan Masuk
    Route::get('/pesanan',                     [PesananController::class, 'index'])->name('pesanan.index');
});