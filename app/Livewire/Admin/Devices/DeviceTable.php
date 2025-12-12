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
        $options = ['HLS', 'DASH'];

        $currentIndex = array_search($this->protocolFilter, $options, true);

        if ($currentIndex === false) {
            $this->protocolFilter = $options[0];
        } else {
            $next = $currentIndex + 1;
            $this->protocolFilter = $next >= count($options) ? null : $options[$next];
        }

        $this->resetPage();
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

        if (! is_null($this->protocolFilter)) {
            $query->where('protocol', $this->protocolFilter);
        }

        if (! is_null($this->statusFilter)) {
            $query->where('status', $this->statusFilter ? 1 : 0);
        }

        $devices = $query->orderBy('id', 'desc')->get();

        return view('livewire.admin.devices.device-table', [
            'devices' => $devices,
        ]);
    }
}
