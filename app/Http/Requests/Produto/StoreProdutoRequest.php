<?php

namespace App\Http\Requests\Produto;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255', 'unique:produtos,nome'],
            'preco' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'variacoes' => ['required', 'array', 'min:1'],
            'variacoes.*.variacao' => ['required', 'string', 'max:255'],
            'variacoes.*.quantidade' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'nome.unique' => 'O nome já existe.',
            'preco.required' => 'O preço é obrigatório.',
            'preco.numeric' => 'O preço deve ser um valor numérico.',
            'preco.min' => 'O preço não pode ser negativo.',
            'preco.regex' => 'O preço deve ter no máximo duas casas decimais.',
            'variacoes.required' => 'Pelo menos uma variação é obrigatória.',
            'variacoes.array' => 'As variações devem ser enviadas como uma lista.',
            'variacoes.min' => 'É necessário informar pelo menos uma variação.',
            'variacoes.*.variacao.required' => 'A variação é obrigatória.',
            'variacoes.*.variacao.string' => 'A variação deve ser um texto.',
            'variacoes.*.variacao.max' => 'A variação não pode ter mais de 255 caracteres.',
            'variacoes.*.quantidade.required' => 'A quantidade é obrigatória.',
            'variacoes.*.quantidade.integer' => 'A quantidade deve ser um número inteiro.',
            'variacoes.*.quantidade.min' => 'A quantidade não pode ser negativa.',
        ];
    }
}
