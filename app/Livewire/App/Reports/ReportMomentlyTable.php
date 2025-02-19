<?php

namespace App\Livewire\App\Reports;

use App\Models\Report;
use App\Mail\ReportResolvedMail;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReportMomentlyTable extends Component
{
    use WithPagination;

    public $search = '';
    public $order = 'desc';
    public $selectedReport = null;
    public $showModal = false;
    protected $queryString = ['search'];
    protected $listeners = ['reportCreated' => '$refresh'];

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

    public function openReportDetails($reportId)
    {
        $this->selectedReport = Report::with(['reportDetails.channel', 'reportedBy'])->find($reportId);
        $this->showModal = true;
    }

    public function closeReportDetails()
    {
        $this->selectedReport = null;
        $this->showModal = false;
    }

    public function toggleOrder()
    {
        $this->order = $this->order === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function markAsSolved()
    {
        if ($this->selectedReport) {
            $endTime = Carbon::now();
            $createdTime = Carbon::parse($this->selectedReport->created_at);
            $duration = $createdTime->diff($endTime)->format('%H:%I:%S');

            $this->selectedReport->update([
                'duration' => $duration,
                'attended_by' => auth()->user()->id,
                'status' => 'Resolved',
            ]);

            foreach ($this->selectedReport->reportDetails as $detail) {
                $detail->update(['status' => 'Resolved']);
            }

            $this->dispatch('reportUpdated');

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('The report and channels have been marked as resolved.'),
            ]);

            if ($this->selectedReport->reportedBy) {
                Mail::to($this->selectedReport->reportedBy->email)
                    ->send(new ReportResolvedMail($this->selectedReport));
            }

            $this->showModal = false;
        }
    }

    public function render()
    {
        $reports = Report::where('type', 'Momentary')
            ->where('status', 'Revision')
            ->where(function ($query) {
                $query->where('category', 'like', '%' . $this->search . '%')
                    ->orWhereHas('reportDetails.channel', function ($channelQuery) {
                        $channelQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('number', 'like', '%' . $this->search . '%');
                    });
            })
            ->with(['reportDetails.channel', 'reportedBy', 'attendedBy'])
            ->orderBy('created_at', $this->order)
            ->paginate(5);

        $reports->getCollection()->transform(function ($report) {
            $report->formatted_date = $this->formatDate($report->created_at);
            $report->channels_preview = $report->reportDetails->take(3)
                ->map(fn($detail) => $detail->channel->name)
                ->implode(', ') . ($report->reportDetails->count() > 3 ? '...' : '');
            return $report;
        });

        return view('livewire.app.reports.report-momently-table', [
            'reports' => $reports,
        ]);
    }
}
