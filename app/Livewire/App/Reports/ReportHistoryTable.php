<?php

namespace App\Livewire\App\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Report;

class ReportHistoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderField = 'created_at';
    public $orderDirection = 'desc';
    public $selectedReport = null;
    public $showModal = false;
    public $statusFilter = null;
    public $selectedUser = null;
    public $currentUserIndex = 0;

    protected $queryString = ['search', 'orderField', 'orderDirection'];

    public function openReportDetails($reportId)
    {
        $this->selectedReport = Report::with(['reportedBy', 'stages', 'reportDetails.channel'])->find($reportId);
        $this->showModal = true;
    }

    public function closeReportDetails()
    {
        $this->selectedReport = null;
        $this->showModal = false;
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

    public function toggleStatusFilter($status)
    {
        if ($this->statusFilter === $status) {
            $this->statusFilter = null;
        } else {
            $this->statusFilter = $status;
        }
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

        if ($this->selectedUser) {
            $query->where('reported_by', $this->selectedUser);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $query->orderBy($this->orderField, $this->orderDirection);

        $reports = $query->paginate(10);

        return view('livewire.app.reports.report-history-table', compact('reports'));
    }
}
