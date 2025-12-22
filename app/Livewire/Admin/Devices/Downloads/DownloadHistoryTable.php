<?php

namespace App\Livewire\Admin\Devices\Downloads;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Download;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DownloadHistoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderField = 'created_at';
    public $orderDirection = 'desc';
    public $areaFilter = 'all';
    public $startDate;
    public $endDate;
    public $showDetailsModal = false;
    public $detailsYear;
    public $detailsMonth;
    public $detailsDownloads = [];
    public $detailsGrouped = [];
    protected $queryString = ['search', 'orderField', 'orderDirection', 'areaFilter' => ['except' => 'all']];

    public function mount()
    {
        $this->search = $this->search ?? '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setOrder($field)
    {
        if ($this->orderField === $field) {
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orderField = $field;
            $this->orderDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'orderField',
            'orderDirection',
            'startDate',
            'endDate',
        ]);

        $this->orderField = 'created_at';
        $this->orderDirection = 'desc';
        $this->areaFilter = 'all';
        $this->resetPage();

        $this->dispatch('clear-datepicker-range');
    }

    public function toggleAreaFilter()
    {
        $auth = Auth::user();
        if (! $auth || $auth->id !== 1) {
            return;
        }

        $options = ['all', 'DTH', 'OTT'];
        $currentIndex = array_search($this->areaFilter, $options, true);
        $this->areaFilter = $options[($currentIndex === false ? 0 : ($currentIndex + 1) % count($options))];
        $this->resetPage();
    }

    protected function filteredQuery()
    {
        $query = Download::query()
            ->with(['device'])
            ->where(function ($q) {
                $q->when($this->search, function ($qq) {
                    $s = "%{$this->search}%";
                    $qq->whereHas('device', fn($d) => $d->where('name', 'like', $s))
                       ->orWhere('year', 'like', $s)
                       ->orWhere('month', 'like', $s);
                });
            });

        if ($this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $end = \Carbon\Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $auth = Auth::user();

        if ($auth && $auth->id === 1) {
            if ($this->areaFilter && in_array($this->areaFilter, ['DTH', 'OTT'], true)) {
                $query->whereHas('device', fn($d) => $d->where('area', $this->areaFilter));
            }
        } else {
            if ($auth && ($viewerArea = ($auth->area))) {
                $query->whereHas('device', fn($d) => $d->where('area', $viewerArea));
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    protected function aggregatesQuery()
    {
        $query = Download::query()
            ->select('downloads.year', 'downloads.month', DB::raw('SUM(downloads.count) as total_count'), DB::raw('MAX(downloads.created_at) as created_at'))
            ->join('devices', 'downloads.device_id', '=', 'devices.id');

        if ($this->search) {
            $s = "%{$this->search}%";
            $query->where(function ($q) use ($s) {
                $q->where('downloads.year', 'like', $s)
                  ->orWhere('downloads.month', 'like', $s);
            });
        }

        if ($this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $end = \Carbon\Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            $query->whereBetween('downloads.created_at', [$start, $end]);
        }

        $auth = Auth::user();

        if ($auth && $auth->id === 1) {
            if ($this->areaFilter && in_array($this->areaFilter, ['DTH', 'OTT'], true)) {
                $query->where('devices.area', $this->areaFilter);
            }
        } else {
            if ($auth && ($viewerArea = ($auth->area))) {
                $query->where('devices.area', $viewerArea);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $query->groupBy('downloads.year', 'downloads.month');

        return $query;
    }

    public function showMonthDetails($year, $month)
    {
        $this->detailsYear = (int) $year;
        $this->detailsMonth = (int) $month;

        $query = Download::with('device')
            ->where('year', $this->detailsYear)
            ->where('month', $this->detailsMonth);

        $auth = Auth::user();

        if ($auth && $auth->id === 1) {
            if ($this->areaFilter && in_array($this->areaFilter, ['DTH', 'OTT'], true)) {
                $query->whereHas('device', fn($d) => $d->where('area', $this->areaFilter));
            }
        } else {
            if ($auth && ($viewerArea = ($auth->area))) {
                $query->whereHas('device', fn($d) => $d->where('area', $viewerArea));
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $this->detailsDownloads = $query->orderByDesc('created_at')->get();

        $grouped = $this->detailsDownloads->groupBy(function ($d) {
            return $d->created_at->format('Y-m-d H:i:s');
        });

        $this->detailsGrouped = $grouped->map(function ($items) {
            $arr = $items->map(function ($i) {
                return [
                    'id' => $i->id,
                    'device_name' => optional($i->device)->name,
                    'protocol' => optional($i->device)->protocol ?? '',
                    'image' => optional($i->device)->thumbnail ?? optional($i->device)->image ?? optional($i->device)->icon ?? null,
                    'count' => $i->count,
                    'created_at' => $i->created_at->format('Y-m-d H:i:s'),
                ];
            })->values()->toArray();

            usort($arr, function ($a, $b) {
                $order = ['HLS' => 0, 'DASH' => 1];
                $pa = strtoupper($a['protocol'] ?? '');
                $pb = strtoupper($b['protocol'] ?? '');
                $ra = $order[$pa] ?? 2;
                $rb = $order[$pb] ?? 2;
                if ($ra !== $rb) {
                    return $ra < $rb ? -1 : 1;
                }
                if ($ra === 2 && $pa !== $pb) {
                    $cmp = strcmp($pa, $pb);
                    if ($cmp !== 0) return $cmp;
                }
                return strcmp($a['device_name'] ?? '', $b['device_name'] ?? '');
            });

            return $arr;
        })->toArray();

        $this->showDetailsModal = true;
    }

    public function render()
    {
        $query = $this->aggregatesQuery();

        if ($this->orderField === 'count') {
            $query->orderBy('total_count', $this->orderDirection);
        } elseif ($this->orderField === 'created_at') {
            $query->orderBy('created_at', $this->orderDirection);
        } elseif (in_array($this->orderField, ['year', 'month'], true)) {
            $query->orderBy($this->orderField, $this->orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $aggregates = $query->paginate(10);

        return view('livewire.admin.devices.downloads.download-history-table', compact('aggregates'));
    }
}
