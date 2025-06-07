<div class="w-full lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @if($produtos->isEmpty())
        <div class="col-span-full">
            <h2 class="text-center font-bold text-xl text-gray-600">
                Não há produtos cadastrados.
            </h2>
        </div>
    @else
        @foreach($produtos as $produto)
            <div class="card bg-white shadow-xl rounded-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg font-bold">{{ $produto->nome }}</h2>
                    <p class="text-gray-700">Preço: <strong>R$ {{ number_format($produto->preco, 2, ',', '.') }}</strong></p>
                    <div class="card-actions justify-end mt-4">
                        <button class="btn btn-primary btn-sm" onclick="document.getElementById('modal-{{ $produto->id }}').showModal()">
                            Ver Variações
                        </button>
                    </div>
                </div>
            </div>

            <dialog id="modal-{{ $produto->id }}" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Variações de Estoque - {{ $produto->nome }}</h3>
                    <form method="POST" action="{{ route('carrinho.add') }}">
                        @csrf
                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                        <ul class="py-4 text-left space-y-2">
                            @foreach($produto->estoques as $estoque)
                                <li class="flex items-center gap-2">
                                    <input type="radio" name="estoque_id" value="{{ $estoque->id }}" required class="radio" id="estoque-{{ $estoque->id }}">
                                    <label for="estoque-{{ $estoque->id }}">
                                        {{ $estoque->variacao }} ({{ $estoque->quantidade }} disponíveis)
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-success">Comprar</button>
                            <button type="button" class="btn btn-error" onclick="document.getElementById('modal-{{ $produto->id }}').close()">Fechar</button>
                        </div>
                    </form>
                </div>
            </dialog>
        @endforeach
    @endif
</div>
