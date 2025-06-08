<?php

namespace App\Http\Requests\Cupom;

use Illuminate\Foundation\Http\FormRequest;

class StoreCupomRequest extends FormRequest
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
            'codigo' => ['required', 'string', 'max:255', 'unique:cupons,codigo'],
            'desconto_percentual'  => ['required', 'numeric',  'min:0', 'max:100'],
            'valor_minimo' => ['required', 'numeric', 'min:0'],
            'validade' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'O campo código é obrigatório.',
            'codigo.string' => 'O código deve ser um texto válido.',
            'codigo.max' => 'O código não pode ter mais que 255 caracteres.',
            'codigo.unique' => 'Este código já está em uso.',

            'desconto_percentual.required' => 'O campo desconto percentual é obrigatório.',
            'desconto_percentual.numeric' => 'O desconto percentual deve ser um número.',
            'desconto_percentual.min' => 'O desconto percentual não pode ser menor que 0.',
            'desconto_percentual.max' => 'O desconto percentual não pode ser maior que 100.',

            'valor_minimo.required' => 'O campo valor mínimo é obrigatório.',
            'valor_minimo.numeric' => 'O valor mínimo deve ser um número.',
            'valor_minimo.min' => 'O valor mínimo deve ser no mínimo 0.',

            'validade.required' => 'O campo validade é obrigatório.',
            'validade.date' => 'A validade deve ser uma data válida.',
            'validade.after_or_equal' => 'A validade deve ser hoje ou uma data futura.',
        ];
    }
}
