@extends('app')

@section('title', 'Produtos')

@section('content')
    <x-alert />
    <h1 class="text-2xl text-center mt-10 font-bold">Listagem de Produtos</h1>

    <div class="flex justify-end px-10 mt-6 gap-1">
        <a class="btn btn-primary" href="{{ route('cupons.index') }}">Ver Cupons</a>
        <a class="btn btn-success" href="{{ route('produto.create') }}">Criar Produto</a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 p-10 items-start">

        <x-carrinho
            :carrinho="$carrinho"
            :frete="$frete"
            :subtotal="$subtotal"
            :total="$total"
        />

        <x-produto-card :produtos="$produtos" />
    </div>

@endsection

