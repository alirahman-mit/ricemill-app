<?php

namespace App\Http\Controllers\Packager;

use App\Http\Controllers\Controller;
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
        $pengemasan = HasilPengemasan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('packager.pengemasan.index', compact('pengemasan'));
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
    public function show(HasilPengemasan $hasilPengemasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HasilPengemasan $hasilPengemasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HasilPengemasan $hasilPengemasan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HasilPengemasan $hasilPengemasan)
    {
        //
    }
}
