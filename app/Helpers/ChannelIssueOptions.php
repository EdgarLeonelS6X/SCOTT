<?php

namespace App\Helpers;

use App\Enums\ChannelIssues;

class ChannelIssueOptions
{
    public static function all(): array
    {
        return [
            ChannelIssues::CORRECT->value => [
                'label' => __('Correct'),
                'color' => 'emerald',
                'dark' => 'emerald',
            ],
            ChannelIssues::LIGHT_PAUSES_AUDIO->value => [
                'label' => __('Light pauses in AUDIO'),
                'color' => 'amber',
                'dark' => 'amber',
            ],
            ChannelIssues::LIGHT_PAUSES_VIDEO->value => [
                'label' => __('Light pauses in VIDEO'),
                'color' => 'amber',
                'dark' => 'amber',
            ],
            ChannelIssues::LIGHT_PAUSES_AV->value => [
                'label' => __('Light pauses in A/V'),
                'color' => 'amber',
                'dark' => 'amber',
            ],
            ChannelIssues::CONSTANT_PAUSES_AUDIO->value => [
                'label' => __('Constant pauses in AUDIO'),
                'color' => 'yellow',
                'dark' => 'yellow',
            ],
            ChannelIssues::CONSTANT_PAUSES_VIDEO->value => [
                'label' => __('Constant pauses in VIDEO'),
                'color' => 'yellow',
                'dark' => 'yellow',
            ],
            ChannelIssues::CONSTANT_PAUSES_AV->value => [
                'label' => __('Constant pauses in A/V'),
                'color' => 'yellow',
                'dark' => 'yellow',
            ],
            ChannelIssues::SHORT_LOADING_TIME->value => [
                'label' => __('Short loading time'),
                'color' => 'sky',
                'dark' => 'sky',
            ],
            ChannelIssues::LONG_LOADING_TIME->value => [
                'label' => __('Long loading time'),
                'color' => 'sky',
                'dark' => 'sky',
            ],
            ChannelIssues::NO_SHOW_CONTENT->value => [
                'label' => __('No content shown'),
                'color' => 'rose',
                'dark' => 'rose',
            ],
        ];
    }
}
