<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanBerasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerimaan = PenerimaanBeras::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('packager.penerimaan-beras.index', compact('penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('packager.penerimaan-beras.create');
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
    public function show(PenerimaanBeras $penerimaanBeras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenerimaanBeras $penerimaanBeras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenerimaanBeras $penerimaanBeras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenerimaanBeras $penerimaanBeras)
    {
        //
    }
}
