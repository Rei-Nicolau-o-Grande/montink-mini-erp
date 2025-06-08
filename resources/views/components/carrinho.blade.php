<div class="bg-white p-6 rounded-xl shadow-md w-full lg:w-1/3 border border-gray-200 space-y-4">
    <h2 class="text-xl font-bold text-center">🛒 Carrinho</h2>

    <div class="">
        <form action="{{ route('carrinho.cep') }}" method="POST">
            @csrf
            @method('POST')
            <input
                type="text"
                name="cep"
                id="cep"
                placeholder="Digite seu CEP"
                class="input input-bordered w-full"
                autocomplete="off"
                value="{{ old('cep', session('cep')) }}"
            >

            @if(session('cep_mensagem'))
                <div class="text-sm mt-1 {{ session('cep_valido') ? 'text-success' : 'text-error' }}">
                    {{ session('cep_mensagem') }}
                </div>
            @endif

            <div class="mt-2">
                <button class="btn btn-success btn-sm" type="submit">Buscar</button>
            </div>
        </form>

        @if(session('cep_dados'))
            <form action="{{ route('cep.remover') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-error btn-sm">Remover CEP</button>
            </form>
        @endif
    </div>

    <div class="">
        <form action="{{ route('cupom.aplicar') }}" method="POST">
            @csrf
            @method('POST')
            <input
                type="text"
                name="codigo"
                id="codigo-cupom"
                placeholder="Digite um Cupom Válido"
                class="input input-bordered w-full"
                autocomplete="off"
                value="{{ old('codigo', session('cupom_codigo')) }}"
            >

            @if(session('cupom_mensagem'))
                <div class="text-sm mt-1 {{ session('cupom_valido') ? 'text-success' : 'text-error' }}">
                    {{ session('cupom_mensagem') }}
                </div>
            @endif

            <div class="mt-2">
                <button class="btn btn-success btn-sm" type="submit">Aplicar</button>
            </div>
        </form>

        @if(session('cupom_codigo'))
            <form action="{{ route('cupom.remover') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-error btn-sm">Remover Cupom</button>
            </form>
        @endif
    </div>

    @if($endereco)
        <div class="text-sm border-t pt-4 space-y-1">
            <p><strong>Logradouro:</strong> {{ $endereco['logradouro'] ?? '-' }}</p>
            <p><strong>Complemento:</strong> {{ $endereco['complemento'] ?? '-' }}</p>
            <p><strong>Bairro:</strong> {{ $endereco['bairro'] ?? '-' }}</p>
            <p><strong>Localidade:</strong> {{ $endereco['localidade'] ?? '-' }}</p>
            <p><strong>UF:</strong> {{ $endereco['uf'] ?? '-' }}</p>
            <p><strong>Estado:</strong> {{ $endereco['estado'] ?? '-' }}</p>
            <p><strong>Região:</strong> {{ $endereco['regiao'] ?? '-' }}</p>
        </div>
    @endif

    <div class="text-sm border-t pt-4 space-y-1">
        @if($cupom && $desconto)
            <p><strong>Cupom:</strong> {{ $cupom ?? 'Nenhum' }}</p>
            <p><strong>Desconto:</strong> R$ {{ number_format($desconto ?? 0, 2, ',', '.') }}</p>
        @endif

        <p><strong>Frete:</strong> R$ <span id="frete">{{ number_format($frete, 2, ',', '.') }}</span></p>
        <p><strong>SubTotal:</strong> R$ <span id="subtotal">{{ number_format($subtotal, 2, ',', '.') }}</span></p>
        <p><strong>Total:</strong> R$ <span id="total">{{ number_format($total, 2, ',', '.') }}</span></p>
    </div>

    <div class="space-y-4 border-t pt-4 max-h-[400px] overflow-y-auto">
        @forelse ($carrinho as $indice => $item)
            @php
                $totalItem = $item['preco'] * $item['quantidade'];
            @endphp
            <div class="bg-gray-50 rounded-lg p-3 border shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $item['nome'] }}</p>
                        <p class="text-xs text-gray-600">Variação: {{ $item['variacao'] }}</p>
                        <p class="text-xs">Unitário: R$ {{ number_format($item['preco'], 2, ',', '.') }}</p>
                        <p class="text-xs mb-2">Total: <strong>R$ {{ number_format($totalItem, 2, ',', '.') }}</strong></p>
                    </div>

                    <form method="POST" action="{{ route('carrinho.remove') }}" id="form-remover-{{ $indice }}">
                        @csrf
                        <input type="hidden" name="indice" value="{{ $indice }}">
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xl leading-none">❌</button>
                    </form>
                </div>

                <form method="POST" action="{{ route('carrinho.update.quantidade') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="indice" value="{{ $indice }}">
                    <input type="number" name="quantidade" value="{{ $item['quantidade'] }}"
                           min="0" class="input input-sm input-bordered w-20"
                           onchange="if (this.value == 0) {
                               document.getElementById('form-remover-{{ $indice }}').submit();
                           } else {
                               this.form.submit();
                           }">
                </form>
            </div>
        @empty
            <p class="text-center text-sm text-gray-500">Carrinho vazio</p>
        @endforelse
    </div>
</div>
