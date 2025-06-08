<x-input-model
    :label="'Codigo'"
    :type="'text'"
    :id="'codigo'"
    :name="'codigo'"
    :placeholder="'Codigo'"
    :value="old('codigo', $cupom->codigo ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('codigo')"
/>

<x-input-model
    :label="'Desconto percentual'"
    :type="'text'"
    :id="'desconto_percentual'"
    :name="'desconto_percentual'"
    :placeholder="'Desconto percentual'"
    :value="old('desconto_percentual', $cupom->desconto_percentual ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('desconto_percentual')"
/>

<x-input-model
    :label="'Valor Minimo'"
    :type="'text'"
    :id="'valor_minimo'"
    :name="'valor_minimo'"
    :placeholder="'Valor Minimo'"
    :value="old('valor_minimo', $cupom->valor_minimo ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('valor_minimo')"
/>

<x-input-model
    :label="'Validade'"
    :type="'date'"
    :id="'validade'"
    :name="'validade'"
    :placeholder="'Validade'"
    :value="old('validade', $cupom->validade ?? '')"
    :autocomplete="'off'"
    :required="true"
    :error="$errors->get('validade')"
/>

<x-save-back-buttons :model="$cupom" :routeBack="'cupons.index'" />
