<?php

namespace App\Enums;

enum ChannelIssues: string
{
    case CORRECT = 'CORRECT';
    case PAUSE = 'PAUSE';
    case LONG_LOAD_TIME = 'LONG LOAD TIME';
    case DO_NOT_SHOW_CONTENT = 'I NOT SHOW CONTENT';
    case ISSUE = 'ISSUE';
}
