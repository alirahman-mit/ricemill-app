<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanGabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenerimaanGabahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenerimaanGabah::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('nama_petani', 'like', '%' . $request->search . '%')
                  ->orWhere('asal_lahan', 'like', '%' . $request->search . '%');
        }

        $penerimaan = $query->with('setoran')->latest('tanggal')->paginate(10)->withQueryString();

        return view('ricemill.penerimaan-gabah.index', compact('penerimaan'));
    }

    public function create()
    {
        $petanis = \App\Models\User::where('role', 'petani')->get();
        return view('ricemill.penerimaan-gabah.create', compact('petanis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_petani'    => 'required|string|max:255',
            'asal_lahan'     => 'nullable|string|max:255',
            'tanggal'        => 'required|date',
            'jumlah_gabah'   => 'required|numeric|min:0.01',
            'kualitas_gabah' => 'required|in:kering,basah,grade_a,grade_b',
            'status'         => 'required|in:menunggu,diterima,diproses,selesai',
            'bukti_foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'        => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('penerimaan', 'public');
        }

        PenerimaanGabah::create($validated);

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan gabah berhasil dicatat!');
    }

    /**
     * BUG FIX: Nama parameter diubah dari $penerimaan → $penerimaanGabah
     * agar cocok dengan route parameter {penerimaan_gabah} (Laravel snake_case ke camelCase).
     * Sebelumnya: edit(PenerimaanGabah $penerimaan) → model binding GAGAL → $penerimaan kosong
     *             → user_id null !== Auth::id() → 403 Forbidden
     */
    public function edit(PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);
        $petanis = \App\Models\User::where('role', 'petani')->get();
        return view('ricemill.penerimaan-gabah.edit', ['penerimaan' => $penerimaanGabah, 'petanis' => $petanis]);
    }

    public function update(Request $request, PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_petani'    => 'required|string|max:255',
            'asal_lahan'     => 'nullable|string|max:255',
            'tanggal'        => 'required|date',
            'jumlah_gabah'   => 'required|numeric|min:0.01',
            'kualitas_gabah' => 'required|in:kering,basah,grade_a,grade_b',
            'status'         => 'required|in:menunggu,diterima,diproses,selesai',
            'bukti_foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan'        => 'nullable|string',
            'biaya_penggilingan' => 'nullable|numeric|min:0',
            'hasil_bersih'       => 'nullable|numeric|min:0',
            'total_pendapatan'   => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('bukti_foto')) {
            if ($penerimaanGabah->bukti_foto) {
                Storage::disk('public')->delete($penerimaanGabah->bukti_foto);
            }
            $validated['bukti_foto'] = $request->file('bukti_foto')->store('penerimaan', 'public');
        }

        $penerimaanGabah->update($validated);

        if ($penerimaanGabah->setoran_id) {
            $penerimaanGabah->setoran->update([
                'status' => $request->status === 'selesai' ? 'selesai' : ($request->status === 'menunggu' ? 'pending' : 'diproses'),
                'biaya_penggilingan' => $request->biaya_penggilingan,
                'hasil_bersih' => $request->hasil_bersih,
                'total_pendapatan' => $request->total_pendapatan,
            ]);
        }

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan berhasil diperbarui!');
    }

    public function destroy(PenerimaanGabah $penerimaanGabah)
    {
        abort_if($penerimaanGabah->user_id !== Auth::id(), 403);

        if ($penerimaanGabah->bukti_foto) {
            Storage::disk('public')->delete($penerimaanGabah->bukti_foto);
        }

        $penerimaanGabah->delete();

        return redirect()->route('ricemill.penerimaan-gabah.index')
            ->with('success', 'Data penerimaan berhasil dihapus!');
    }
}
