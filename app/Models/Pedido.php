<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'valor_total',
        'frete',
        'status',
        'email_cliente',
        'cep',
        'logradouro',
        'complemento',
        'bairro',
        'localidade',
        'uf',
        'estado',
        'regiao',
        'ativo'
    ];

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }
}
