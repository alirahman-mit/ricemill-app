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

    public function index(Request $request)
    {
        $query = KeuanganRicemill::where('user_id', Auth::id());

        // Dropdown Filter Bulan (Y-m)
        $selectedBulan = $request->get('bulan');
        if ($selectedBulan && $selectedBulan !== 'all') {
            $query->whereMonth('tanggal', substr($selectedBulan, 5, 2))
                  ->whereYear('tanggal', substr($selectedBulan, 0, 4));
        }

        $keuangan = $query->clone()->latest('tanggal')->paginate(10)->withQueryString();

        $totalPemasukan = $query->clone()->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $query->clone()->where('tipe', 'pengeluaran')->sum('jumlah');

        // Untuk daftar Pemasukan, Pengeluaran, dan Overall per bulan, kita ambil semua data tanpa filter bulan
        // agar user tetap bisa melihat perbandingan antar bulan.
        $allKeuangan = KeuanganRicemill::where('user_id', Auth::id())->get();

        $pemasukanPerBulan = $allKeuangan->where('tipe', 'pemasukan')
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m'))
            ->map(fn($row) => $row->sum('jumlah'))
            ->sortKeysDesc();

        $pengeluaranPerBulan = $allKeuangan->where('tipe', 'pengeluaran')
            ->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m'))
            ->map(fn($row) => $row->sum('jumlah'))
            ->sortKeysDesc();

        $overallPerBulan = collect();
        $semuaBulan = $pemasukanPerBulan->keys()->merge($pengeluaranPerBulan->keys())->unique()->sortDesc();
        foreach ($semuaBulan as $b) {
            $pemasukan = $pemasukanPerBulan->get($b, 0);
            $pengeluaran = $pengeluaranPerBulan->get($b, 0);
            $overallPerBulan->put($b, $pemasukan - $pengeluaran);
        }

        // Kategori Pemasukan & Pengeluaran (terpengaruh filter)
        $kategoriPemasukan = $query->clone()->where('tipe', 'pemasukan')
            ->select('kategori', \DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get();

        $kategoriPengeluaran = $query->clone()->where('tipe', 'pengeluaran')
            ->select('kategori', \DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get();

        // Daftar opsi bulan untuk dropdown (dari semua data)
        $bulanOptions = $semuaBulan;

        return view('ricemill.keuangan.index', compact(
            'keuangan', 'totalPemasukan', 'totalPengeluaran', 
            'pemasukanPerBulan', 'pengeluaranPerBulan', 'overallPerBulan',
            'kategoriPemasukan', 'kategoriPengeluaran', 'selectedBulan', 'bulanOptions'
        ));
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
