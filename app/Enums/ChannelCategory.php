<?php

namespace App\Enums;

enum ChannelCategory: string
{
    case STANDARD = 'Standard TV Channel';
    case STINGRAY = 'Stingray Music';
    case FAST = "FAST";
    case RESTART_CUTV = 'RESTART/CUTV';
}
