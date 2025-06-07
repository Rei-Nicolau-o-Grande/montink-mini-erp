<div class="bg-white p-6 rounded-xl shadow-md w-full lg:w-1/3 border border-gray-200 space-y-4">
    <h2 class="text-xl font-bold text-center">🛒 Carrinho</h2>
    <div>
        <input type="text" id="cep" placeholder="Digite o CEP" class="input input-bordered w-full" autocomplete="off">
        <div id="endereco" class="text-sm text-gray-600 mt-1"></div>
    </div>

    <div class="text-sm border-t pt-4 space-y-1">
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

<script src="{{ asset('js/cep.js') }}"></script>
