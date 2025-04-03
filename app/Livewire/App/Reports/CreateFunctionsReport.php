<?php

namespace App\Livewire\App\Reports;

use App\Mail\ReportFunctionsCreatedMail;
use Livewire\Component;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\ReportContentLoss;
use App\Models\Stage;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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
            'loss_periods' => [],
        ];
    }

    public function getPeriodsCount($categoryIndex)
    {
        $category = $this->categories[$categoryIndex] ?? null;

        if (!$category || $category['name'] !== 'CUTV') {
            return 0;
        }

        $totalPeriods = 0;

        foreach ($category['channels'] as $channel) {
            if (!isset($channel['loss_periods']) || !is_array($channel['loss_periods'])) {
                continue;
            }
            $totalPeriods += count($channel['loss_periods']);
        }

        return $totalPeriods;
    }

    public function addPeriod($categoryIndex, $channelIndex)
    {
        $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'][] = [
            'start_time' => '',
            'end_time' => '',
        ];
    }

    public function removePeriod($categoryIndex, $channelIndex, $periodIndex)
    {
        unset($this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'][$periodIndex]);
        $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods'] = array_values(
            $this->categories[$categoryIndex]['channels'][$channelIndex]['loss_periods']
        );
    }

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

            foreach ($this->categories as $category) {
                if (empty($category['channels'])) {
                    continue;
                }

                foreach ($category['channels'] as $channel) {
                    if ($category['name'] === 'CUTV' && empty($channel['loss_periods'])) {
                        throw ValidationException::withMessages([
                            'CUTV' => __('Each channel in CUTV must have at least one loss period.')
                        ]);
                    }

                    $reportDetail = ReportDetail::create([
                        'report_id' => $report->id,
                        'subcategory' => $category['name'],
                        'channel_id' => $channel['channel_id'],
                        'protocol' => $channel['protocol'] ?? null,
                        'stage_id' => $channel['stage'],
                        'media' => $channel['media'] ?? null,
                        'description' => $channel['description'] ?? null,
                    ]);

                    if ($category['name'] === 'CUTV') {
                        foreach ($channel['loss_periods'] as $period) {
                            $startTime = Carbon::parse($period['start_time']);
                            $endTime = Carbon::parse($period['end_time']);
                            $now = Carbon::now();

                            if (!$startTime || !$endTime) {
                                throw ValidationException::withMessages([
                                    'CUTV - loss_periods' => __('Start time and end time are required.')
                                ]);
                            }

                            if ($startTime->greaterThan($now) || $endTime->greaterThan($now)) {
                                throw ValidationException::withMessages([
                                    'CUTV - start_time' => __('Start and end times cannot be in the future.')
                                ]);
                            }

                            if ($endTime->lessThanOrEqualTo($startTime)) {
                                throw ValidationException::withMessages([
                                    'CUTV - end_time' => __('End time must be after start time.')
                                ]);
                            }

                            $duration = $startTime->diffInMinutes($endTime);

                            ReportContentLoss::create([
                                'report_detail_id' => $reportDetail->id,
                                'start_time' => $startTime,
                                'end_time' => $endTime,
                                'duration' => $duration,
                            ]);
                        }
                    }
                }
            }

            Mail::to(Auth::user()->email)->send(new ReportFunctionsCreatedMail($report, $this->categories));

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Function report created successfully.'),
            ]);
        } catch (ValidationException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => __('Error'),
                'html' => '<b>' . __('Your report log contains errors:') . '</b><br><br><ul>' . implode('', array_map(fn($msg) => "<li>• $msg</li>", array_flatten($e->errors()))) . '</ul>',
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
