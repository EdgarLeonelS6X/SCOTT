<?php

namespace App\Livewire\App\Reports\Create;

use App\Mail\Reports\ReportCreatedMail;
use Livewire\Component;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\Stage;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class CreateMomentlyReport extends Component
{
    public $reportData;
    public $stages;
    public $protocols = ['HLS', 'DASH', 'HLS/DASH'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];

    public function mount()
    {
        $this->stages = Stage::where('status', '1')->get();
        $this->reportData = [
            'type' => __('Momentary'),
            'category' => '',
            'reported_by' => '',
            'reviewed_by' => '',
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
                    ->where('status', 'Revision')
                    ->exists();

                if ($existingChannel) {
                    throw ValidationException::withMessages([
                        'reportData.channels' => __('The channel is currently under review and cannot be added.')
                    ]);
                }
            }

            $channelIds = array_column($this->reportData['channels'], 'channel_id');

            $channelCounts = array_count_values($channelIds);

            foreach ($channelCounts as $channelId => $count) {
                if ($count > 1) {
                    throw ValidationException::withMessages([
                        'reportData.channels' => __('The channel ":channel" cannot be selected more than once.', [
                            'channel' => Channel::find($channelId)?->number ?? $channelId
                        ])
                    ]);
                }
            }

            $report = Report::create([
                'category' => $this->reportData['category'],
                'type' => $this->reportData['type'],
                'duration' => null,
                'reported_by' => Auth::user()->id,
                'reviewed_by' => $this->reportData['reviewed_by'],
                'attended_by' => null,
                'status' => __('Revision'),
            ]);

            foreach ($this->reportData['channels'] as $channel) {
                ReportDetail::create([
                    'report_id' => $report->id,
                    'channel_id' => $channel['channel_id'],
                    'stage_id' => $channel['stage'],
                    'protocol' => $channel['protocol'],
                    'media' => $channel['media'],
                    'description' => $channel['description'],
                    'status' => __('Revision'),
                ]);
            }

            $emails = User::whereJsonContains('report_mail_preferences->report_created', true)
                ->pluck('email')
                ->toArray();

            Mail::to($emails)->send(new ReportCreatedMail($report));

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
            'reportData.reviewed_by' => 'required|string|max:255',
            'reportData.channels' => 'required|array|min:1',
        ], [], [
            'reportData.category' => __('category name'),
            'reportData.reviewed_by' => __('reviewed by'),
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
        return view('livewire.app.reports.create.create-momently-report', [
            'channels' => Channel::where('status', '1')->orderBy('number')->get(),
            'stages' => Stage::all(),
        ]);
    }
}
