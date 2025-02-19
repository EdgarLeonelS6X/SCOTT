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

    public function mount()
    {
        $this->stages = Stage::where('status', '1')->get();
        $this->initializeDefaultCategories();
    }

    protected function initializeDefaultCategories()
    {
        $defaultCategories = ['CDN TELMEX', 'CDN CEF+'];

        foreach ($defaultCategories as $category) {
            $this->categories[] = [
                'name' => $category,
                'channels' => [],
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
        ];
    }

    public function removeCategory($index)
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
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

            $categoryNames = implode(', ', array_column($this->categories, 'name'));

            $report = Report::create([
                'type' => __('Hourly'),
                'category' => $categoryNames,
                'reported_by' => Auth::user()->id,
                'status' => __('Reported'),
            ]);

            $categories = [];

            foreach ($this->categories as $category) {
                $categoryData = [
                    'name' => $category['name'],
                    'channels' => [],
                ];

                foreach ($category['channels'] as $channel) {
                    $reportDetail = ReportDetail::create([
                        'report_id' => $report->id,
                        'subcategory' => $category['name'],
                        'channel_id' => $channel['channel_id'],
                        'protocol' => $channel['protocol'],
                        'stage_id' => $channel['stage'],
                        'media' => $channel['media'],
                        'description' => $channel['description'],
                    ]);

                    $categoryData['channels'][] = [
                        'channel_id' => $reportDetail->channel_id,
                        'name' => Channel::find($reportDetail->channel_id)->name ?? 'Unknown',
                        'stage' => Stage::find($reportDetail->stage_id)->name ?? 'Unknown',
                        'media' => $reportDetail->media,
                    ];
                }

                $categories[] = $categoryData;
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
            ], [], [
                "categories.$index.name" => __('category name'),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.app.reports.create-hourly-report', [
            'channels' => Channel::where('status', '1')->get(),
        ]);
    }
}
