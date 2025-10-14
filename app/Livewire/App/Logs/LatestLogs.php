<?php

namespace App\Livewire\App\Logs;

use Livewire\Component;
use App\Models\Issue;
use App\Models\Channel;

class LatestLogs extends Component
{
    public $logs = [];

    public function mount()
    {
        $this->fetchLogs();
    }

    public function fetchLogs()
    {
        $issues = Issue::orderByDesc('created_at')->get()->reverse();
        $this->logs = $issues->map(function ($issue) {
            $date = $issue->created_at ? $issue->created_at->format('d/m/Y H:i:s') : '';
            $channelNumber = null;
            $originalChannel = $issue->channel;
            $channelName = $originalChannel;
            if (preg_match('/^(\d+)/', $originalChannel, $matches)) {
                $channelNumber = $matches[1];
            }
            $channelImage = null;
            if ($channelNumber) {
                $channel = Channel::where('number', $channelNumber)->first();
                if ($channel) {
                    if (!empty($channel->image_url)) {
                        $channelImage = $channel->image;
                    }
                    if (!empty($channel->name)) {
                        $channelName = $channel->name;
                    }
                }
            }

            return [
                'date' => $date,
                'channel' => $channelName,
                'channel_number' => $channelNumber,
                'type' => $issue->issueType,
                'description' => $issue->issueDescription,
                'tag' => $issue->tag,
                'channel_image' => $channelImage,
            ];
        })->values()->toArray();
    }

    public function render()
    {
        return view('livewire.app.logs.latest-logs', [
            'logs' => $this->logs,
        ]);
    }
}
