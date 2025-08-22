<?php

namespace App\Livewire\App\Reports\Create;

use Livewire\Component;
use App\Models\Chromecast;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateChromecastReport extends Component
{
    public $reportData;

    public function mount()
    {
        $this->reportData = [
            'reviewed_by' => '',
            'chromecast' => [
                'status' => true, // toggle por defecto activado
                'description' => '',
            ],
        ];
    }

    public function saveReport()
    {
        // Validación
        $this->validate([
            'reportData.chromecast.description' => 'nullable|string|max:2000',
        ]);

        try {
            $report = Report::create([
                'category' => __('Chromecast'),
                'type' => __('Functions'),
                'reported_by' => Auth::id(),
                'status' => 'Reported',
            ]);

            Chromecast::create([
                'report_id' => $report->id,
                'description' => $this->reportData['chromecast']['description'],
            ]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => __('Well done!'),
                'text' => __('Chromecast report created successfully.'),
            ]);

            $this->dispatch('reportCreated');

        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.app.reports.create.create-chromecast-report');
    }
}
