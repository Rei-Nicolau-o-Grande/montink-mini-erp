<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupons extends Model
{
    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'desconto_percentual',
        'valor_minimo',
        'validade',
        'ativo',
    ];
}
