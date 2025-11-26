<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;

class UserTable extends Component
{
    public $areaFilter = 'all';

    protected $queryString = [
        'areaFilter' => ['except' => 'all'],
    ];

    public function toggleAreaFilter()
    {
        $auth = auth()->user();

        if (! $auth || $auth->id !== 1) {
            return;
        }

        $options = ['all', 'DTH', 'OTT'];

        $currentIndex = array_search($this->areaFilter, $options, true);

        $this->areaFilter = $options[($currentIndex === false ? 0 : ($currentIndex + 1) % count($options))];
    }

    public function render()
    {
        $user = auth()->user();

        $query = User::query();

        if ($user && $user->id === 1) {
            if (in_array($this->areaFilter, ['OTT', 'DTH'])) {
                $filter = $this->areaFilter;
                $query->where(function ($q) use ($filter) {
                    $q->where('default_area', $filter)
                      ->orWhere('area', $filter);
                });
            }
        } else {
            if ($user) {
                $viewerArea = $user->default_area ?? $user->area ?? null;

                if (in_array($viewerArea, ['OTT', 'DTH'])) {
                    $filter = $viewerArea;
                    $query->where(function ($q) use ($filter) {
                        $q->where('default_area', $filter)
                          ->orWhere('area', $filter);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }

        $users = $query->orderBy('status', 'desc')->get();

        return view('livewire.admin.users.user-table', compact('users'));
    }
}
