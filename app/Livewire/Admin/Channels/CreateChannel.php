<?php

namespace App\Livewire\Admin\Channels;

use App\Models\Channel;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateChannel extends Component
{
    use WithFileUploads;

    public $image_url;
    public $number;
    public $origin = '';
    public $name;
    public $url;
    public $category = '';
    public $status = '';

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
                    'title' => '¡Error!',
                    'html' => '<b>' . __('Your registration for a new channel contains the following errors:') . '</b><br><br>' . $errorMessages,
                ]);
            }
        });
    }

    public function store()
    {
        $this->validate([
            'image_url' => 'required|unique:channels,image_url',
            'number' => 'required|integer|unique:channels,number',
            'origin' => 'nullable|string',
            'name' => 'required|string',
            'url' => 'nullable|url',
            'category' => 'required|string',
            'status' => 'required|string',
        ], [], [
            'image_url' => __('channel image'),
            'number' => __('channel number'),
            'origin' => __('channel origin'),
            'name' => __('channel name'),
            'url' => __('channel URL'),
            'category' => __('channel category'),
            'status' => __('channel status'),
        ]);

        $imageName = time() . '_' . $this->image_url->getClientOriginalName();

        $this->image_url->storeAs('channels', $imageName, 'public');

        $channel = Channel::create([
            'image_url' => 'channels/' . $imageName,
            'number' => $this->number,
            'origin' => $this->origin,
            'name' => $this->name,
            'url' => $this->url,
            'category' => $this->category,
            'status' => $this->status,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('New channel created successfully.')
        ]);

        return redirect()->route('admin.channels.show', $channel);
    }

    public function render()
    {
        return view('livewire.admin.channels.create-channel');
    }
}