<?php

namespace App\Livewire\Admin\Channels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Channel;

class ChannelTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    protected $queryString = ['search', 'showInactive'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowInactive()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Channel::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->showInactive) {
            $query->where('status', '0');
        }

        $channels = $query->paginate(10);

        return view('livewire.admin.channels.channel-table', [
            'channels' => $channels,
        ]);
    }
}