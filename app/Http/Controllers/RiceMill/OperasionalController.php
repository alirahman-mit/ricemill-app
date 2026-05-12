<?php

namespace App\Http\Controllers\RiceMill;

use App\Http\Controllers\Controller;
use App\Models\OperasionalPenggilingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operasional = OperasionalPenggilingan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ricemill.operasional.index', compact('operasional'));
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
    public function show(OperasionalPenggilingan $operasionalPenggilingan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperasionalPenggilingan $operasionalPenggilingan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OperasionalPenggilingan $operasionalPenggilingan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperasionalPenggilingan $operasionalPenggilingan)
    {
        //
    }
}
