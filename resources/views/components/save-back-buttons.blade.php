<div class="flex justify-between items-center flex-col md:flex-row gap-4">
    @if ( request()->routeIs('*create*') )
        <button type="submit" class="btn btn-success text-black w-full md:w-auto">Salvar</button>
    @elseif( request()->routeIs('*edit*') )
        <button type="submit" class="btn btn-info text-black w-full md:w-auto">Editar</button>
    @endif
    <a href="{{ $cancelRoute }}" class="btn btn-error text-black w-full md:w-auto">Voltar</a>
</div>
