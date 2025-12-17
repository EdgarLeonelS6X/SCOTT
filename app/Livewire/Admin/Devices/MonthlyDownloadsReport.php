<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use App\Models\Device;
use App\Models\Download;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyDownloadsReport extends Component
{
    public $year;
    public $month;
    public $devices;
    public $counts = [];
    public $successMessage = null;

    protected $rules = [
        'year' => 'required|integer|min:1900',
        'month' => 'required|integer|min:1|max:12',
        'counts' => 'array',
        'counts.*' => 'nullable|integer|min:0',
    ];

    public function mount()
    {
        if (Auth::id() !== 1) {
            abort(403);
        }

        $this->year = date('Y');
        $this->month = date('n');
        $this->devices = Device::orderBy('name')->get();

        $downloads = Download::where('year', $this->year)
            ->where('month', $this->month)
            ->get()
            ->keyBy('device_id');

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = $downloads->get($device->id)->count ?? 0;
        }
    }

    public function getTotalProperty()
    {
        return array_sum(array_map('intval', $this->counts));
    }

    public function getAverageProperty()
    {
        $n = count($this->counts) ?: 1;
        return (int) round($this->total / $n);
    }

    public function fillZeros()
    {
        foreach ($this->devices as $device) {
            $this->counts[$device->id] = 0;
        }
    }

    public function fillFromPrevious()
    {
        $prevMonth = (int)$this->month - 1;
        $prevYear = (int)$this->year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear -= 1;
        }

        $prev = Download::where('year', $prevYear)
            ->where('month', $prevMonth)
            ->get()
            ->keyBy('device_id');

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = $prev->get($device->id)->count ?? 0;
        }
    }

    public function updatedYear()
    {
        $this->loadCounts();
    }

    public function updatedMonth()
    {
        $this->loadCounts();
    }

    protected function loadCounts()
    {
        $downloads = Download::where('year', $this->year)
            ->where('month', $this->month)
            ->get()
            ->keyBy('device_id');

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = $downloads->get($device->id)->count ?? 0;
        }
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            foreach ($this->devices as $device) {
                $value = isset($this->counts[$device->id]) ? (int) $this->counts[$device->id] : 0;

                $download = Download::firstOrNew([
                    'device_id' => $device->id,
                    'year' => $this->year,
                    'month' => $this->month,
                ]);

                $download->count = $value;
                $download->save();
            }
        });

        $this->successMessage = __('Monthly downloads saved successfully.');
        $this->emit('downloadReportSaved');
    }
    public function render()
    {
        return view('livewire.admin.devices.monthly-downloads-report');
    }
}
