@props([
    'model' => null,
    'routeBack' => null
])

<div class="flex justify-between items-center flex-col md:flex-row gap-4">
    @if ($model)
        <button type="submit" class="btn btn-info text-black w-full md:w-auto">Editar</button>
    @else
        <button type="submit" class="btn btn-success text-black w-full md:w-auto">Salvar</button>
    @endif
    <a href="{{ route($routeBack) }}" class="btn btn-error text-black w-full md:w-auto">Voltar</a>
</div>
