<?php

namespace App\Livewire\Admin\Radios;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Radio;
use Illuminate\Support\Facades\Storage;

class EditRadio extends Component
{
    use WithFileUploads;

    public $radioId;
    public $name;
    public $url;
    public $image_url; // temporary uploaded file
    public $existingImage; // public URL for existing image
    public $existingImagePath; // storage path for deletion
    public $status = '';
    public $area = 'DTH';

    public function mount(Radio $radio)
    {
        $this->radioId = $radio->id;
        $this->name = $radio->name;
        $this->url = $radio->url;
        $this->existingImage = $radio->image; // accessor returns Storage::url()
        $this->existingImagePath = $radio->image_url; // raw storage path
        $this->status = $radio->status ? 1 : 0;
        $this->area = $radio->area ?? 'DTH';
    }

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
                    'html' => '<b>' . __('Your changes contain the following errors:') . '</b><br><br>' . $errorMessages,
                ]);
            }
        });
    }

    public function update()
    {
        $radio = Radio::findOrFail($this->radioId);

        $this->authorize('update', $radio);

        $this->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:2048',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|in:1,0',
            'area' => 'required|string|in:OTT,DTH,DTH/OTT',
        ], [], [
            'name' => __('radio name'),
            'image_url' => __('radio image'),
            'area' => __('radio area'),
            'url' => __('radio url'),
        ]);

        $imagePath = $this->existingImagePath;
        if ($this->image_url) {
            $imageName = time() . '_' . $this->image_url->getClientOriginalName();
            $this->image_url->storeAs('radios', $imageName, 'public');
            $imagePath = 'radios/' . $imageName;

            if ($this->existingImagePath) {
                try {
                    Storage::disk('public')->delete($this->existingImagePath);
                } catch (\Throwable $e) {
                    // ignore deletion failure
                }
            }
        }

        $radio->update([
            'name' => $this->name,
            'url' => $this->url ?: null,
            'image_url' => $imagePath,
            'status' => (bool) $this->status,
            'area' => $this->area ?: 'DTH',
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => __('Updated!'),
            'text' => __('Radio updated successfully.'),
        ]);

        return redirect()->route('admin.radios.show', $radio);
    }

    public function render()
    {
        return view('livewire.admin.radios.edit-radio');
    }
}
