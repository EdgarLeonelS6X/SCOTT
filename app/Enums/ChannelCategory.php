<?php

namespace App\Enums;

enum ChannelCategory: string

{
    case NEWS = 'News';
    case SPORTS = 'Sports';
    case MOVIES = 'Movies';
    case MUSIC = 'Music';
    case KIDS = 'Kids';
    case DOCUMENTARY = 'Documentary';
    case EDUCATION = 'Education';
    case ENTERTAINMENT = 'Entertainment';
    case RELIGION = 'Religion';
    case LIFESTYLE = 'Lifestyle';
}