<?php

namespace App\Http\Controllers;

use App\Enums\StatusPedidos;
use App\Mail\EnviarPedido;
use App\Models\Cupom;
use App\Models\Estoque;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
            return back()->with('error', 'Estoque esgotado!');
        }

        $carrinho = session()->get('carrinho', []);
        $chave = $estoque->id;

        $carrinho[$chave] = isset($carrinho[$chave])
            ? array_merge($carrinho[$chave], ['quantidade' => $carrinho[$chave]['quantidade'] + 1])
            : [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'variacao' => $estoque->variacao,
                'quantidade' => 1,
                'preco' => $produto->preco,
            ];

        session()->put('carrinho', $carrinho);

        return back()->with('success', 'Produto adicionado ao carrinho!');
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
        $subtotal = $this->calcularSubtotal($carrinho);
        $frete = $this->calcularFrete($subtotal);

        [$desconto, $cupom] = $this->obterCupomValidado($subtotal);
        $total = $subtotal + $frete - $desconto;

        $endereco = $this->obterEnderecoSessao();

        return compact('carrinho', 'frete', 'subtotal', 'total', 'desconto', 'cupom', 'endereco');
    }

    public function aplicarCupom(Request $request): RedirectResponse
    {
        $codigo = $request->input('codigo');
        if (!$codigo) {
            return back()->with('cupom_mensagem', 'Digite um cupom!');
        }

        $subtotal = $this->calcularSubtotal(session('carrinho', []));
        $validacao = $this->validateCupom($codigo, $subtotal);

        if (!$validacao['valido']) {
            session()->forget('cupom_codigo');
            return back()
                ->with('cupom_valido', false)
                ->with('cupom_mensagem', $validacao['mensagem']);
        }

        session()->put('cupom_codigo', $codigo);
        return back()
            ->with('cupom_valido', true)
            ->with('cupom_mensagem', 'Cupom aplicado com sucesso!');
    }

    public function removerCupom(): RedirectResponse
    {
        session()->forget(['cupom_codigo', 'cupom_valido', 'cupom_mensagem']);
        return back()->with('success', 'Cupom removido com sucesso.');
    }

    public function buscarCep(Request $request): RedirectResponse
    {
        $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));

        if (strlen($cep) !== 8) {
            return back()->with('cep_valido', false)->with('cep_mensagem', 'CEP inválido.');
        }

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed() || $response->json('erro')) {
            return back()->with('cep_valido', false)->with('cep_mensagem', 'CEP não encontrado.');
        }

        session()->put('cep', $cep);
        session()->put('cep_dados', $response->json());

        return back()->with('cep_valido', true)->with('cep_mensagem', 'CEP encontrado com sucesso!');
    }

    public function removerCep(): RedirectResponse
    {
        session()->forget(['cep', 'cep_valido', 'cep_mensagem', 'cep_dados']);
        return back()->with('success', 'CEP removido com sucesso.');
    }

    public function finalizarPedido(Request $request): RedirectResponse
    {
        $carrinho =  $this->show()['carrinho'];
        $subtotal = $this->calcularSubtotal($carrinho);
        $frete = $this->calcularFrete($subtotal);

        [$desconto, $cupom] = $this->obterCupomValidado($subtotal);
        $total = $subtotal + $frete - $desconto;

        $endereco = $this->obterEnderecoSessao();

        if (session()->get('cep') == null) {
            return back()->with('cep_mensagem', 'Cep não pode ficar em branco.');
        }

        if (count($carrinho) <= 0) {
            return back()->with('error', 'O carrinho estar vazio.');
        }

        if ($request->input('email_cliente') == null) {
            return back()->with('email_cliente_mensagem', 'O email não pode ficar em branco.');
        }

        $pedido = Pedido::create([
            'valor_total' => $total,
            'frete' => $frete,
            'status' => StatusPedidos::AGUARDANDO->name,
            'email_cliente' => $request->input('email_cliente'),
            'cupom' => $cupom,
            'cep' => $endereco['cep'],
            'logradouro' => $endereco['logradouro'],
            'complemento' => $endereco['complemento'] ?? '',
            'bairro' => $endereco['bairro'],
            'localidade' => $endereco['localidade'],
            'uf' => $endereco['uf'],
            'estado' => $endereco['estado'] ?? '',
            'regiao' => $endereco['regiao'] ?? '',
            'ativo' => true,
        ]);

        foreach ($carrinho as $item) {
            PedidoProduto::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $item['produto_id'],
                'variacao' => $item['variacao'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
                'subtotal' => $item['preco'] * $item['quantidade'],
            ]);
        }

        foreach ($carrinho as $item) {
            $produto = Produto::find($item['produto_id']);
            $estoque = $produto->estoques()
                ->where('variacao', $item['variacao'])
                ->first();

            if (!$estoque || $estoque->quantidade < $item['quantidade']) {
                return back()->with('error', "Produto '{$produto->nome}' com variação '{$item['variacao']}' sem estoque suficiente.");
            }

            $estoque->update([
                'quantidade' => $estoque->quantidade - $item['quantidade'],
            ]);
        }

        Mail::to($pedido->email_cliente)->send(new EnviarPedido($pedido->toArray(), $carrinho));

        session()->forget(['carrinho', 'cep_dados', 'cupom_codigo', 'cep']);

        return redirect()->route('produto.index')->with('success', 'Pedido finalizado com sucesso.');
    }

    private function calcularSubtotal(array $carrinho): float
    {
        return collect($carrinho)->sum(fn($item) => $item['preco'] * $item['quantidade']);
    }

    private function obterCupomValidado(float $subtotal): array
    {
        $codigo = session('cupom_codigo');
        if (!$codigo) {
            return [0, null];
        }

        $validacao = $this->validateCupom($codigo, $subtotal);

        if ($validacao['valido']) {
            return [$validacao['desconto'], $validacao['cupom']];
        }

        session()->forget('cupom_codigo');
        return [0, null];
    }

    private function obterEnderecoSessao(): ?array
    {
        return session('cep_dados', []);
    }

    public function validateCupom(string $codigo, float $subtotal): array
    {
        $cupom = Cupom::where('codigo', $codigo)->first();

        if (!$cupom) {
            return ['valido' => false, 'mensagem' => 'Cupom não encontrado.'];
        }

        if (!$cupom->ativo || now()->gt($cupom->validade)) {
            return ['valido' => false, 'mensagem' => 'Cupom expirado ou inativo.'];
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
        return match (true) {
            $subtotal >= 200.00 => 0.00,
            $subtotal >= 52.00 && $subtotal <= 166.59 => 15.00,
            default => 20.00,
        };
    }
}
