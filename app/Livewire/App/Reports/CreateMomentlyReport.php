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
    public $category;
    public $stages;
    public $protocols = ['DASH', 'HLS', 'DASH/HLS'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];

    public function mount()
    {
        $this->stages = Stage::where('status', '1')->get();
        $this->category = [
            'name' => '',
            'channels' => [
                $this->initializeChannel(),
            ],
        ];
    }

    public function addChannel()
    {
        $this->category['channels'][] = $this->initializeChannel();
    }

    public function removeChannel($channelIndex)
    {
        unset($this->category['channels'][$channelIndex]);
        $this->category['channels'] = array_values($this->category['channels']);
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

            foreach ($this->category['channels'] as $channel) {
                $existingChannel = ReportDetail::where('channel_id', $channel['channel_id'])
                    ->where('status', 'Pending')
                    ->exists();

                if ($existingChannel) {
                    throw ValidationException::withMessages([
                        'category.channels' => __('The channel is currently under review and cannot be added until it is marked as correct.')
                    ]);
                }
            }

            $report = Report::create([
                'type' => 'Momentary',
                'report_date' => now()->toDateString(),
                'reported_by' => Auth::user()->id,
                'end_time' => null,
                'duration' => null,
                'status' => __('Pending'),
            ]);

            foreach ($this->category['channels'] as $channel) {
                ReportDetail::create([
                    'report_id' => $report->id,
                    'channel_id' => $channel['channel_id'],
                    'stage_id' => $channel['stage'],
                    'category' => $this->category['name'],
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
                'title' => __('¡Error!'),
                'html' => '<b>' . __('Your report log contains the following errors:') . '</b>' . '<br>' . '<br>' . $errorMessages,
            ]);
        }
    }

    protected function validateReportData()
    {
        $this->validate([
            'category.name' => 'required|string|max:255',
            'category.channels' => 'required|array|min:1',
        ], [], [
            'category.name' => __('category name'),
            'category.channels' => __('channels')
        ]);

        foreach ($this->category['channels'] as $channelIndex => $channel) {
            $this->validate([
                "category.channels.$channelIndex.channel_id" => 'required|exists:channels,id',
                "category.channels.$channelIndex.stage" => 'required|exists:stages,id',
                "category.channels.$channelIndex.protocol" => 'required|in:' . implode(',', $this->protocols),
                "category.channels.$channelIndex.media" => 'required|in:' . implode(',', $this->mediaOptions),
            ], [], [
                "category.channels.$channelIndex.channel_id" => __('channel'),
                "category.channels.$channelIndex.stage" => __('stage'),
                "category.channels.$channelIndex.protocol" => __('protocol'),
                "category.channels.$channelIndex.media" => __('media'),
            ]);
        }
    }

    public function getChannelCount($categoryIndex)
    {
        return count($this->category['channels']);
    }

    public function render()
    {
        return view('livewire.app.reports.create-momently-report', [
            'channels' => Channel::where('status', '1')->get(),
        ]);
    }
}