<?php

namespace App\Enums;

enum ChannelIssues: string
{
    case CORRECTO = 'CORRECTO';
    case PAUSADO = 'PAUSADO';
    case TIEMPO_CARGA_LARGO = 'TIEMPO CARGA LARGO';
    case NO_MOSTRAR_CONTENIDO = 'NO MOSTRAR CONTENIDO';
    case PROBLEMA = 'PROBLEMA';
}
