<?php

namespace App\Livewire\Admin\Channels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Channel;
use App\Enums\ChannelOrigin;

class ChannelTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    public $originFilter = null;
    protected $queryString = ['search', 'showInactive', 'originFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowInactive()
    {
        $this->resetPage();
    }

    public function toggleOriginFilter()
    {
        $origins = collect(ChannelOrigin::cases())->map(fn($case) => $case->value)->all();

        if ($this->originFilter === null) {
            $this->originFilter = $origins[0];
        } else {
            $currentIndex = array_search($this->originFilter, $origins);
            $this->originFilter = $origins[$currentIndex + 1] ?? null;
        }

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

        if ($this->originFilter) {
            $query->where('origin', $this->originFilter);
        }

        $channels = $query->paginate(10);

        return view('livewire.admin.channels.channel-table', [
            'channels' => $channels,
        ]);
    }
}
