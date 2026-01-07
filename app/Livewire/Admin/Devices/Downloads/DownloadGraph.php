<?php

namespace App\Livewire\Admin\Devices\Downloads;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DownloadGraph extends Component
{
    public $selectedYear;
    public $monthlyData = [];
    public $kpis = [];
    public $monthlyDeviceData = [];
    public $devices;
    public $selectedDevice = null;

    public function render()
    {
        return view('livewire.admin.devices.downloads.download-graph');
    }

    public function mount($year = null)
    {
        $this->selectedYear = $year ? (int) $year : (int) date('Y');
        try {
            $this->devices = DB::table('devices')->select('id','name')->orderBy('name')->get();
        } catch (\Exception $e) {
            $this->devices = collect();
        }

        $this->loadData();
        $this->loadDeviceData();
    }

    public function loadData()
    {
        try {
            $rows = DB::table('downloads')
                ->where('year', $this->selectedYear)
                ->when($this->selectedDevice && $this->selectedDevice !== '', function ($q) {
                    $q->where('device_id', $this->selectedDevice);
                })
                ->selectRaw('month, SUM(`count`) as total')
                ->groupBy('month')
                ->get()
                ->keyBy(function ($item) { return (int) $item->month; });
        } catch (\Exception $e) {
            $rows = collect();
        }

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = isset($rows[$m]) ? (int) $rows[$m]->total : 0;
        }

        $this->monthlyData = $data;

        $total = array_sum($data);
        $average = $total ? (int) round($total / 12) : 0;
        $max = $data ? max($data) : 0;
        $topMonth = '—';
        if ($max > 0) {
            $idx = array_search($max, $data);
            $topMonth = date('F', mktime(0, 0, 0, $idx + 1, 1));
        }

        $this->kpis = [
            'total' => $total,
            'average' => $average,
            'top' => ['month' => $topMonth, 'value' => $max],
        ];

        try {
            $this->loadDeviceData();
        } catch (\Exception $e) {
        }

        if (!empty($this->monthlyDeviceData) && is_iterable($this->monthlyDeviceData)) {
            $top = collect($this->monthlyDeviceData)->sortByDesc('total')->first();
            if ($top) {
                $deviceName = $top->name ?? ($top->device_id ?? '—');
                $deviceTotal = isset($top->total) ? (int) $top->total : 0;
                $this->kpis['top_device'] = ['name' => $deviceName ?? '—', 'total' => $deviceTotal];
            }
        }

        $payload = [
            'data' => $this->monthlyData,
            'year' => $this->selectedYear,
            'kpis' => $this->kpis,
            'device_name' => null,
            'device_id' => $this->selectedDevice,
        ];

        if ($this->selectedDevice) {
            try {
                $name = DB::table('devices')->where('id', $this->selectedDevice)->value('name');
                $payload['device_name'] = $name ?: null;
            } catch (\Exception $e) {
                $payload['device_name'] = null;
            }
        }

        try { $this->dispatch('downloads-updated', $payload); } catch (\Exception $e) {}
    }

    public function loadDeviceData()
    {
        try {
            $this->monthlyDeviceData = DB::table('downloads')
                ->where('year', $this->selectedYear)
                ->join('devices', 'downloads.device_id', '=', 'devices.id')
                ->select('devices.id as device_id', 'devices.name', DB::raw('SUM(`count`) as total'))
                ->groupBy('devices.id', 'devices.name')
                ->get();
        } catch (\Exception $e) {
            $this->monthlyDeviceData = collect();
        }
    }

    public function updatedSelectedDevice($value)
    {
        $this->selectedDevice = $value === '' ? null : $value;
        $this->loadData();
        $this->loadDeviceData();
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedYear = (int) $value;
        $this->loadData();
        $this->loadDeviceData();
    }
}
