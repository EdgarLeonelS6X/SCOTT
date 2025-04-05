<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports->map(function ($report) {
            return [
                'Folio' => $report->id,
                'Categoría' => $report->category,
                'Tipo' => $report->type,
                'Estado' => $report->status,
                'Fecha' => $report->created_at->format('d/m/Y h:i A'),
                'Reportado por' => $report->reportedBy->name,
                'Canales' => $report->reportDetails->pluck('channel.name')->join(', '),
                'Pérdidas de contenido' => $report->reportDetails
                    ->flatMap->reportContentLosses
                    ->map(function ($loss) {
                        return "Inicio: {$loss->start_time}, Fin: {$loss->end_time}, Duración: {$loss->duration} min";
                    })->join(" | "),
            ];
        });
    }
}
