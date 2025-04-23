<?php

namespace App\Livewire\App\Reports\Edit;

use Livewire\Component;
use App\Models\Report;
use App\Models\Stage;
use App\Models\Channel;

class EditFunctionsReport extends Component
{
    public $report;
    public $stages;
    public $categories = [];
    public $protocols = ['HLS', 'DASH', 'HLS/DASH'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];
    public $allowedStages = ['CDN TELMEX', 'CDN CEF+', 'CDN TELMEX/CEF+'];

    public function mount(Report $report)
    {
        $this->report = Report::with('reportDetails.reportContentLosses')->findOrFail($report->id);
        $this->stages = Stage::whereIn('name', $this->allowedStages)->pluck('id', 'name')->toArray();
        $this->loadReportData();
    }

    protected function loadReportData()
    {
        $grouped = $this->report->reportDetails ? $this->report->reportDetails->groupBy('subcategory') : collect();

        foreach (['RESTART', 'CUTV', 'EPG', 'PC'] as $name) {
            $channels = [];

            foreach ($grouped->get($name, []) as $detail) {
                $losses = [];

                if ($name === 'CUTV') {
                    foreach ($detail->reportContentLosses()->get() as $loss) {
                        $losses[] = [
                            'start_time' => \Carbon\Carbon::parse($loss->start_time)->format('Y-m-d\TH:i'),
                            'end_time' => \Carbon\Carbon::parse($loss->end_time)->format('Y-m-d\TH:i'),
                        ];
                    }
                }

                if ($name === 'CUTV' && empty($losses)) {
                    $losses[] = ['start_time' => '', 'end_time' => ''];
                }

                $channels[] = [
                    'channel_id' => $detail->channel_id,
                    'stage' => $detail->stage_id,
                    'protocol' => $detail->protocol,
                    'media' => $detail->media,
                    'description' => $detail->description,
                    'loss_periods' => $losses,
                ];
            }

            $this->categories[] = [
                'name' => $name,
                'channels' => $channels,
            ];
        }
    }

    public function addChannel($categoryIndex)
    {
        $this->categories[$categoryIndex]['channels'][] = $this->initializeChannel();
    }

    public function removeChannel($categoryIndex, $channelIndex)
    {
        unset($this->categories[$categoryIndex]['channels'][$channelIndex]);
        $this->categories[$categoryIndex]['channels'] = array_values($this->categories[$categoryIndex]['channels']);
    }

    public function addPeriod($categoryIndex, $channelIndex)
    {
        $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'][] = [
            'start_time' => '',
            'end_time' => '',
        ];
    }

    public function updateLossPeriods($categoryIndex, $channelIndex, $newPeriods)
    {
        $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'] = $newPeriods;
    }

    public function removePeriod($categoryIndex, $channelIndex, $periodIndex)
    {
        unset($this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'][$periodIndex]);
        $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'] = array_values(
            $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods']
        );
    }

    protected function initializeChannel()
    {
        return [
            'channel_id' => '',
            'stage' => '',
            'protocol' => '',
            'media' => '',
            'description' => '',
            'loss_periods' => [],
        ];
    }

    protected function getChannelsByCategory()
    {
        $allChannels = Channel::where('status', '1')
            ->orderBy('number')
            ->get();

        $channelsByCategory = [];

        foreach ($this->categories as $category) {
            $categoryName = $category['name'];

            if (in_array($categoryName, ['RESTART', 'CUTV'])) {
                $channelsByCategory[$categoryName] = $allChannels->filter(
                    fn($channel) => $channel->category === 'RESTART/CUTV'
                );
            } else {
                $channelsByCategory[$categoryName] = $allChannels;
            }
        }

        return $channelsByCategory;
    }

    public function render()
    {
        return view('livewire.app.reports.edit.edit-functions-report', [
            'channelsByCategory' => $this->getChannelsByCategory(),
        ]);
    }
}
