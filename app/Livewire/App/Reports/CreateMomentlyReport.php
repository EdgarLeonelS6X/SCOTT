<?php

namespace App\Livewire\App\Reports;

use Livewire\Component;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\Stage;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateMomentlyReport extends Component
{
    public $reportData;
    public $stages;
    public $protocols = ['DASH', 'HLS', 'DASH/HLS'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];

    public function mount()
    {
        $this->stages = Stage::where('status', '1')->get();
        $this->reportData = [
            'category' => '',
            'attended_by' => '',
            'channels' => [$this->initializeChannel()],
        ];
    }

    public function addChannel()
    {
        $this->reportData['channels'][] = $this->initializeChannel();
    }

    public function removeChannel($index)
    {
        unset($this->reportData['channels'][$index]);
        $this->reportData['channels'] = array_values($this->reportData['channels']);
    }

    protected function initializeChannel()
    {
        return [
            'channel_id' => '',
            'stage' => '',
            'protocol' => '',
            'media' => '',
            'description' => '',
        ];
    }

    public function saveReport()
    {
        try {
            $this->validateReportData();

            foreach ($this->reportData['channels'] as $channel) {
                $existingChannel = ReportDetail::where('channel_id', $channel['channel_id'])
                    ->where('status', 'Pending')
                    ->exists();

                if ($existingChannel) {
                    throw ValidationException::withMessages([
                        'reportData.channels' => __('The channel is currently under review and cannot be added.')
                    ]);
                }
            }

            $report = Report::create([
                'type' => 'Momentary',
                'category' => $this->reportData['category'],
                'attended_by' => $this->reportData['attended_by'],
                'report_date' => now()->toDateString(),
                'reported_by' => Auth::user()->id,
                'end_time' => null,
                'duration' => null,
                'status' => __('Pending'),
            ]);

            foreach ($this->reportData['channels'] as $channel) {
                ReportDetail::create([
                    'report_id' => $report->id,
                    'channel_id' => $channel['channel_id'],
                    'stage_id' => $channel['stage'],
                    'protocol' => $channel['protocol'],
                    'media' => $channel['media'],
                    'description' => $channel['description'],
                    'status' => __('Pending'),
                ]);
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Momentary report created successfully.')
            ]);

            $this->dispatch('reportCreated');

        } catch (ValidationException $e) {
            $errorMessages = '<ul style="text-align: center;">';

            foreach ($e->errors() as $errorMessagesArray) {
                foreach ($errorMessagesArray as $message) {
                    $errorMessages .= "<li>• $message</li>";
                }
            }

            $errorMessages .= '</ul>';

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Error'),
                'html' => '<b>' . __('Your report log contains errors:') . '</b><br><br>' . $errorMessages,
            ]);
        }
    }

    protected function validateReportData()
    {
        $this->validate([
            'reportData.category' => 'required|string|max:255',
            'reportData.attended_by' => 'required|string|max:255',
            'reportData.channels' => 'required|array|min:1',
        ], [], [
            'reportData.category' => __('category name'),
            'reportData.attended_by' => __('attended by'),
            'reportData.channels' => __('channels')
        ]);

        foreach ($this->reportData['channels'] as $index => $channel) {
            $this->validate([
                "reportData.channels.$index.channel_id" => 'required|exists:channels,id',
                "reportData.channels.$index.stage" => 'required|exists:stages,id',
                "reportData.channels.$index.protocol" => 'required|in:' . implode(',', $this->protocols),
                "reportData.channels.$index.media" => 'required|in:' . implode(',', $this->mediaOptions),
            ], [], [
                "reportData.channels.$index.channel_id" => __('channel'),
                "reportData.channels.$index.stage" => __('stage'),
                "reportData.channels.$index.protocol" => __('protocol'),
                "reportData.channels.$index.media" => __('media'),
            ]);
        }
    }

    public function getChannelCount()
    {
        return count($this->reportData['channels']);
    }

    public function render()
    {
        return view('livewire.app.reports.create-momently-report', [
            'channels' => Channel::where('status', '1')->get(),
        ]);
    }
}
