<?php

namespace App\Livewire\Admin\Channels;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Channel;
use Illuminate\Support\Facades\Storage;

class EditChannel extends Component
{
    use WithFileUploads;

    public $channel;
    public $number;
    public $origin;
    public $name;
    public $url;
    public $category;
    public $status;
    public $image_url;
    public $new_image;
    public $profiles = [];

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
                    'html' => '<b>' . __('Your update contains the following errors:') . '</b><br><br>' . $errorMessages,
                ]);
            }
        });
    }

    public function mount(Channel $channel)
    {
        $this->channel = $channel;
        $this->number = $channel->number;
        $this->origin = $channel->origin;
        $this->name = $channel->name;
        $this->url = $channel->url;
        $this->category = $channel->category;
        $this->status = $channel->status;
        $this->image_url = $channel->image_url;
        $this->profiles = $channel->profiles ?? [];
    }

    public function update()
    {
        $this->validate([
            'number' => 'required|integer|unique:channels,number,' . $this->channel->id,
            'origin' => 'nullable|string',
            'name' => 'required|string',
            'url' => 'nullable|url',
            'category' => 'required|string',
            'status' => 'required|numeric',
            'new_image' => 'nullable|image',
            'profiles' => 'nullable|array',
            'profiles.high' => 'nullable|string',
            'profiles.medium' => 'nullable|string',
            'profiles.low' => 'nullable|string',
        ], [], [
            'number' => __('channel number'),
            'origin' => __('channel origin'),
            'name' => __('channel name'),
            'url' => __('channel URL'),
            'category' => __('channel category'),
            'status' => __('channel status'),
            'new_image' => __('new channel image'),
        ]);

        if ($this->new_image) {
            if ($this->image_url && Storage::exists($this->image_url)) {
                Storage::delete($this->image_url);
            }

            $imageName = time() . '_' . $this->new_image->getClientOriginalName();
            $this->image_url = $this->new_image->storeAs('channels', $imageName, 'public');
        }

        $this->channel->update([
            'number' => $this->number,
            'origin' => $this->origin,
            'name' => $this->name,
            'url' => $this->url,
            'category' => $this->category,
            'status' => $this->status,
            'image_url' => $this->image_url,
            'profiles' => $this->profiles ?: null
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Well done!'),
            'text' => __('Channel updated successfully.')
        ]);

        return redirect()->route('admin.channels.show', $this->channel);
    }

    public function render()
    {
        return view('livewire.admin.channels.edit-channel');
    }
}
