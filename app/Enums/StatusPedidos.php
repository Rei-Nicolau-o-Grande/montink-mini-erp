<?php

namespace App\Enums;

enum StatusPedidos: string
{
    case ENVIADO = 'ENVIADO';
    case AGUARDANDO = 'AGUARDANDO';
    case CANCELADO = 'CANCELADO';
}
