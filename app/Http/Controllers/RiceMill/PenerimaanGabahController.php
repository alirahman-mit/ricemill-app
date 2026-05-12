<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanGabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanGabahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerimaan = PenerimaanGabah::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ricemill.penerimaan-gabah.index', compact('penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ricemill.penerimaan-gabah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PenerimaanGabah $penerimaanGabah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenerimaanGabah $penerimaanGabah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenerimaanGabah $penerimaanGabah)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenerimaanGabah $penerimaanGabah)
    {
        //
    }
}
