<x-input-model
    :label="'Nome'"
    :type="'text'"
    :id="'nome'"
    :name="'nome'"
    :placeholder="'Nome'"
    :value="old('nome', $produto->nome ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('nome')"
/>

<x-input-model
    :label="'Preço'"
    :type="'text'"
    :id="'preco'"
    :name="'preco'"
    :placeholder="'Preço'"
    :value="old('nome', $produto->preco ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('preco')"
/>

<x-variacoes-model :produto="$produto" />

<x-save-back-buttons :model="$produto" :routeBack="'produto.index'" />

<script src="{{ asset('js/form-produto.js') }}"></script>
