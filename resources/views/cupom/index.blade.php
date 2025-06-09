@extends('app')

@section('title', 'Cupons')

@section('content')
    <x-alert />
    <h1 class="text-2xl text-center mt-10 font-bold">Listagem de Cupons</h1>

    <div class="flex justify-end px-10 mt-6 gap-2">
        <a class="btn btn-primary" href="{{ route('produto.index') }}">Voltar para Produto</a>
        <a class="btn btn-success" href="{{ route('cupons.create') }}">Criar Cupom</a>
    </div>

    <div class="px-4 mt-6 max-w-7xl mx-auto">
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content">
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th>Desconto (%)</th>
                    <th>Valor Mínimo</th>
                    <th>Validade</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cupons as $cupom)
                    <tr class="hover:bg-gray-100">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cupom->codigo }}</td>
                        <td>{{ $cupom->desconto_percentual }}%</td>
                        <td>R$ {{ number_format($cupom->valor_minimo, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cupom->validade)->format('d/m/Y') }}</td>
                        <td>
                            @if ($cupom->ativo)
                                <span class="badge badge-success">Sim</span>
                            @else
                                <span class="badge badge-error">Não</span>
                            @endif
                        </td>
                        <td>
                            <x-action-button-group
                                :model="$cupom"
                                :routeEditar="'cupons.edit'"
                                :routeExcluir="'cupons.delete'"
                                :routeAtivar="'cupons.active'"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-lg text-black">
                            Nenhum Cupom encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
