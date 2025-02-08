<?php

namespace App\Enums;

enum ChannelReviewer: string
{
    case ORIGIN = 'ORIGIN';
    case DTH = 'DTH';
    case OVERON = 'OVERON';
    case ENGINEERS = 'ENGINEERS';
}
