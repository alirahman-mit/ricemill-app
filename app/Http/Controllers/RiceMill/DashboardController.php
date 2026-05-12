<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanGabah;
use App\Models\OperasionalPenggilingan;
use App\Models\RiwayatProduksi;
use App\Models\PengirimanBeras;
use App\Models\KeuanganRicemill;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bulan  = now()->month;
        $tahun  = now()->year;

        // ── Stat Cards ──────────────────────────────────────────────
        $totalPenerimaan = PenerimaanGabah::where('user_id', $userId)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->count();

        $totalOperasional = OperasionalPenggilingan::where('user_id', $userId)
            ->whereMonth('tanggal_proses', $bulan)->whereYear('tanggal_proses', $tahun)
            ->count();

        $totalProduksiKg = RiwayatProduksi::where('user_id', $userId)
            ->whereMonth('tanggal_proses', $bulan)->whereYear('tanggal_proses', $tahun)
            ->sum('jumlah_beras');

        // Rendemen rata-rata bulan ini
        $produksiBulan = RiwayatProduksi::where('user_id', $userId)
            ->whereMonth('tanggal_proses', $bulan)->whereYear('tanggal_proses', $tahun)
            ->get();

        $rendemenRata = 0;
        if ($produksiBulan->isNotEmpty()) {
            $totalGabah = $produksiBulan->sum('jumlah_gabah');
            $totalBeras = $produksiBulan->sum('jumlah_beras');
            $rendemenRata = $totalGabah > 0 ? round(($totalBeras / $totalGabah) * 100, 1) : 0;
        }

        $totalPengiriman = PengirimanBeras::where('user_id', $userId)
            ->whereMonth('tanggal_kirim', $bulan)->whereYear('tanggal_kirim', $tahun)
            ->count();

        $pengirimanMenunggu = PengirimanBeras::where('user_id', $userId)
            ->where('status', 'menunggu')->count();

        // ── Ringkasan Status Proses ──────────────────────────────────
        $prosesMenunggu = OperasionalPenggilingan::where('user_id', $userId)
            ->where('status', 'menunggu')->count();
        $prosesBerjalan = OperasionalPenggilingan::where('user_id', $userId)
            ->where('status', 'diproses')->count();
        $prosesSelesai  = OperasionalPenggilingan::where('user_id', $userId)
            ->where('status', 'selesai')->count();

        // ── Keuangan Bulan Ini ──────────────────────────────────────
        $pemasukanBulan = KeuanganRicemill::where('user_id', $userId)
            ->where('tipe', 'pemasukan')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $pengeluaranBulan = KeuanganRicemill::where('user_id', $userId)
            ->where('tipe', 'pengeluaran')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $stats = [
            'total_penerimaan'    => $totalPenerimaan,
            'total_operasional'   => $totalOperasional,
            'total_produksi_kg'   => $totalProduksiKg,
            'rendemen_rata'       => $rendemenRata,
            'total_pengiriman'    => $totalPengiriman,
            'pengiriman_menunggu' => $pengirimanMenunggu,
            'proses_menunggu'     => $prosesMenunggu,
            'proses_berjalan'     => $prosesBerjalan,
            'proses_selesai'      => $prosesSelesai,
            'pemasukan_bulan'     => $pemasukanBulan,
            'pengeluaran_bulan'   => $pengeluaranBulan,
        ];

        // ── Recent Data ─────────────────────────────────────────────
        $recentPenerimaan = PenerimaanGabah::where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentProduksi = RiwayatProduksi::where('user_id', $userId)
            ->latest('tanggal_proses')
            ->take(5)
            ->get();

        $recentPengiriman = PengirimanBeras::where('user_id', $userId)
            ->latest('tanggal_kirim')
            ->take(5)
            ->get();

        return view('ricemill.dashboard', compact(
            'stats',
            'recentPenerimaan',
            'recentProduksi',
            'recentPengiriman'
        ));
    }
}
