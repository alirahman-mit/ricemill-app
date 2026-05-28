<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\KeuanganRicemill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    // Daftar kategori yang valid — sinkron dengan view
    const KATEGORI_VALID = [
        'Penjualan Beras',
        'Jasa Penggilingan',
        'Biaya Listrik/Mesin',
        'Gaji Karyawan',
        'Logistik/Transport',
        'Pembelian Bahan Baku',
        'Perawatan Mesin',
        'Lain-lain',
    ];

    public function index()
    {
        $keuangan = KeuanganRicemill::where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        $totalPemasukan = KeuanganRicemill::where('user_id', Auth::id())
            ->where('tipe', 'pemasukan')->sum('jumlah');

        $totalPengeluaran = KeuanganRicemill::where('user_id', Auth::id())
            ->where('tipe', 'pengeluaran')->sum('jumlah');

        return view('ricemill.keuangan.index', compact('keuangan', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function create()
    {
        $kategoriList = self::KATEGORI_VALID;
        return view('ricemill.keuangan.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $kategoriValid = implode(',', array_map(fn($k) => '"' . $k . '"', self::KATEGORI_VALID));

        $validated = $request->validate([
            'tipe'       => 'required|in:pemasukan,pengeluaran',
            'kategori'   => 'required|string|max:100',
            'jumlah'     => 'required|numeric|min:0.01',
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string|max:255',
            'catatan'    => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        KeuanganRicemill::create($validated);

        return redirect()->route('ricemill.keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil dicatat!');
    }

    public function destroy(KeuanganRicemill $keuangan)
    {
        abort_if($keuangan->user_id !== Auth::id(), 403);
        $keuangan->delete();
        return redirect()->route('ricemill.keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus!');
    }
}
