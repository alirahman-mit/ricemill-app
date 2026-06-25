<?php

namespace App\Http\Controllers\Petani;

use App\Http\Controllers\Controller;
use App\Models\SetoranPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = SetoranPenggilingan::where('user_id', Auth::id())
            ->with('ricemill')
            ->latest()
            ->paginate(10);

        $totalPendapatan = SetoranPenggilingan::where('user_id', Auth::id())
            ->sum('total_pendapatan');

        return view('petani.setoran.index', compact('setorans', 'totalPendapatan'));
    }

    public function create()
    {
        $ricemills = \App\Models\User::where('role', 'rice_mill')->get();
        $lahans = \App\Models\ProfilLahan::where('user_id', Auth::id())->get();
        return view('petani.setoran.create', compact('ricemills', 'lahans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ricemill_id'        => 'required|exists:users,id',
            'profil_lahan_id'    => 'nullable|exists:profil_lahans,id',
            'tanggal_setoran'    => 'required|date',
            'jenis_hasil_panen'  => 'required|string|max:100',
            'jumlah_setoran'     => 'required|numeric|min:0.01',
            'catatan'            => 'nullable|string',
            'bukti_nota'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_nota')) {
            $validated['bukti_nota'] = $request->file('bukti_nota')->store('setoran', 'public');
        }

        $setoran = SetoranPenggilingan::create($validated);
        
        $asal_lahan = '-';
        if ($setoran->profil_lahan_id) {
            $lahan = \App\Models\ProfilLahan::find($setoran->profil_lahan_id);
            if ($lahan) {
                $asal_lahan = $lahan->nama_lahan . ' (' . $lahan->lokasi . ')';
            }
        }

        // Otomatis buat data penerimaan gabah untuk ricemill tujuan
        \App\Models\PenerimaanGabah::create([
            'user_id'        => $validated['ricemill_id'], // Operator Ricemill
            'setoran_id'     => $setoran->id,
            'nama_petani'    => Auth::user()->name,
            'asal_lahan'     => $asal_lahan,
            'tanggal'        => $validated['tanggal_setoran'],
            'jumlah_gabah'   => $validated['jumlah_setoran'],
            'kualitas_gabah' => 'kering', // Default kualitas gabah
            'status'         => 'menunggu',
            'catatan'        => $validated['catatan'],
        ]);

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Transaksi setoran berhasil dicatat!');
    }

    public function edit(SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);
        $ricemills = \App\Models\User::where('role', 'rice_mill')->get();
        $lahans = \App\Models\ProfilLahan::where('user_id', Auth::id())->get();
        return view('petani.setoran.edit', compact('setoran', 'ricemills', 'lahans'));
    }

    public function update(Request $request, SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);

        // BUG FIX: 'status' ditambahkan ke validasi agar perubahan status tersimpan
        $validated = $request->validate([
            'ricemill_id'        => 'required|exists:users,id',
            'profil_lahan_id'    => 'nullable|exists:profil_lahans,id',
            'tanggal_setoran'    => 'required|date',
            'jenis_hasil_panen'  => 'required|string|max:100',
            'jumlah_setoran'     => 'required|numeric|min:0.01',
            'status'             => 'required|in:pending,diproses,selesai',
            'catatan'            => 'nullable|string',
            'bukti_nota'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('bukti_nota')) {
            if ($setoran->bukti_nota) {
                Storage::disk('public')->delete($setoran->bukti_nota);
            }
            $validated['bukti_nota'] = $request->file('bukti_nota')->store('setoran', 'public');
        }

        $setoran->update($validated);
        
        // Update asal_lahan di penerimaan gabah jika berubah
        if ($setoran->penerimaan_gabah) {
            $asal_lahan = '-';
            if ($setoran->profil_lahan_id) {
                $lahan = \App\Models\ProfilLahan::find($setoran->profil_lahan_id);
                if ($lahan) {
                    $asal_lahan = $lahan->nama_lahan . ' (' . $lahan->lokasi . ')';
                }
            }
            $setoran->penerimaan_gabah->update([
                'asal_lahan' => $asal_lahan,
            ]);
        }

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Data setoran berhasil diperbarui!');
    }

    public function destroy(SetoranPenggilingan $setoran)
    {
        abort_if($setoran->user_id !== Auth::id(), 403);

        if ($setoran->bukti_nota) {
            Storage::disk('public')->delete($setoran->bukti_nota);
        }

        $setoran->delete();

        return redirect()->route('petani.setoran.index')
            ->with('success', 'Data setoran berhasil dihapus!');
    }
}