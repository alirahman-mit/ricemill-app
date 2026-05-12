<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use App\Models\HasilPengemasan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bulan  = now()->month;
        $tahun  = now()->year;

        // ── Stat Cards ──────────────────────────────────────────────
        $totalPenerimaan = PenerimaanBeras::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->count();

        $totalKemasan = HasilPengemasan::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah_kemasan');

        // Efisiensi: (layak jual / total) * 100
        $totalProduksi  = HasilPengemasan::where('user_id', $userId)->count();
        $layakJual      = HasilPengemasan::where('user_id', $userId)->where('kualitas', 'layak jual')->count();
        $reject         = HasilPengemasan::where('user_id', $userId)->where('kualitas', 'reject')->count();
        $efisiensi      = $totalProduksi > 0 ? round(($layakJual / $totalProduksi) * 100, 1) : 0;

        $totalPesanan   = Pesanan::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->count();

        $pesananPending = Pesanan::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Omzet bulan ini dari pesanan selesai
        $omzetBulan = Pesanan::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('total_harga');

        // ── Distribusi Kemasan ──────────────────────────────────────
        $kemasan5kg  = HasilPengemasan::where('user_id', $userId)->where('jenis_kemasan', '5kg')
            ->whereMonth('tanggal', $bulan)->sum('jumlah_kemasan');
        $kemasan10kg = HasilPengemasan::where('user_id', $userId)->where('jenis_kemasan', '10kg')
            ->whereMonth('tanggal', $bulan)->sum('jumlah_kemasan');
        $kemasan25kg = HasilPengemasan::where('user_id', $userId)->where('jenis_kemasan', '25kg')
            ->whereMonth('tanggal', $bulan)->sum('jumlah_kemasan');

        $stats = [
            'total_penerimaan' => $totalPenerimaan,
            'total_kemasan'    => $totalKemasan,
            'efisiensi'        => $efisiensi,
            'total_pesanan'    => $totalPesanan,
            'pesanan_pending'  => $pesananPending,
            'omzet_bulan'      => $omzetBulan,
            'kemasan_5kg'      => $kemasan5kg,
            'kemasan_10kg'     => $kemasan10kg,
            'kemasan_25kg'     => $kemasan25kg,
            'layak_jual'       => $layakJual,
            'reject'           => $reject,
        ];

        // ── Recent Data ─────────────────────────────────────────────
        $recentPenerimaan = PenerimaanBeras::where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentPengemasan = HasilPengemasan::where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentPesanan = Pesanan::where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('packager.dashboard', compact(
            'stats',
            'recentPenerimaan',
            'recentPengemasan',
            'recentPesanan'
        ));
    }
}
