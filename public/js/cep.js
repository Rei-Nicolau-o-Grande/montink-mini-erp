document.addEventListener('DOMContentLoaded', function () {
    const cepInput = document.getElementById('cep');
    const enderecoDiv = document.getElementById('endereco');
    let timeout = null;

    cepInput.addEventListener('input', function () {
        const cep = this.value.replace(/\D/g, '');
        enderecoDiv.innerText = '';

        if (cep.length === 8) {
            clearTimeout(timeout);
            timeout = setTimeout(async () => {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    if (data.erro) {
                        enderecoDiv.innerHTML = `<p class="text-error mt-2">CEP não encontrado.</>`;
                    } else {
                        enderecoDiv.innerHTML = `<p class="text-success mt-2">CEP encontrado.</>`
                    }
                } catch {
                    enderecoDiv.innerText = 'Erro ao buscar o CEP.';
                }
            }, 100);
        }
    });
});
