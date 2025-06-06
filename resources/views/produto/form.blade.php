@extends('app')

@section('title', isset($produto) ? '- Editando ' . $produto->nome : '- Criando Produto')

@section('content')
    <h1 class="text-4xl text-center mt-4">{{ isset($produto) ? 'Editando ' . $produto->nome : 'Criando Produto'  }}</h1>
    <x-alert />
    <x-form-model
        action="{{ isset($produto) ? route('produto.update', $produto) : route('produto.store') }}"
        method="POST"
    >
        @csrf
        @if(isset($produto))
            @method('PUT')
        @else
            @method('POST')
        @endif
        @include('produto.partials.form-produto')
    </x-form-model>
@endsection
