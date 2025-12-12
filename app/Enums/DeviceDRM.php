<?php

namespace App\Enums;

enum DeviceDRM: string
{
    case Verimatrix = 'Verimatrix';
    case Widevine = 'Widevine';
}
