<?php

namespace App\Livewire\Admin\Channels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Channel;
use App\Enums\ChannelOrigin;
use App\Enums\ChannelCategory;

class ChannelTable extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactive = false;
    public $originFilter = null;
    public $categoryFilter = null;
    protected $queryString = ['search', 'showInactive', 'originFilter', 'categoryFilter'];

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

    public function toggleCategoryFilter()
    {
        $categories = collect(ChannelCategory::cases())->map(fn($case) => $case->value)->all();

        if ($this->categoryFilter === null) {
            $this->categoryFilter = $categories[0];
        } else {
            $currentIndex = array_search($this->categoryFilter, $categories);
            $this->categoryFilter = $categories[$currentIndex + 1] ?? null;
        }

        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->resetPage();
        $this->search = '';
        $this->showInactive = false;
        $this->originFilter = null;
        $this->categoryFilter = null;
    }

    public function render()
    {
        $query = Channel::query();

        if ($this->search) {
            $searchTerms = explode(' ', $this->search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('name', 'like', '%' . $term . '%')
                            ->orWhere('number', 'like', '%' . $term . '%');
                    });
                }
            });
        }

        if ($this->showInactive) {
            $query->where('status', '0');
        }

        if ($this->originFilter) {
            $query->where('origin', $this->originFilter);
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $channels = $query->orderBy('number')->paginate(10);

        return view('livewire.admin.channels.channel-table', [
            'channels' => $channels,
        ]);
    }
}
