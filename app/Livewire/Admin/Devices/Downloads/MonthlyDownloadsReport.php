<?php

namespace App\Livewire\Admin\Devices\Downloads;

use Livewire\Component;
use App\Models\Device;
use App\Models\Download;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyDownloadsReport extends Component
{
    public $year;
    public $month;
    public $years = [];
    public $months = [];
    public $devices;
    public $counts = [];
    public $successMessage = null;

    protected $rules = [
        'year' => 'required|integer|min:1900',
        'month' => 'required|integer|min:1|max:12',
        'counts' => 'array',
        'counts.*' => 'integer|min:0',
    ];

    public function mount()
    {
        if (Auth::id() !== 1) {
            abort(403);
        }

        $this->year = (int) date('Y');
        $this->month = (int) date('n');
        $exclude = ['Web Client', 'Android Mobile', 'Android TV'];
        $this->devices = Device::whereNotIn('name', $exclude)->orderBy('name')->get();

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = 0;
        }

        $current = (int) date('Y');
        $currentMonth = (int) date('n');

        $minYear = Download::min('year');
        $maxYear = Download::max('year');

        if (! $minYear) {
            $minYear = $current - 5;
        }

        if (! $maxYear) {
            $maxYear = $current;
        }

        $maxYear = max($maxYear, $current);

        $minYear = min($minYear, $current - 3);

        for ($y = $maxYear; $y >= $minYear; $y--) {
            $this->years[] = $y;
        }

        if ($this->year >= $current) {
            $maxMonth = $currentMonth;
        } else {
            $maxMonth = 12;
        }

        $this->months = [];
        for ($m = 1; $m <= $maxMonth; $m++) {
            $this->months[] = $m;
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

    public function updatedYear()
    {
        $this->year = (int) $this->year;
        $this->buildMonths();
        $this->loadCounts();
    }

    public function updatedMonth()
    {
        $this->loadCounts();
    }

    protected function buildMonths()
    {
        $current = (int) date('Y');
        $currentMonth = (int) date('n');

        $selectedYear = (int) $this->year;

        if ($selectedYear >= $current) {
            $maxMonth = $currentMonth;
        } else {
            $maxMonth = 12;
        }

        $this->months = [];
        for ($m = 1; $m <= $maxMonth; $m++) {
            $this->months[] = $m;
        }

        if (! in_array((int) $this->month, $this->months)) {
            $this->month = (int) (end($this->months) ?: $currentMonth);
        }
    }

    protected function loadCounts()
    {
        foreach ($this->devices as $device) {
            $this->counts[$device->id] = 0;
        }
    }

    public function save()
    {
        foreach ($this->devices as $device) {
            if (! isset($this->counts[$device->id]) || $this->counts[$device->id] === '') {
                $this->counts[$device->id] = 0;
            }
            $this->counts[$device->id] = (int) $this->counts[$device->id];
        }

        $this->validate();

        DB::transaction(function () {
            foreach ($this->devices as $device) {
                $value = isset($this->counts[$device->id]) ? (int) $this->counts[$device->id] : 0;

                $download = new Download();
                $download->device_id = $device->id;
                $download->year = $this->year;
                $download->month = $this->month;
                $download->count = $value;
                $download->save();
            }
        });

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = 0;
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Monthly downloads saved successfully.'),
        ]);

        $this->dispatch('downloads-updated', [
            'year' => $this->year,
            'month' => $this->month,
        ]);

        $this->year = (int) date('Y');
        $this->month = (int) date('n');
        $this->buildMonths();
        $this->loadCounts();

        try { $this->dispatch('close-monthly-report-modal'); } catch (\Exception $e) {}
    }

    public function render()
    {
        return view('livewire.admin.devices.downloads.monthly-downloads-report');
    }
}
