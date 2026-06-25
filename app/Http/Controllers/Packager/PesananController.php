<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('packager.pesanan.create');
    }

    private function normalizeProductName($beras, $kemasan)
    {
        $beras = trim($beras);
        if (stripos($beras, 'Beras') === false) {
            $beras = 'Beras ' . $beras;
        }
        // Pastikan format kemasan seragam, misalnya "5 kg" menjadi "5kg"
        $kemasan = strtolower(str_replace(' ', '', trim($kemasan)));
        return ucwords(strtolower($beras)) . ' ' . $kemasan;
    }

    private function getAvailableStock($userId, $jenisProduk = null)
    {
        $kualitasValid = ['layak jual', 'layak_jual'];
        $jenisProduk = strtolower(str_replace(' ', '', $jenisProduk)); // normalize input query

        if ($jenisProduk) {
            $allDikemas = \App\Models\HasilPengemasan::where('user_id', $userId)
                ->whereIn('kualitas', $kualitasValid)
                ->get();
            
            $totalDikemas = $allDikemas->filter(function($k) use ($jenisProduk) {
                $namaKemas = $this->normalizeProductName($k->jenis_beras, $k->jenis_kemasan);
                return strtolower(str_replace(' ', '', $namaKemas)) === $jenisProduk;
            })->sum('jumlah_kemasan');

            $totalTerkirim = Pesanan::where('user_id', $userId)
                ->whereIn('status', ['dikirim', 'selesai'])
                ->get()
                ->filter(function($p) use ($jenisProduk) {
                    return strtolower(str_replace(' ', '', $p->jenis_produk)) === $jenisProduk;
                })->sum('jumlah');

            return $totalDikemas - $totalTerkirim;
        }

        return 0;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        // Data untuk statistik
        $terlaris = Pesanan::where('user_id', Auth::id())
            ->select('jenis_produk', \DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('jenis_produk')
            ->orderByDesc('total_qty')
            ->first();

        // Stock Beras dari Ricemill (Penerimaan Beras yang diterima)
        $total_stok_beras = \App\Models\PenerimaanBeras::where('user_id', Auth::id())
            ->where('status', 'diterima')
            ->sum('jumlah_beras');

        // Kalkulasi daftar stok kemasan yang tersedia (Ready)
        $kualitasValid = ['layak jual', 'layak_jual'];
        $allDikemas = \App\Models\HasilPengemasan::where('user_id', Auth::id())
            ->whereIn('kualitas', $kualitasValid)
            ->get();
            
        $stok_kemasan_list = collect();
        foreach ($allDikemas as $k) {
            $namaKemas = $this->normalizeProductName($k->jenis_beras, $k->jenis_kemasan);
            if (!$stok_kemasan_list->has($namaKemas)) {
                $stok_kemasan_list->put($namaKemas, 0);
            }
            $stok_kemasan_list[$namaKemas] += $k->jumlah_kemasan;
        }

        $allTerkirim = Pesanan::where('user_id', Auth::id())
            ->whereIn('status', ['dikirim', 'selesai'])
            ->get();
            
        foreach ($allTerkirim as $p) {
            // Kita coba mapping dengan nama yang ada (walaupun spasi berbeda, kita samakan polanya)
            $foundKey = $stok_kemasan_list->keys()->first(function($key) use ($p) {
                return strtolower(str_replace(' ', '', $key)) === strtolower(str_replace(' ', '', $p->jenis_produk));
            });
            
            if ($foundKey) {
                $stok_kemasan_list[$foundKey] -= $p->jumlah;
            } else {
                $stok_kemasan_list->put($p->jenis_produk, -$p->jumlah);
            }
        }
        
        // Filter hanya yang stoknya > 0
        $stok_kemasan_list = $stok_kemasan_list->filter(function($val) {
            return $val > 0;
        });

        return view('packager.pesanan.index', compact('pesanan', 'terlaris', 'total_stok_beras', 'stok_kemasan_list'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'jenis_produk'   => 'required|string|max:100',
            'jumlah'         => 'required|integer|min:1',
            'harga_satuan'   => 'required|numeric|min:0',
            'status'         => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
            'catatan'        => 'nullable|string',
        ]);

        $validated['user_id']     = Auth::id();
        $validated['total_harga'] = $request->jumlah * $request->harga_satuan;
        
        // Validasi stok spesifik per jenis_produk saat membuat pesanan baru dengan status dikirim/selesai
        if (in_array($validated['status'], ['dikirim', 'selesai'])) {
            $available_stok = $this->getAvailableStock(Auth::id(), $validated['jenis_produk']);

            if ($validated['jumlah'] > $available_stok) {
                return redirect()->back()->with('error', 'Out of Stock! Stok kemasan ' . $validated['jenis_produk'] . ' tidak mencukupi. (Sisa: ' . $available_stok . ' pack)')->withInput();
            }
        }

        Pesanan::create($validated);

        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Pesanan berhasil dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);
        
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
        ]);

        // Cek jika status berubah menjadi dikirim atau selesai
        if (in_array($validated['status'], ['dikirim', 'selesai']) && !in_array($pesanan->status, ['dikirim', 'selesai'])) {
            $available_stok = $this->getAvailableStock(Auth::id(), $pesanan->jenis_produk);

            if ($pesanan->jumlah > $available_stok) {
                return redirect()->back()->with('error', 'Out of Stock! Stok kemasan ' . $pesanan->jenis_produk . ' tidak mencukupi untuk memproses pesanan ini. (Sisa: ' . $available_stok . ' pack)');
            }
        }

        $pesanan->update($validated);

        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== Auth::id(), 403);
        $pesanan->delete();
        return redirect()->route('packager.pesanan.index')
            ->with('success', 'Data pesanan berhasil dihapus!');
    }
}
