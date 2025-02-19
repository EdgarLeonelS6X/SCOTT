<?php

namespace App\Livewire\App\Reports;

use App\Mail\ReportFunctionsCreatedMail;
use Livewire\Component;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\Stage;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class CreateFunctionsReport extends Component
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
        $this->categories = [
            [
                'name' => __('RESTART'),
                'channels' => [],
            ],
            [
                'name' => __('CUTV'),
                'channels' => [],
            ],
            [
                'name' => __('EPG'),
                'channels' => [],
            ],
            [
                'name' => __('PC'),
                'channels' => [],
            ],
        ];
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

    // Guarda el reporte
    public function saveReport()
    {
        try {
            $categoryNames = implode(', ', array_column($this->categories, 'name'));

            $report = Report::create([
                'type' => __('Functions'),
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

            Mail::to(Auth::user()->email)->send(new ReportFunctionsCreatedMail($report, $categories));

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Function report created successfully.'),
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

    public function render()
    {
        return view('livewire.app.reports.create-functions-report', [
            'channels' => Channel::where('status', '1')->get(),
        ]);
    }
}
