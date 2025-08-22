<?php

namespace App\Livewire\App\Reports\Create;

use App\Mail\Reports\ReportCreatedMail;
use App\Models\ChannelTest;
use Livewire\Component;
use App\Models\Report;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class CreateProfileReport extends Component
{
    public $reportData;

    public function mount()
    {
        $this->reportData = [
            'title' => '',
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
            'profiles' => [
                ['name' => 'HIGH', 'value' => ''],
            ],
        ];
    }

    public function addProfile($channelIndex)
    {
        $this->reportData['channels'][$channelIndex]['profiles'][] = [
            'name' => '',
            'value' => '',
        ];
    }

    public function removeProfile($channelIndex, $profileIndex)
    {
        unset($this->reportData['channels'][$channelIndex]['profiles'][$profileIndex]);
        $this->reportData['channels'][$channelIndex]['profiles'] = array_values($this->reportData['channels'][$channelIndex]['profiles']);
    }

    public function saveReport()
    {
        try {
            $this->validateReportData();

            // Validar duplicados
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

            // Crear el reporte principal
            $report = Report::create([
                'title' => $this->reportData['title'],
                'type' => 'Profiles',
                'duration' => null,
                'reported_by' => Auth::user()->id,
                'reviewed_by' => $this->reportData['reviewed_by'],
                'attended_by' => null,
                'status' => '',
            ]);

            // Guardar cada canal con sus perfiles
            foreach ($this->reportData['channels'] as $channel) {
                // Mapear perfiles a columnas high/medium/low
                $profiles = collect($channel['profiles'])->pluck('value', 'name')->toArray();

                ChannelTest::create([
                    'report_id' => $report->id,
                    'channel_id' => $channel['channel_id'],
                    'user_id' => Auth::user()->id,
                    'high' => $profiles['HIGH'] ?? null,
                    'medium' => $profiles['MEDIUM'] ?? null,
                    'low' => $profiles['LOW'] ?? null,
                ]);
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Video profile test report created successfully.')
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
            'reportData.title' => 'required|string|max:255',
            'reportData.reviewed_by' => 'required|string|max:255',
            'reportData.channels' => 'required|array|min:1',
        ], [], [
            'reportData.title' => __('title'),
            'reportData.reviewed_by' => __('reviewed by'),
            'reportData.channels' => __('channels')
        ]);

        foreach ($this->reportData['channels'] as $cIndex => $channel) {
            $this->validate([
                "reportData.channels.$cIndex.channel_id" => 'required|exists:channels,id',
            ], [], [
                "reportData.channels.$cIndex.channel_id" => __('channel'),
            ]);

            foreach ($channel['profiles'] as $pIndex => $profile) {
                $this->validate([
                    "reportData.channels.$cIndex.profiles.$pIndex.name" => 'required|string|max:50',
                    "reportData.channels.$cIndex.profiles.$pIndex.value" => 'nullable|string|max:255',
                ], [], [
                    "reportData.channels.$cIndex.profiles.$pIndex.name" => __('profile name'),
                    "reportData.channels.$cIndex.profiles.$pIndex.value" => __('profile value'),
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.app.reports.create.create-profile-report', [
            'channels' => Channel::where('status', '1')
                ->orderBy('number')
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'number' => $c->number,
                    'name' => $c->name,
                    'image' => $c->image,
                    'profiles' => [
                        'high' => $c->high ?? null,
                        'medium' => $c->medium ?? null,
                        'low' => $c->low ?? null,
                    ]
                ]),

        ]);
    }
}
