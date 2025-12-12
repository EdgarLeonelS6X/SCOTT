<?php

namespace App\Livewire\Admin\Grafana;

use Livewire\Component;
use App\Models\GrafanaPanel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GrafanaTable extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        $this->authorize('viewAny', GrafanaPanel::class);

        $user = auth()->user();

        $query = GrafanaPanel::query();

        if ($user && in_array($user->area, ['OTT', 'DTH'])) {
            if ($user->area === 'DTH') {
                $query->where(function ($q) {
                    $q->where('area', 'DTH')
                        ->orWhereIn('id', [1, 3]);
                });
            } else {
                $query->where('area', $user->area);
            }
        }

        $panels = $query->get();

        $panels = $panels->sortBy(function ($panel) {
            if ($panel->id == 2) {
                return 3;
            }
            if ($panel->id == 3) {
                return 2;
            }
            return $panel->id;
        })->values();

        return view('livewire.admin.grafana.grafana-table', [
            'panels' => $panels,
        ]);
    }
}
