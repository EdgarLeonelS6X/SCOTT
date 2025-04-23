<?php

namespace App\Livewire\App\Reports\Edit;

use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\Stage;
use App\Models\Channel;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class EditHourlyReport extends Component
{
    public $report;
    public $categories = [];
    public $stages;
    public $protocols = ['HLS', 'DASH', 'HLS/DASH'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];
    protected $fixedCategories = ['CDN TELMEX', 'CDN CEF+', 'STINGRAY'];

    public function mount(Report $report)
    {
        $this->report = $report;
        $this->stages = Stage::where('status', '1')->get();

        $this->initializeFromReport();
    }

    protected function initializeFromReport()
    {
        $grouped = $this->report->reportDetails->groupBy('subcategory');

        foreach ($grouped as $subcategory => $details) {
            $this->categories[] = [
                'name' => $subcategory,
                'fixed' => in_array($subcategory, $this->fixedCategories),
                'channels' => $details->map(function ($detail) {
                    return [
                        'channel_id' => $detail->channel_id,
                        'stage' => $detail->stage_id,
                        'protocol' => $detail->protocol,
                        'media' => $detail->media,
                        'description' => $detail->description,
                    ];
                })->toArray(),
            ];
        }
    }

    public function addCategory()
    {
        $this->categories[] = [
            'name' => '',
            'channels' => [$this->initializeChannel()],
            'fixed' => false
        ];
    }

    public function removeCategory($index)
    {
        if (!isset($this->categories[$index]['fixed']) || !$this->categories[$index]['fixed']) {
            unset($this->categories[$index]);
            $this->categories = array_values($this->categories);
        }
    }

    public function addChannel($categoryIndex)
    {
        $this->categories[$categoryIndex]['channels'][] = $this->initializeChannel($this->categories[$categoryIndex]['name']);
    }

    public function removeChannel($categoryIndex, $channelIndex)
    {
        unset($this->categories[$categoryIndex]['channels'][$channelIndex]);
        $this->categories[$categoryIndex]['channels'] = array_values($this->categories[$categoryIndex]['channels']);
    }

    public function getChannelCount($categoryIndex)
    {
        if (!isset($this->categories[$categoryIndex])) {
            return 0;
        }

        return count($this->categories[$categoryIndex]['channels']);
    }

    protected function initializeChannel($category = '')
    {
        $defaultStage = '';
        if ($category === 'CDN TELMEX') {
            $defaultStage = Stage::where('name', 'CDN TELMEX')->first()->id ?? '';
        } elseif ($category === 'CDN CEF+') {
            $defaultStage = Stage::where('name', 'CDN CEF+')->first()->id ?? '';
        }

        return [
            'channel_id' => '',
            'stage' => $defaultStage,
            'protocol' => '',
            'media' => '',
            'description' => '',
        ];
    }

    public function updateCategoryName($index, $name)
    {
        if (isset($this->categories[$index])) {
            $this->categories[$index]['name'] = $name;
        }
    }

    public function updateReport()
    {
        try {
            $this->validateReportData();

            $categoryNames = implode(', ', array_column($this->categories, 'name'));

            $this->report->update([
                'category' => $categoryNames,
            ]);

            $this->report->reportDetails()->delete();

            foreach ($this->categories as $category) {
                foreach ($category['channels'] as $channel) {
                    ReportDetail::create([
                        'report_id' => $this->report->id,
                        'subcategory' => $category['name'],
                        'channel_id' => $channel['channel_id'],
                        'protocol' => $channel['protocol'],
                        'stage_id' => $channel['stage'],
                        'media' => $channel['media'],
                        'description' => $channel['description'],
                    ]);
                }
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => __('Updated!'),
                'text' => __('The hourly report was successfully updated.'),
            ]);

            return redirect()->route('reports.show', $this->report->id);
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
                'html' => '<b>' . __('Your report update contains errors:') . '</b><br><br>' . $errorMessages,
            ]);
        }
    }

    protected function validateReportData()
    {
        foreach ($this->categories as $index => $category) {
            $this->validate([
                "categories.$index.name" => 'required|string|max:255'
            ], [], [
                "categories.$index.name" => __('category name')
            ]);

            if (!in_array($category['name'], $this->fixedCategories) && empty($category['channels'])) {
                throw ValidationException::withMessages([
                    "categories.$index.channels" => __('New categories must have at least one channel.')
                ]);
            }

            foreach ($category['channels'] as $channelIndex => $channel) {
                $this->validate([
                    "categories.$index.channels.$channelIndex.channel_id" => 'required|exists:channels,id',
                    "categories.$index.channels.$channelIndex.stage" => 'required|exists:stages,id',
                    "categories.$index.channels.$channelIndex.protocol" => 'required|in:' . implode(',', $this->protocols),
                    "categories.$index.channels.$channelIndex.media" => 'required|in:' . implode(',', $this->mediaOptions),
                ], [], [
                    "categories.$index.channels.$channelIndex.channel_id" => __('channel'),
                    "categories.$index.channels.$channelIndex.stage" => __('stage'),
                    "categories.$index.channels.$channelIndex.protocol" => __('protocol'),
                    "categories.$index.channels.$channelIndex.media" => __('media'),
                ]);
            }
        }
    }

    public function render()
    {
        foreach ($this->categories as $index => $category) {
            if (!isset($this->categories[$index]['fixed'])) {
                $this->categories[$index]['fixed'] = in_array($category['name'], $this->fixedCategories);
            }
        }

        return view('livewire.app.reports.edit.edit-hourly-report', [
            'channels' => Channel::where('status', '1')->orderBy('number')->get(),
            'stingrayChannels' => Channel::where('status', '1')->where('category', 'Stingray Music')->orderBy('number')->get(),
        ]);
    }
}
