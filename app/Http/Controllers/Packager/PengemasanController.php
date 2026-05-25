<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use App\Models\HasilPengemasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengemasan = HasilPengemasan::with('penerimaanBeras')
            ->where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        // Chart 1: Jumlah produksi per hari (14 hari terakhir) — Bar Chart
        $chartHarian = HasilPengemasan::where('user_id', Auth::id())
            ->selectRaw('DATE(tanggal) as tgl, SUM(jumlah_kemasan) as total')
            ->where('tanggal', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        // Isi tanggal yang kosong agar chart lengkap 14 hari
        $harian = [];
        for ($i = 13; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $harian[] = [
                'label' => now()->subDays($i)->format('d M'),
                'total' => $chartHarian->get($tgl)?->total ?? 0,
            ];
        }

        // Chart 2: Distribusi jenis kemasan — Pie Chart
        $chartKemasan = HasilPengemasan::where('user_id', Auth::id())
            ->selectRaw('jenis_kemasan, SUM(jumlah_kemasan) as total')
            ->groupBy('jenis_kemasan')
            ->orderByDesc('total')
            ->get();

        return view('packager.pengemasan.index', compact('pengemasan', 'harian', 'chartKemasan'));
    }

    public function create()
    {
        $penerimaan = PenerimaanBeras::where('user_id', Auth::id())
            ->where('status', 'diterima')
            ->get();
        return view('packager.pengemasan.create', compact('penerimaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penerimaan_beras_id' => 'required|exists:penerimaan_beras,id',
            'tanggal'             => 'required|date',
            'jenis_beras'         => 'required|string|max:100',
            'jenis_kemasan'       => 'required|in:5kg,10kg,25kg,50kg',
            'jumlah_kemasan'      => 'required|integer|min:1',
            'kualitas'            => 'required|in:layak_jual,reject',
            'catatan'             => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        HasilPengemasan::create($validated);

        return redirect()->route('packager.pengemasan.index')
            ->with('success', 'Hasil pengemasan berhasil dicatat!');
    }

    public function destroy(HasilPengemasan $pengemasan)
    {
        abort_if($pengemasan->user_id !== Auth::id(), 403);
        $pengemasan->delete();
        return redirect()->route('packager.pengemasan.index')
            ->with('success', 'Data pengemasan berhasil dihapus!');
    }
}
