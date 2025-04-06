<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class ReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
    public $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports;
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Category',
            'Report Type',
            'Status',
            'Created At',
            'Reported By',
            'Reviewed By',
            'Attended By',
            'Duration',
            'Associated Channels',
            'Protocol',
            'Media',
            'Description',
            'Content Losses',
        ];
    }

    public function map($report): array
    {
        $channels = $report->reportDetails->map(function ($detail) {
            $channelNumber = $detail->channel->number ?? 'No Number';
            $channelName = $detail->channel->name ?? 'Unknown Channel';
            return "{$channelNumber} {$channelName}";
        })->join(', ');

        $protocols = $report->reportDetails->map(function ($detail) {
            $channelNumber = $detail->channel->number ?? 'No Number';
            $channelName = $detail->channel->name ?? 'Unknown Channel';
            $protocol = $detail->protocol;
            return "{$channelNumber} {$channelName}: {$protocol}";
        })->join("\n");

        $media = $report->reportDetails->map(function ($detail) {
            $channelNumber = $detail->channel->number ?? 'No Number';
            $channelName = $detail->channel->name ?? 'Unknown Channel';
            $media = $detail->media;
            return "{$channelNumber} {$channelName}: {$media}";
        })->join("\n");

        $descriptions = $report->reportDetails->map(function ($detail) {
            $channelNumber = $detail->channel->number ?? 'No Number';
            $channelName = $detail->channel->name ?? 'Unknown Channel';
            $description = $detail->description;
            return "{$channelNumber} {$channelName}: {$description}";
        })->join("\n");

        $losses = $report->reportDetails
            ->flatMap(function ($detail) {
                $channelName = $detail->channel->name ?? 'Unknown Channel';
                $channelNumber = $detail->channel->number ?? 'No Number';
                $subcategory = $detail->subcategory ?? 'No Subcategory';

                if ($detail->reportContentLosses->isEmpty()) {
                    return ["Subcategory: {$subcategory}, Channel: {$channelNumber} {$channelName}: "];
                }

                return $detail->reportContentLosses->map(function ($loss) use ($channelName, $channelNumber, $subcategory) {
                    $start = \Carbon\Carbon::parse($loss->start_time)->format('Y/m/d h:i A');
                    $end = \Carbon\Carbon::parse($loss->end_time)->format('Y/m/d h:i A');
                    return "Subcategory: {$subcategory}, Channel: {$channelNumber} {$channelName}, Start: {$start}, End: {$end}, Duration: {$loss->duration} min";
                });
            })->join("\n");

        return [
            $report->id,
            $report->category,
            $report->type,
            $report->status,
            $report->created_at->format('Y/m/d h:i A'),
            $report->reportedBy->name ?? 'N/A',
            $report->reviewedBy->name ?? '',
            $report->attendedBy->name ?? '',
            $report->duration ?? '',
            $channels,
            $protocols,
            $media,
            $descriptions,
            $losses,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
