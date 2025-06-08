@extends('app')

@section('title', isset($cupom) ? '- Editando ' . $cupom->codigo : '- Criando Cuopm')

@section('content')
    <h1 class="text-4xl text-center mt-4">{{ isset($cupom) ? 'Editando ' . $cupom->codigo : 'Criando Cupom'  }}</h1>
    <x-alert />
    <x-form-model
        action="{{ isset($cupom) ? route('cupons.update', $cupom) : route('cupons.store') }}"
        method="POST"
    >
        @csrf
        @if(isset($cupom))
            @method('PUT')
        @else
            @method('POST')
        @endif
        @include('cupom.partials.form-cupom')
    </x-form-model>
@endsection
