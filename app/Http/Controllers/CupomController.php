<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cupom\StoreCupomRequest;
use App\Http\Requests\Cupom\UpdateCupomRequest;
use App\Models\Cupom;
use Illuminate\Http\RedirectResponse;
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
        return  view('cupom.form', ['cupom' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCupomRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Cupom::create([
            'codigo' =>  $validated['codigo'],
            'desconto_percentual'  => $validated['desconto_percentual'],
            'valor_minimo'  => $validated['valor_minimo'],
            'validade'  => $validated['validade'],
        ]);

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom criado com sucesso!');
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
        return view('cupom.form', compact('cupom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCupomRequest $request, Cupom $cupom): RedirectResponse
    {
        $validated = $request->validated();

        $cupom->update([
            'codigo' =>  $validated['codigo'],
            'desconto_percentual'  => $validated['desconto_percentual'],
            'valor_minimo'  => $validated['valor_minimo'],
            'validade'  => $validated['validade'],
        ]);
        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $cupom)
    {
        $cupom->update([
            'ativo'  => false,
        ]);

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom desativado com sucesso!');
    }

    public function active(Cupom $cupom): RedirectResponse
    {
        $cupom->update([
            'ativo' => true,
        ]);

        return redirect()
            ->route('cupons.index')
            ->with('success', 'Cupom ativado com sucesso!');
    }
}
