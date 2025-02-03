<?php

namespace App\Livewire\App\Reports;

use App\Models\Channel;
use Livewire\Component;
use App\Models\Report;
use Livewire\WithPagination;
use Carbon\Carbon;

class ReportMomentlyTable extends Component
{
    use WithPagination;

    protected $listeners = ['reportCreated' => '$refresh'];
    public $search = '';
    public $order = 'desc';
    protected $queryString = ['search'];
    public $selectedReport = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function formatDate($date)
    {
        $carbonDate = Carbon::parse($date);
        if ($carbonDate->isToday()) {
            return __('It was reported today at ') . $carbonDate->format('H:i');
        } elseif ($carbonDate->isYesterday()) {
            return __('It was reported yesterday at ') . $carbonDate->format('H:i');
        } else {
            return __('It was reported ') . $carbonDate->diffForHumans();
        }
    }

    public function toggleOrder()
    {
        $this->order = $this->order === 'asc' ? 'desc' : 'asc';
    }

    public function openReportDetails($reportId)
    {
        $this->selectedReport = Report::with(['reportDetails.channel', 'reportedBy'])->find($reportId);
    }

    public function closeReportDetails()
    {
        $this->selectedReport = null;
    }

    public function render()
    {
        $filteredChannels = Channel::where('number', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->get();

        $reports = Report::where('type', 'Momentary')
            ->whereHas('reportDetails', function ($query) use ($filteredChannels) {
                $query->whereIn('channel_id', $filteredChannels->pluck('id')->toArray());
            })
            ->with(['reportDetails.channel', 'reportedBy'])
            ->orderBy('created_at', $this->order)
            ->paginate(5);

        $reports->getCollection()->transform(function ($report) {
            $report->formatted_date = $this->formatDate($report->created_at);
            return $report;
        });

        return view('livewire.app.reports.report-momently-table', [
            'reports' => $reports,
        ]);
    }
}