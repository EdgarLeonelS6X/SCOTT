<?php

namespace App\Livewire\App\Grafana;

use Livewire\Component;
use App\Models\Channel;
use Carbon\Carbon;

class GrafanaFirst extends Component
{
    public $channels = [];
    public $selectedChannel = null;
    public $mode = 'relative';
    public $preset = '1h';
    public $absoluteFrom = null;
    public $absoluteTo   = null;
    public $theme = 'dark';
    public $iframeRefreshKey = 0;
    public $channelPanelIds = [];
    public $channelMulticasts = [];

    public function mount()
    {
        $apiUrl = 'http://172.16.100.38:5000/channels';
        $numbers = [];
        $panelIds = [];
        $multicasts = [];
        try {
            $response = @file_get_contents($apiUrl);
            if ($response !== false) {
                $json = json_decode($response, true);
                if (is_array($json)) {
                    foreach ($json as $item) {
                        $num = (string)($item['number'] ?? '');
                        if ($num !== '') {
                            $numbers[] = $num;
                            $panelIds[$num] = $item['id'] ?? null;
                            $multicasts[$num] = $item['multicast'] ?? null;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        $this->channels = Channel::whereIn('number', $numbers)->orderByRaw('CAST(number AS UNSIGNED) ASC')->get();
        $this->channelPanelIds = $panelIds;
        $this->channelMulticasts = $multicasts;

        if ($this->channels->isNotEmpty()) {
            $default = $this->channels->firstWhere('number', '101') ?? $this->channels->first();
            $this->selectedChannel = $default->id;
        }
    }

    #[On('setTheme')]
    public function setTheme($theme)
    {
        $this->theme = $theme;
        $this->iframeRefreshKey++;
    }

    public function render()
    {
        return view('livewire.app.grafana.grafana-first');
    }

    public function getGrafanaUrlProperty()
    {
        $base = "https://172.16.100.177:3000/d-solo/0ce5d82e-9619-4da7-8301-e1c118fb4c14/multicast-monitor";

        [$from, $to] = $this->resolveTimeParams();

        $panelId = 1;
        if ($this->selectedChannel) {
            $channel = $this->channels->firstWhere('id', $this->selectedChannel);
            if ($channel && isset($this->channelPanelIds[$channel->number])) {
                $panelId = $this->channelPanelIds[$channel->number];
            }
        }

        $params = [
            "orgId"     => 1,
            "timezone"  => "browser",
            "refresh"   => "5s",
            "theme"     => $this->theme,
            "panelId"   => $panelId,
            "from"      => $from,
            "to"        => $to,
            "__feature.dashboardSceneSolo" => "true",
        ];

        if ($this->selectedChannel) {
            $params["var-canal"] = $this->selectedChannel;
        }

        $params["_k"] = substr(md5(json_encode([$from, $to, $this->selectedChannel, $panelId])), 0, 10);

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
