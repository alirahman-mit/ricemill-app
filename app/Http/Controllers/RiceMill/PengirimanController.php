<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\PengirimanBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengiriman = PengirimanBeras::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ricemill.pengiriman.index', compact('pengiriman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(PengirimanBeras $pengirimanBeras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengirimanBeras $pengirimanBeras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengirimanBeras $pengirimanBeras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengirimanBeras $pengirimanBeras)
    {
        //
    }
}
