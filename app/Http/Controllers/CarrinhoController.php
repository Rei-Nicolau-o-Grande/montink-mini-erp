<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $cupom = session('cupom_codigo');
        $desconto = 0;

        if ($cupom) {
            $validacao = $this->validateCupom($cupom, $subtotal);

            if (!empty($validacao['valido']) && $validacao['valido'] === true) {
                $desconto = $validacao['desconto'];
                $total -= $desconto;
            } else {
                session()->forget('cupom_codigo');
            }
        }

        $endereco = session('cep_dados');

        return compact('carrinho', 'frete', 'subtotal', 'total', 'desconto', 'cupom', 'endereco');
    }

    public function aplicarCupom(Request $request): RedirectResponse
    {
        $codigo = $request->input('codigo');
        if ($codigo == null || $codigo == '') {
            return back()->with('cupom_mensagem', 'Digite um cupom!');
        }

        $carrinho = session('carrinho', []);
        $subtotal = collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);

        $validacao = $this->validateCupom($codigo, $subtotal);

        if (!$validacao['valido']) {
            session()->flash('cupom_valido', false);
            session()->flash('cupom_mensagem', $validacao['mensagem']);
            session()->forget('cupom_codigo');
        } else {
            session()->put('cupom_codigo', $codigo);
            session()->flash('cupom_valido', true);
            session()->flash('cupom_mensagem', 'Cupom aplicado com sucesso!');
        }

        return redirect()->back();
    }



    public function validateCupom(string $codigo, float $subtotal): array
    {
        $cupom = Cupom::where('codigo', $codigo)->first();

        if (!$cupom) {
            return ['valido' => false, 'mensagem' => 'Cupom não encontrado.'];
        }

        if (!$cupom->ativo) {
            return ['valido' => false, 'mensagem' => 'Cupom inativo.'];
        }

        if (now()->gt($cupom->validade)) {
            return ['valido' => false, 'mensagem' => 'Cupom expirado.'];
        }

        if ($subtotal < $cupom->valor_minimo) {
            return ['valido' => false, 'mensagem' => "Valor mínimo para o cupom é R$ {$cupom->valor_minimo}."];
        }

        $desconto = $subtotal * ($cupom->desconto_percentual / 100);

        return [
            'valido' => true,
            'desconto' => $desconto,
            'cupom' => $cupom->codigo,
        ];
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

    function buscarCep(Request $request): RedirectResponse
    {
        $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));

        if (strlen($cep) !== 8) {
            session()->flash('cep_valido', false);
            session()->flash('cep_mensagem', 'CEP inválido. Deve conter 8 dígitos.');
            return redirect()->back();
        }

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed() || $response->json('erro')) {
            session()->flash('cep_valido', false);
            session()->flash('cep_mensagem', 'CEP não encontrado.');
            return redirect()->back();
        }

        $dados = $response->json();

        session()->put('cep', $cep);
        session()->put('cep_dados', $dados);
        session()->flash('cep_valido', true);
        session()->flash('cep_mensagem', 'CEP encontrado com sucesso!');

        return redirect()->back();

    }

    public function removerCupom(): RedirectResponse
    {
        session()->forget('cupom_codigo');
        session()->forget('cupom_validado');
        session()->forget('cupom_mensagem');

        return redirect()->back()->with('success', 'Cupom removido com sucesso.');
    }

    public function removerCep(): RedirectResponse
    {
        session()->forget('cep');
        session()->forget('cep_valido');
        session()->forget('cep_mensagem');
        session()->forget('cep_dados');

        return redirect()->back()->with('success', 'CEP removido com sucesso.');
    }
}
