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
    public $editingId = null;
    public $perPage = 10;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'status' => 'boolean',
    ];

    public function render()
    {
        $auth = auth()->user();

        $query = Device::query();

        if (! ($auth && $auth->id === 1)) {
            $query->where('area', $auth->area ?? 'OTT');
        }

        $devices = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.admin.devices.device-table', [
            'devices' => $devices,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('update', $device);

        $this->editingId = $device->id;
        $this->name = $device->name;
        $this->status = (bool) $device->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $device = Device::findOrFail($this->editingId);
            $this->authorize('update', $device);

            $device->update([
                'name' => $this->name,
                'status' => $this->status,
            ]);

            $this->emit('swal', [[
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Device updated successfully.'),
            ]]);
        } else {
            $this->authorize('create', Device::class);

            Device::create([
                'name' => $this->name,
                'status' => $this->status,
                'area' => 'OTT',
            ]);

            $this->emit('swal', [[
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Device created successfully.'),
            ]]);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function delete($id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('delete', $device);

        $device->delete();

        $this->emit('swal', [[
            'icon' => 'success',
            'title' => __('Deleted'),
            'text' => __('Device removed successfully.'),
        ]]);

        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('update', $device);

        $device->status = ! $device->status;
        $device->save();

        $this->emit('swal', [[
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Device status updated.'),
        ]]);

        $this->resetPage();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->status = true;
    }
}
