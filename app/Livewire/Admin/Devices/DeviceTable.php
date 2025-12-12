<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Device;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeviceTable extends Component
{
    use WithPagination, AuthorizesRequests;

    public $name = '';
    public $status = true;
    public $protocolFilter = null;
    public $statusFilter = null;
    public $queryString = ['protocolFilter', 'statusFilter'];

    public function toggleProtocolFilter()
    {
        $options = ['all', 'HLS', 'DASH'];

        $currentIndex = array_search($this->protocolFilter, $options, true);

        $this->protocolFilter = $options[($currentIndex === false ? 0 : ($currentIndex + 1) % count($options))];
    }

    public function toggleStatusFilter()
    {
        if ($this->statusFilter === null) {
            $this->statusFilter = true;
        } elseif ($this->statusFilter === true) {
            $this->statusFilter = false;
        } else {
            $this->statusFilter = null;
        }

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('viewAny', Device::class);

        $auth = auth()->user();

        $query = Device::query();

        if (! ($auth && $auth->id === 1)) {
            $query->where('area', $auth->area ?? 'OTT');
        }

        // Apply protocol filter when set and not 'all'
        if ($this->protocolFilter && $this->protocolFilter !== 'all') {
            $query->where('protocol', $this->protocolFilter);
        }

        // Apply status filter when not null (true/false)
        if (! is_null($this->statusFilter)) {
            $query->where('status', $this->statusFilter ? 1 : 0);
        }

        $devices = $query->orderBy('id', 'desc')->get();

        return view('livewire.admin.devices.device-table', [
            'devices' => $devices,
        ]);
    }
}
