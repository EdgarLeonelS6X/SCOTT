<?php

namespace App\Livewire\App\Reports\Create;

use App\Models\Channel;
use App\Models\ChannelTest;
use Livewire\Component;

class CreateProfileReport extends Component
{
    public $reportData;
    public $channel_id;
    public $high;
    public $medium;
    public $low;
    public $channels;

    public function mount()
    {
        $this->channels = Channel::all();

        $this->reportData = [
            'type' => __('Profiles'),
            'channels' => [$this->initializeChannel()]
        ];
    }

    public function addChannel()
    {
        $this->reportData['channels'][] = $this->initializeChannel();
    }

    public function removeChannel($index)
    {
        unset($this->reportData['channels'][$index]);

        $this->reportData['channels'] = array_values($this->reportData['channels']);
    }

    public function initializeChannel()
    {
        return [
            'channel_id' => '',
            'high' => '',
            'medium' => '',
            'low' => '',
        ];
    }

    public function save()
    {
        $this->validate([
            'channel_id' => 'required|exists:channels,id',
            'high' => 'required|string',
            'medium' => 'required|string',
            'low' => 'required|string',
        ]);

        ChannelTest::create([
            'channel_id' => $this->channel_id,
            'user_id' => auth()->id(),
            'high' => $this->high,
            'medium' => $this->medium,
            'low' => $this->low,
        ]);

        session()->flash('message', 'Prueba registrada correctamente.');

        $this->reset(['channel_id', 'high', 'medium', 'low']);
    }

    public function getChannelCount()
    {
        return count($this->reportData['channels']);
    }

    public function render()
    {
        return view('livewire.app.reports.create.create-profile-report');
    }
}
