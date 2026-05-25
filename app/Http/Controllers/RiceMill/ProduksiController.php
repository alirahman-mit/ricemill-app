<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\RiwayatProduksi;
use App\Models\OperasionalPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = RiwayatProduksi::with('operasional')
            ->where('user_id', Auth::id());

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_proses', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_proses', $request->tahun);
        }

        $produksi = $query->latest('tanggal_proses')->paginate(10)->withQueryString();

        // Data perbandingan 6 bulan terakhir untuk chart
        $perbandingan = RiwayatProduksi::where('user_id', Auth::id())
            ->selectRaw('YEAR(tanggal_proses) as tahun, MONTH(tanggal_proses) as bulan, SUM(jumlah_beras) as total_beras, SUM(jumlah_gabah) as total_gabah')
            ->where('tanggal_proses', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw('YEAR(tanggal_proses), MONTH(tanggal_proses)')
            ->orderByRaw('YEAR(tanggal_proses), MONTH(tanggal_proses)')
            ->get();

        $tahunList = range(now()->year - 2, now()->year);

        return view('ricemill.produksi.index', compact('produksi', 'perbandingan', 'tahunList'));
    }

    public function create()
    {
        $operasional = OperasionalPenggilingan::where('user_id', Auth::id())
            ->where('status', '!=', 'selesai')
            ->get();

        return view('ricemill.produksi.create', compact('operasional'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operasional_id' => 'required|exists:operasional_penggilingan,id',
            'tanggal_proses' => 'required|date',
            'jumlah_beras'   => 'required|numeric|min:0.01',
            'jenis_beras'    => 'required|in:premium,medium,setra_ramos,pandan_wangi,biasa',
            'catatan'        => 'nullable|string',
        ]);

        $operasional = OperasionalPenggilingan::findOrFail($request->operasional_id);
        
        $validated['user_id']      = Auth::id();
        $validated['batch_id']     = $operasional->batch_id;
        $validated['jumlah_gabah'] = $operasional->jumlah_gabah_masuk;

        // Cek rendemen rendah (misal di bawah 60%)
        $rendemen = ($request->jumlah_beras / $operasional->jumlah_gabah_masuk) * 100;
        $validated['notifikasi_rendemen_rendah'] = $rendemen < 60;

        RiwayatProduksi::create($validated);

        // Update status operasional & penerimaan gabah
        $operasional->update(['status' => 'selesai']);
        if ($operasional->penerimaanGabah) {
            $operasional->penerimaanGabah->update(['status' => 'selesai']);
        }

        $redirectMsg = 'Hasil produksi berhasil dicatat!';
        if ($rendemen < 60) {
            $redirectMsg = "⚠️ Hasil produksi dicatat, namun rendemen hanya {$rendemen}% — di bawah standar (60%)!";
        }

        return redirect()->route('ricemill.produksi.index')
            ->with($rendemen < 60 ? 'warning' : 'success', $redirectMsg);
    }

    public function destroy(RiwayatProduksi $produksi)
    {
        abort_if($produksi->user_id !== Auth::id(), 403);
        $produksi->delete();
        return redirect()->route('ricemill.produksi.index')
            ->with('success', 'Data produksi berhasil dihapus!');
    }
}
