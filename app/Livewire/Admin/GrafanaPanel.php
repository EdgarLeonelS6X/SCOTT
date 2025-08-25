<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Channel;
use Carbon\Carbon;

class GrafanaPanel extends Component
{
    public $channels = [];
    public $selectedChannel = null;

    public $mode = 'relative';

    public $preset = '1h';

    public $absoluteFrom = null;
    public $absoluteTo   = null;

    public function mount()
    {
        $this->channels = Channel::orderBy('number', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.admin.grafana-panel');
    }

    public function getGrafanaUrlProperty()
    {
        $base = "https://172.16.100.177:3000/d-solo/0ce5d82e-9619-4da7-8301-e1c118fb4c14/multicast-monitor";

        [$from, $to] = $this->resolveTimeParams();

        $params = [
            "orgId"     => 1,
            "timezone"  => "browser",
            "refresh"   => "5s",
            "theme"     => "dark",
            "panelId"   => 1,
            "from"      => $from,
            "to"        => $to,
            "__feature.dashboardSceneSolo" => "true",
        ];

        if ($this->selectedChannel) {
            $params["var-canal"] = $this->selectedChannel;
        }

        $params["_k"] = substr(md5(json_encode([$from, $to, $this->selectedChannel])), 0, 10);

        return $base . "?" . http_build_query($params);
    }

    public function getIframeKeyProperty()
    {
        return 'grafana-' . substr(md5($this->grafanaUrl), 0, 12);
    }

    public function updatedPreset()
    {
        $this->mode = 'relative';
    }

    protected function resolveTimeParams(): array
    {
        if ($this->mode === 'relative') {
            return ["now-{$this->preset}", "now"];
        }

        $fromMs = $this->toMillis($this->absoluteFrom) ?? now()->subHour()->getTimestampMs();
        $toMs   = $this->toMillis($this->absoluteTo)   ?? now()->getTimestampMs();

        return [$fromMs, $toMs];
    }

    protected function toMillis(?string $dt): ?int
    {
        if (!$dt) return null;
        try {
            return Carbon::parse($dt)->getTimestampMs();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
