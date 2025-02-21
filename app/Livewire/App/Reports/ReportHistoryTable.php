<?php

namespace App\Livewire\App\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;
use App\Models\User;

class ReportHistoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderField = 'created_at';
    public $orderDirection = 'desc';
    public $statusFilter = null;
    public $selectedUser = null;
    public $typeFilter = null;
    public $reportTypes = ['Momentary', 'Hourly', 'Functions'];
    public $currentTypeIndex = 0;
    public $statusOptions = ['Revision', 'Resolved', 'Reported'];
    public $currentStatusIndex = 0;
    public $userOptions = [];
    public $currentUserIndex = 0;

    protected $queryString = ['search', 'orderField', 'orderDirection'];

    public function mount()
    {
        $this->userOptions = User::whereHas('reports')->pluck('name')->toArray();

        if (!empty($this->userOptions)) {
            $this->selectedUser = $this->userOptions[0];
        }
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

    public function setOrderByReporter()
    {
        $usersWithReports = Report::select('reported_by')
            ->distinct()
            ->pluck('reported_by')
            ->toArray();

        if (empty($usersWithReports))
            return;

        if ($this->orderField !== 'reported_by') {
            $this->orderField = 'reported_by';
            $this->currentUserIndex = 0;
        } else {
            $this->currentUserIndex = ($this->currentUserIndex + 1) % count($usersWithReports);
        }

        $this->selectedUser = $usersWithReports[$this->currentUserIndex];
    }

    public function toggleStatusFilter()
    {
        $this->currentStatusIndex = ($this->currentStatusIndex + 1) % count($this->statusOptions);
        $this->statusFilter = $this->statusOptions[$this->currentStatusIndex];
    }

    public function toggleTypeFilter()
    {
        $this->currentTypeIndex = ($this->currentTypeIndex + 1) % count($this->reportTypes);
        $this->typeFilter = $this->reportTypes[$this->currentTypeIndex];
    }

    public function resetFilters()
    {
        $this->reset(['search', 'orderField', 'orderDirection', 'statusFilter', 'selectedUser', 'currentUserIndex', 'typeFilter', 'currentTypeIndex']);
        $this->resetPage();
    }

    public function toggleUserFilter()
    {
        if (empty($this->userOptions)) {
            return;
        }

        $this->currentUserIndex = ($this->currentUserIndex + 1) % count($this->userOptions);
        $this->selectedUser = $this->userOptions[$this->currentUserIndex];
    }

    public function render()
    {
        $query = Report::query()
            ->where(function ($q) {
                $q->where('category', 'like', "%{$this->search}%")
                    ->orWhereHas('reportedBy', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('reportDetails.channel', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%")
                            ->orWhere('number', 'like', "%{$this->search}%");
                    });
            })
            ->with(['reportedBy', 'stages', 'reportDetails.channel']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->selectedUser) {
            $query->whereHas('reportedBy', function ($q) {
                $q->where('name', $this->selectedUser);
            });
        }

        $query->orderBy($this->orderField, $this->orderDirection);

        $reports = $query->paginate(10);

        return view('livewire.app.reports.report-history-table', compact('reports'));
    }
}
