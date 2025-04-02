<?php

namespace App\Livewire\App\Reports;

use App\Mail\ReportGeneralCreatedMail;
use Livewire\Component;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\Stage;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class CreateHourlyReport extends Component
{
    public $stages;
    public $categories = [];
    public $protocols = ['HLS', 'DASH', 'HLS/DASH'];
    public $mediaOptions = ['AUDIO', 'VIDEO', 'AUDIO/VIDEO'];
    public $reportData = [
        'category' => '',
    ];

    public function mount()
    {
        $this->stages = Stage::where('status', '1')->get();
        $this->initializeDefaultCategories();
    }

    protected function initializeDefaultCategories()
    {
        $defaultCategories = ['CDN TELMEX', 'CDN CEF+', 'Stingray'];

        foreach ($defaultCategories as $category) {
            $this->categories[] = [
                'name' => $category,
                'channels' => [
                    $this->initializeChannel($category),
                ],
                'fixed' => true
            ];
        }
    }

    public function addCategory()
{
    $this->categories[] = [
        'name' => '',
        'channels' => [
            $this->initializeChannel(),
        ],
        'fixed' => false
    ];

    $this->reportData['category'] = ''; // Asegura que la nueva categoría pueda editarse
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
        $categoryName = $this->categories[$categoryIndex]['name'];
        $this->categories[$categoryIndex]['channels'][] = $this->initializeChannel($categoryName);
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

    public function saveReport()
    {
        try {
            $this->validateReportData();

            $categoryNames = implode(', ', array_column($this->categories, 'name'));

            $report = Report::create([
                'type' => __('Hourly'),
                'category' => $categoryNames,
                'reported_by' => Auth::user()->id,
                'status' => __('Reported'),
            ]);

            $categories = [];

            foreach ($this->categories as $category) {
                if (empty($category['channels'])) {
                    continue;
                }

                $categoryData = [
                    'name' => $category['name'],
                    'channels' => [],
                ];

                foreach ($category['channels'] as $channel) {
                    if (empty($channel['channel_id'])) {
                        continue;
                    }

                    $reportDetail = ReportDetail::create([
                        'report_id' => $report->id,
                        'subcategory' => $category['name'],
                        'channel_id' => $channel['channel_id'],
                        'protocol' => $channel['protocol'],
                        'stage_id' => $channel['stage'],
                        'media' => $channel['media'],
                        'description' => $channel['description'],
                    ]);

                    $channelData = Channel::find($reportDetail->channel_id);
                    $stageData = Stage::find($reportDetail->stage_id);

                    $categoryData['channels'][] = [
                        'channel_id' => $reportDetail->channel_id,
                        'number' => $channelData->number ?? 'N/A',
                        'name' => $channelData->name ?? 'Unknown',
                        'stage' => $stageData->name ?? 'Unknown',
                        'media' => $reportDetail->media,
                    ];
                }

                if (!empty($categoryData['channels'])) {
                    $categories[] = $categoryData;
                }
            }

            Mail::to(Auth::user()->email)->send(new ReportGeneralCreatedMail($report, $categories));

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('General report created successfully.'),
            ]);
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
        foreach ($this->categories as $index => $category) {
            $this->validate([
                "categories.$index.name" => 'required|string|max:255',
                "categories.$index.channels" => 'required|array|min:1',
            ], [], [
                "categories.$index.name" => __('category name'),
                "categories.$index.channels" => __('category channels'),
            ]);
        }
    }

    public function render()
    {
        foreach ($this->categories as $index => $category) {
            if (!isset($this->categories[$index]['fixed'])) {
                $this->categories[$index]['fixed'] = in_array($category['name'], ['CDN TELMEX', 'CDN CEF+', 'Stingray']);
            }
        }

        return view('livewire.app.reports.create-hourly-report', [
            'channels' => Channel::where('status', '1')->get(),
            'stingrayChannels' => Channel::where('status', '1')->where('category', 'Stingray')->get(),
        ]);
    }
}
