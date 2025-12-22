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

        $this->year = date('Y');
        $this->month = date('n');
        $exclude = ['Web Client', 'Android Mobile', 'Android TV'];
        $this->devices = Device::whereNotIn('name', $exclude)->orderBy('name')->get();

        foreach ($this->devices as $device) {
            $this->counts[$device->id] = 0;
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
        $this->loadCounts();
    }

    public function updatedMonth()
    {
        $this->loadCounts();
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
    }

    public function render()
    {
        return view('livewire.admin.devices.downloads.monthly-downloads-report');
    }
}
