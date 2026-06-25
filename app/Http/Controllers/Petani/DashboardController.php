<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\ProfilLahan;
use App\Models\RiwayatPanen;
use App\Models\SetoranPenggilingan;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total_lahan'     => ProfilLahan::where('user_id', $userId)->count(),
            'total_panen'     => RiwayatPanen::where('user_id', $userId)->count(),
            'total_setoran'   => SetoranPenggilingan::where('user_id', $userId)->count(),
            'pendapatan_bulan'=> RiwayatPanen::where('user_id', $userId)
                                    ->whereMonth('tanggal_panen', now()->month)
                                    ->sum('total_pendapatan'),
        ];

        $recentPanen = RiwayatPanen::with('profilLahan')
            ->where('user_id', $userId)
            ->latest('tanggal_panen')
            ->take(5)
            ->get();

        $recentSetoran = SetoranPenggilingan::where('user_id', $userId)
            ->with('ricemill')
            ->latest('tanggal_setoran')
            ->take(5)
            ->get();

        return view('petani.dashboard', compact('stats', 'recentPanen', 'recentSetoran'));
    }
}