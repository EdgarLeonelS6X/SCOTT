<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Device;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateDevice extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $name;
    public $image_url;
    public $status = true;
    public $area = 'OTT';

    public function boot()
    {
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $errorMessages = '<ul style="list-style-type: disc; padding-left: 20px; margin-left: 0; padding-right: 0;">';

                foreach ($validator->errors()->all() as $error) {
                    $errorMessages .= "<li style='list-style-position: inside;'>$error</li>";
                }

                $errorMessages .= '</ul>';

                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => __('Error'),
                    'html' => '<b>' . __('Please fix the following errors:') . '</b><br><br>' . $errorMessages,
                ]);
            }
        });
    }

    public function store()
    {
        $this->authorize('create', Device::class);

        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean',
            'area' => 'nullable|string|in:OTT,DTH,DTH/OTT',
            'image_url' => 'nullable|image|max:2048',
        ], [], [
            'name' => __('device name'),
            'image_url' => __('device image'),
            'area' => __('device area'),
        ]);

        $imagePath = null;
        if ($this->image_url) {
            $imageName = time() . '_' . $this->image_url->getClientOriginalName();
            $this->image_url->storeAs('devices', $imageName, 'public');
            $imagePath = 'devices/' . $imageName;
        }

        $device = Device::create([
            'name' => $this->name,
            'status' => $this->status,
            'area' => $this->area ?: 'OTT',
            'image_url' => $imagePath,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('New device created successfully.'),
        ]);

        return redirect()->route('devices.show', $device);
    }

    public function render()
    {
        return view('livewire.admin.devices.create-device');
    }
}

