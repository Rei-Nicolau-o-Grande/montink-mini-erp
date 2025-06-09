<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'preco',
        'ativo'
    ];

    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class);
    }

    public function pedidoProdutos(): HasMany
    {
        return $this->hasMany(PedidoProduto::class);
    }
}
