document.addEventListener('DOMContentLoaded', function () {
    const variacoesList = document.getElementById('variacoes-list');
    const addVariacaoBtn = document.getElementById('add-variacao');
    let variacaoIndex = variacoesList.querySelectorAll('.variacao-item').length;

    addVariacaoBtn.addEventListener('click', function () {
        const newVariacao = document.createElement('div');
        newVariacao.classList.add('flex', 'space-x-2', 'variacao-item', 'items-end');
        newVariacao.innerHTML = `
                <div class="form-control mb-4 flex-1">
                    <label for="variacao-${variacaoIndex}" class="label">
                        <span class="label-text font-bold">Variação</span>
                    </label>
                    <input
                        type="text"
                        id="variacao-${variacaoIndex}"
                        placeholder="Cor, Tipo, Tamanho"
                        name="variacoes[${variacaoIndex}][variacao]"
                        value=""
                        autocomplete="off"
                        class="input input-bordered w-full"
                        required
                    >
                    <p class="text-error mt-1 hidden"></p>
                </div>
                <div class="form-control mb-4 flex-1">
                    <label for="quantidade-${variacaoIndex}" class="label">
                        <span class="label-text font-bold">Quantidade</span>
                    </label>
                    <input
                        type="number"
                        id="quantidade-${variacaoIndex}"
                        placeholder="Quantidade"
                        name="variacoes[${variacaoIndex}][quantidade]"
                        value=""
                        autocomplete="off"
                        class="input input-bordered w-full"
                        required
                    >
                    <p class="text-error mt-1 hidden"></p>
                </div>
                <button type="button" class="btn btn-error remove-variacao mb-4">Remover</button>
            `;
        variacoesList.appendChild(newVariacao);
        variacaoIndex++;
    });

    variacoesList.addEventListener('click', function (event) {
        const target = event.target;
        if (target.classList.contains('remove-variacao')) {
            const variacaoItem = target.closest('.variacao-item');
            if (variacaoItem) {
                variacaoItem.remove();
                updateVariacaoIndexes();
            }
        }
    });

    function updateVariacaoIndexes() {
        const variacaoItems = variacoesList.querySelectorAll('.variacao-item');
        variacaoItems.forEach((item, index) => {
            const inputs = item.querySelectorAll('input');
            const labels = item.querySelectorAll('label');
            if (inputs[0]) {
                inputs[0].name = `variacoes[${index}][variacao]`;
                inputs[0].id = `variacao-${index}`;
                labels[0].setAttribute('for', `variacao-${index}`);
            }
            if (inputs[1]) {
                inputs[1].name = `variacoes[${index}][quantidade]`;
                inputs[1].id = `quantidade-${index}`;
                labels[1].setAttribute('for', `quantidade-${index}`);
            }
        });
        variacaoIndex = variacaoItems.length;

        if (variacaoIndex === 0) {
            addVariacaoBtn.click();
        }
    }

    document.querySelector('form').addEventListener('submit', function (event) {
        console.log(new FormData(this));
    });
});
