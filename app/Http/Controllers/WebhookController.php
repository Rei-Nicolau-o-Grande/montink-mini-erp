<?php

namespace App\Http\Controllers;

use App\Enums\StatusPedidos;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function receberStatus(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string',
        ]);

        $pedido = Pedido::find($request->id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado.'], 404);
        }

        if ($pedido->status !== StatusPedidos::AGUARDANDO->name) {
            return response()->json(['message' => 'Pedido já foi Processado.'], 400);
        }

        if (strtolower($request->status) === 'cancelado') {
            $pedido->update([
                'status' => StatusPedidos::CANCELADO->name,
            ]);

            foreach ($pedido->pedidoProdutos as $item) {
                $produto = Produto::find($item->produto_id);
                $estoque = $produto->estoques()
                    ->where('variacao', $item->variacao)
                    ->first();

                if ($estoque) {
                    $estoque->update([
                        'quantidade' => $estoque->quantidade + $item->quantidade,
                    ]);
                }
            }

            return response()->json(['message' => 'Pedido cancelado.']);
        }

        $pedido->status = $request->status;
        $pedido->save();

        return response()->json(['message' => 'Status do pedido atualizado com sucesso.']);
    }
}
