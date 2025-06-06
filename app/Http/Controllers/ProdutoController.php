<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produto\StoreProdutoRequest;
use App\Http\Requests\Produto\UpdateProdutoRequest;
use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('produto.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('produto.form', ['produto' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdutoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $produto = Produto::create([
                'nome' => $validated['nome'],
                'preco' => $validated['preco'],
            ]);

            foreach ($validated['variacoes'] as $variacao) {
                Estoque::create([
                    'produto_id' => $produto->id,
                    'variacao' => $variacao['variacao'],
                    'quantidade' => $variacao['quantidade'],
                ]);
            }
        });
        return redirect()
            ->route('home')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto): View
    {
//        $produto->load('estoques');
        return view('produto.form', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutoRequest $request, Produto $produto): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $produto) {
            $produto->update([
                'nome' => $validated['nome'],
                'preco' => $validated['preco'],
            ]);

            $estoquesExistentes = $produto->estoques->keyBy('variacao')->toArray();

            foreach ($validated['variacoes'] as $variacao) {
                $produto->estoques()->updateOrCreate(
                    ['variacao' => $variacao['variacao']],
                    [
                        'produto_id' => $produto->id,
                        'variacao' => $variacao['variacao'] ?? null,
                        'quantidade' => $variacao['quantidade'],
                    ]
                );
                unset($estoquesExistentes[$variacao['variacao']]);
            }

            if (!empty($estoquesExistentes)) {
                $produto->estoques()->whereIn('variacao', array_keys($estoquesExistentes))->delete();
            }
        });

        return redirect()
            ->route('home')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
    }
}
