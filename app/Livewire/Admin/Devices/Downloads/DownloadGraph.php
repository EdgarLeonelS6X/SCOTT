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

    public function render()
    {
        return view('livewire.admin.devices.downloads.download-graph');
    }

    public function mount($year = null)
    {
        $this->selectedYear = $year ? (int) $year : (int) date('Y');
        $this->loadData();
        $this->loadDeviceData();
    }

    public function loadData()
    {
        try {
            $rows = DB::table('downloads')
                ->where('year', $this->selectedYear)
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

        $payload = [
            'data' => $this->monthlyData,
            'year' => $this->selectedYear,
            'kpis' => $this->kpis,
        ];

        try { $this->dispatch('downloads-updated', $payload); } catch (\Exception $e) {}
    }

    public function loadDeviceData()
    {
        try {
            $this->monthlyDeviceData = DB::table('downloads')
                ->select('device_type', DB::raw('SUM(`count`) as total'))
                ->groupBy('device_type')
                ->get();
        } catch (\Exception $e) {
            $this->monthlyDeviceData = collect();
        }
    }

    public function updatedSelectedYear($value)
    {
        $this->selectedYear = (int) $value;
        $this->loadData();
        $this->loadDeviceData();
    }
}
