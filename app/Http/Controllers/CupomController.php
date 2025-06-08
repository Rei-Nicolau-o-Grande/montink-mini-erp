<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cupom\StoreCupomRequest;
use App\Http\Requests\Cupom\UpdateCupomRequest;
use App\Models\Cupom;
use Illuminate\View\View;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $cupons = Cupom::all();

        return view('cupom.index',  compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return  view('cupom.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCupomRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cupom $cupom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupom $cupom): View
    {
        return view('cupom.form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCupomRequest $request, Cupom $cupom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $cupom)
    {
        //
    }
}
