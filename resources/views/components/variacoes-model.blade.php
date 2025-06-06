@props(['produto' => null])

<div class="space-y-2" id="variacoes-list">
    @if($produto && $produto->estoques->isNotEmpty())
        @foreach($produto->estoques as $index => $estoque)
            <div class="flex space-x-2 variacao-item items-end">
                <x-input-model
                    :label="'Variação'"
                    :type="'text'"
                    :id="'variacao-' . $index"
                    :name="'variacoes[' . $index .'][variacao]'"
                    :placeholder="'Cor, Tipo, Tamanho'"
                    :value="old('variacoes.' . $index . '.variacao', $estoque->variacao ?? '')"
                    :autocomplete="'off'"
                    :required="true"
                    :error="$errors->get('variacoes.' . $index . '.variacao')"
                />
                <x-input-model
                    :label="'Quantidade'"
                    :type="'number'"
                    :id="'quantidade-' . $index"
                    :name="'variacoes[' . $index .'][quantidade]'"
                    :placeholder="'Quantidade'"
                    :value="old('variacoes.' . $index . '.quantidade', $estoque->quantidade ?? '')"
                    :autocomplete="'off'"
                    :required="true"
                    :error="$errors->get('variacoes.' . $index . '.quantidade')"
                />
                <button type="button" class="btn btn-error remove-variacao mb-4">Remover</button>
            </div>
        @endforeach
    @else
        <div class="flex space-x-2 variacao-item items-end">
            <x-input-model
                :label="'Variação'"
                :type="'text'"
                :id="'variacao-0'"
                :name="'variacoes[0][variacao]'"
                :placeholder="'Cor, Tipo, Tamanho'"
                :value="old('variacoes.0.variacao')"
                :autocomplete="'off'"
                :required="true"
                :error="$errors->get('variacoes.0.variacao')"
            />
            <x-input-model
                :label="'Quantidade'"
                :type="'number'"
                :id="'quantidade-0'"
                :name="'variacoes[0][quantidade]'"
                :placeholder="'Quantidade'"
                :value="old('variacoes.0.quantidade')"
                :autocomplete="'off'"
                :required="true"
                :error="$errors->get('variacoes.0.quantidade')"
            />
            <button type="button" class="btn btn-error remove-variacao mb-4">Remover</button>
        </div>
    @endif
</div>
<button type="button" class="btn btn-primary" id="add-variacao">Adicionar Variação</button>
