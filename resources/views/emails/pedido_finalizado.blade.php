<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
    <h1>Pedido Recebido</h1>
    <p>Olá! Seu pedido foi finalizado com sucesso.</p>
    <p><strong>Total:</strong> R$ {{ number_format($pedido['valor_total'], 2, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ $pedido['status'] }}</p>
    <hr>
    <h2>Itens:</h2>
    <ul>
        @foreach ($itens as $item)
            <li>{{ $item['nome'] }} - {{ $item['variacao'] }} - {{ $item['quantidade'] }}x R$ {{ number_format($item['preco'], 2, ',', '.') }}</li>
        @endforeach
    </ul>
</body>
</html>
