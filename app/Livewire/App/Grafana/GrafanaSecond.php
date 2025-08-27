<?php

namespace App\Livewire\App\Grafana;

use Livewire\Component;
use App\Models\Channel;
use Carbon\Carbon;

class GrafanaSecond extends Component
{
    public $channels = [];
    public $selectedChannel = null;
    public $mode = 'relative';
    public $preset = '1h';
    public $absoluteFrom = null;
    public $absoluteTo   = null;
    public $theme = 'dark';
    public $iframeRefreshKey = 0;

    public function mount()
    {
        $this->channels = Channel::orderBy('number', 'asc')
            ->where('category', 'RESTART/CUTV')
            ->get();
    }

    #[On('setTheme')]
    public function setTheme($theme)
    {
        $this->theme = $theme;
        $this->iframeRefreshKey++;
    }

    public function render()
    {
        return view('livewire.app.grafana.grafana-second');
    }

    public function getGrafanaUrlProperty()
    {
        $base = "https://172.16.100.177:3000/d-solo/0ce5d82e-9619-4da7-8301-e1c118fb4c14/multicast-monitor";

        [$from, $to] = $this->resolveTimeParams();

        $params = [
            "orgId"     => 1,
            "timezone"  => "browser",
            "refresh"   => "5s",
            "theme"     => $this->theme,
            "panelId"   => 2,
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
        return 'grafana-' . substr(md5($this->grafanaUrl), 0, 12) . '-' . $this->iframeRefreshKey;
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
