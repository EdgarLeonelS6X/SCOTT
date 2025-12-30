<?php

namespace App\Livewire\Admin\Devices\Downloads;

use Livewire\Component;
use App\Models\Download;

class DownloadGraph extends Component
{
    public $devices;
    public $selectedYear;
    public $selectedDevice = null;
    public $monthlyData = [];

    public function mount()
    {
        $query = Download::query()->where('year', $this->selectedYear);

        if (!empty($this->selectedDevice)) {
            $query->where('device_id', $this->selectedDevice);
        }

        $rows = $query->selectRaw('month, SUM(`count`) as total')
            ->groupBy('month')
            ->get()
            ->keyBy(function($item){ return (int) $item->month; });

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[$m] = isset($rows[$m]) ? (int) $rows[$m]->total : 0;
        }

        $this->monthlyData = array_values($data);

        $this->dispatch('downloads-updated', ['data' => $this->monthlyData, 'year' => $this->selectedYear, 'device' => $this->selectedDevice]);
        for ($m = 1; $m <= 12; $m++) {
            $data[$m] = isset($rows[$m]) ? (int) $rows[$m]->count : 0;
        }

        $this->monthlyData = array_values($data);

        $this->dispatch('downloads-updated', ['data' => $this->monthlyData, 'year' => $this->selectedYear, 'device' => $this->selectedDevice]);
    }

    public function updatedSelectedYear()
    {
        $this->loadData();
    }

    public function updatedSelectedDevice()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.devices.downloads.download-graph', [
            'devices' => $this->devices,
        ]);
    }
}
