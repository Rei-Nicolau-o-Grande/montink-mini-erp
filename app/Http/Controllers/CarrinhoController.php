<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function addToCart(Request $request): RedirectResponse
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'estoque_id' => 'required|exists:estoques,id',
        ]);

        $produto = Produto::find($request->produto_id);
        $estoque = Estoque::find($request->estoque_id);

        // Verifica estoque disponível
        if ($estoque->quantidade <= 0) {
            return redirect()->back()->with('error', 'Estoque esgotado!');
        }

        $carrinho = session()->get('carrinho', []);

        $chave = $estoque->id;

        if (isset($carrinho[$chave])) {
            $carrinho[$chave]['quantidade']++;
        } else {
            $carrinho[$chave] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'variacao' => $estoque->variacao,
                'quantidade' => 1,
                'preco' => $produto->preco,
            ];
        }

        session()->put('carrinho', $carrinho);

        return redirect()
            ->back()
            ->with('success', 'Produto adicionado ao carrinho!');
    }

    public function removeItemCart(Request $request): RedirectResponse
    {
        $indice = $request->input('indice');
        $carrinho = session()->get('carrinho', []);
        unset($carrinho[$indice]);
        session()->put('carrinho', $carrinho);
        return back()->with('success', 'Item removido do carrinho.');
    }

    public function updateQuantidadeCart(Request $request): RedirectResponse
    {
        $indice = $request->input('indice');
        $quantidade = max(0, (int) $request->input('quantidade'));

        $carrinho = session()->get('carrinho', []);
        if (isset($carrinho[$indice])) {
            if ($quantidade === 0) {
                unset($carrinho[$indice]);
            } else {
                $carrinho[$indice]['quantidade'] = $quantidade;
            }
            session()->put('carrinho', $carrinho);
        }

        return back()->with('success', 'Quantidade atualizada.');
    }

    public function show(): array
    {
        $carrinho = session('carrinho', []);
        $subtotal = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);
        $frete = $this->calcularFrete($subtotal);
        $total = $subtotal + $frete;

        return compact('carrinho','frete', 'subtotal', 'total');
    }

    public function calcularFrete($subtotal): float
    {
        if ($subtotal >= 200.00) {
            return 0.00;
        } elseif ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        } else {
            return 20.00;
        }
    }
}
