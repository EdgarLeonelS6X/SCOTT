<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class GrafanaSettings extends Component
{
    public $dth_url;
    public $cutv_url;
    public $api_key;
    public $success = false;
    public $error = '';

    public function mount()
    {
        $this->dth_url = Cache::get('grafana_dth_url', config('grafana.dth_url'));
        $this->cutv_url = Cache::get('grafana_cutv_url', config('grafana.cutv_url'));
        $this->api_key = Cache::get('grafana_api_key', config('grafana.api_key'));
    }

    public function save()
    {
        try {
            Cache::put('grafana_dth_url', $this->dth_url);
            Cache::put('grafana_cutv_url', $this->cutv_url);
            Cache::put('grafana_api_key', $this->api_key);
            $this->success = true;
            $this->error = '';
            $this->dispatch('grafana-settings-updated');
        } catch (\Exception $e) {
            $this->success = false;
            $this->error = __('Could not save settings.');
        }
    }

    public function render()
    {
        return view('livewire.admin.grafana-settings');
    }
}
