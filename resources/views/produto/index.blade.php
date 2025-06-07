@extends('app')

@section('title', 'Produtos')

@section('content')
    <x-alert />
    <h1 class="text-2xl text-center mt-10 font-bold">Listagem de Produtos</h1>

    <div class="flex justify-end px-10 mt-6">
        <a class="btn btn-success" href="{{ route('produto.create') }}">Criar Produto</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-10">
        {{-- Carrinho --}}
        <div class="bg-gray-100 p-6 rounded-xl shadow-md col-span-1">
            <h2 class="text-xl font-bold text-center mb-4">Carrinho</h2>
            <p><strong>Frete:</strong> R$ 0,00</p>
            <p><strong>SubTotal:</strong> R$ 0,00</p>
            <p><strong>Total:</strong> R$ 0,00</p>
        </div>

        {{-- Lista de Produtos --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 col-span-3">
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

                    {{-- Modal de Variações --}}
                    <dialog id="modal-{{ $produto->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="text-lg font-bold">Variações de Estoque - {{ $produto->nome }}</h3>
                            <form method="POST">
                                @csrf
                                <ul class="py-4 text-left space-y-2">
                                    @forelse($produto->estoques as $estoque)
                                        <li class="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                name="estoque_id"
                                                class="radio"
                                                value="{{ $estoque->id }}"
                                                id="estoque-{{ $estoque->id }}"
                                                required
                                            >
                                            <label for="estoque-{{ $estoque->id }}">
                                                {{ $estoque->variacao }} ({{ $estoque->quantidade }} disponíveis)
                                            </label>
                                        </li>
                                    @empty
                                        <li>Sem variações cadastradas.</li>
                                    @endforelse
                                </ul>
                                <div class="modal-action">
                                    <button type="submit" class="btn btn-success">Adicionar ao carrinho</button>
                                    <button type="button" class="btn btn-error" onclick="document.getElementById('modal-{{ $produto->id }}').close()">Fechar</button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endforeach
            @endif
        </div>
    </div>
@endsection
