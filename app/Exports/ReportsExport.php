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
            'Updated At',
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
        $channels = $report->reportDetails
            ->groupBy(function ($detail) use ($report) {
                return $report->type === 'Momentary'
                    ? ($report->category ?? 'Unknown Category')
                    : ($detail->subcategory ?? 'Unknown Subcategory');
            })
            ->map(function ($details, $group) use ($report) {
                if ($details->isEmpty()) {
                    return "Category: {$group} - All channels verified correctly.";
                }

                $channelsList = $details->map(function ($detail) {
                    $number = $detail->channel->number ?? 'No Number';
                    $name = $detail->channel->name ?? 'Unknown Channel';
                    return "{$number} {$name}";
                })->join(', ');

                return "Category: {$group} - {$channelsList}";
            })
            ->join("\n");

        $protocols = $report->reportDetails
            ->groupBy(function ($detail) use ($report) {
                return $report->type === 'Momentary'
                    ? ($report->category ?? 'Unknown Category')
                    : ($detail->subcategory ?? 'Unknown Subcategory');
            })
            ->map(function ($details, $group) {
                $channelsList = $details->map(function ($detail) {
                    $number = $detail->channel->number ?? 'No Number';
                    $name = $detail->channel->name ?? 'Unknown Channel';
                    $protocol = $detail->protocol ?? 'No Protocol';
                    return "{$number} {$name}: {$protocol}";
                })->join(', ');

                return "Category: {$group} - {$channelsList}";
            })
            ->join("\n");

        $media = $report->reportDetails
            ->groupBy(function ($detail) use ($report) {
                return $report->type === 'Momentary'
                    ? ($report->category ?? 'Unknown Category')
                    : ($detail->subcategory ?? 'Unknown Subcategory');
            })
            ->map(function ($details, $group) use ($report) {
                $channelsList = $details->map(function ($detail) use ($report) {
                    $number = $detail->channel->number ?? 'No Number';
                    $name = $detail->channel->name ?? 'Unknown Channel';
                    $subcategory = $detail->subcategory ?? null;
                    $media = $detail->media;

                    if ($report->type === 'Functions' && in_array($subcategory, ['EPG', 'PC']) && (is_null($media) || $media == '')) {
                        $media = 'Not Applicable';
                    } else {
                        $media = $media ?? 'No Media';
                    }

                    return "{$number} {$name}: {$media}";
                })->join(', ');

                return "Category: {$group} - {$channelsList}";
            })
            ->join("\n");

        $descriptions = $report->reportDetails
            ->map(function ($detail) use ($report) {
                $number = $detail->channel->number ?? 'No Number';
                $name = $detail->channel->name ?? 'Unknown Channel';

                $categoryOrSubcategory = $report->type === 'Momentary'
                    ? ($report->category ?? 'Unknown Category')
                    : ($detail->subcategory ?? 'Unknown Subcategory');

                if ($report->type === 'Functions' && $categoryOrSubcategory === 'CUTV') {
                    $description = 'Not Applicable';
                } else {
                    $description = $detail->description ?? 'No Applicable';
                }

                return "Category: {$categoryOrSubcategory} - {$number} {$name}: {$description}";
            })
            ->join("\n");
        $losses = '';
        if ($report->type === 'Functions') {
            $losses = $report->reportDetails
                ->where('subcategory', 'CUTV')
                ->flatMap(function ($detail) {
                    $channelNumber = $detail->channel->number ?? 'No Number';
                    $channelName = $detail->channel->name ?? 'Unknown Channel';
                    $subcategory = $detail->subcategory ?? 'No Subcategory';

                    if ($detail->reportContentLosses->isEmpty()) {
                        return [];
                    }

                    return $detail->reportContentLosses->map(function ($loss) use ($channelNumber, $channelName, $subcategory) {
                        $start = \Carbon\Carbon::parse($loss->start_time);
                        $end = \Carbon\Carbon::parse($loss->end_time);
                        $diff = $start->diff($end);
                        $days = (int)$diff->format('%a');
                        $hours = (int)$diff->format('%H');
                        $minutes = (int)$diff->format('%I');

                        $duration = '';
                        if ($days > 0) {
                            $duration .= "{$days}d ";
                        }
                        if ($hours > 0 || $days > 0) {
                            $duration .= "{$hours}h ";
                        }
                        $duration .= "{$minutes}m";

                        return "Subcategory: {$subcategory}, Channel: {$channelNumber} {$channelName}, Start: {$start->format('d/m/Y H:i')}, End: {$end->format('d/m/Y H:i')}, Duration: {$duration}";
                    });
                })
                ->join("\n");
        }

        return [
            $report->id,
            $report->category,
            $report->type,
            $report->status,
            $report->created_at->format('Y/m/d h:i A'),
            $report->updated_at->format('Y/m/d h:i A'),
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
