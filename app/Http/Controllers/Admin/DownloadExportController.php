<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DownloadExportController extends Controller
{
    public function historyCsv(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $query = DB::table('downloads')
            ->select([
                'devices.id as device_id',
                'devices.name as device_name',
                'devices.protocol',
                'devices.area as device_area',
                'downloads.year',
                'downloads.month',
                DB::raw('SUM(downloads.count) as device_total'),
            ])
            ->join('devices', 'downloads.device_id', '=', 'devices.id')
            ->groupBy('devices.id', 'devices.name', 'devices.protocol', 'devices.area', 'downloads.year', 'downloads.month')
            ->orderBy('downloads.year', 'desc')
            ->orderBy('downloads.month', 'desc')
            ->orderBy('devices.name', 'asc');

        if ($start && $end) {
            try {
                $s = Carbon::createFromFormat('Y-m-d', $start)->startOfDay();
                $e = Carbon::createFromFormat('Y-m-d', $end)->endOfDay();
                $query->whereBetween('downloads.created_at', [$s, $e]);
            } catch (\Throwable $e) {
            }
        }

        $auth = Auth::user();
        if ($auth && $auth->id !== 1) {
            if ($viewerArea = $auth->area) {
                $query->where('devices.area', $viewerArea);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $filename = 'download-history-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($query, $start, $end) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [__('Report'), __('History of monthly downloads')]);
            fputcsv($handle, [__('Generated at'), now()->toDateTimeString()]);
            if ($start || $end) {
                fputcsv($handle, [__('Range start'), $start ?: '—']);
                fputcsv($handle, [__('Range end'), $end ?: '—']);
            } else {
                fputcsv($handle, [__('Range'), __('All time')]);
            }
            fputcsv($handle, []);
            fputcsv($handle, []);

            fputcsv($handle, [
                __('Device'), __('Protocol'), __('Year'), __('Month'), __('Total Downloads')
            ]);

            $currentYm = null;
            $subtotal = 0;
            $grandTotal = 0;

            $query->chunk(500, function ($rows) use ($handle, &$currentYm, &$subtotal, &$grandTotal) {
                foreach ($rows as $r) {
                    $ym = sprintf('%04d-%02d', $r->year, $r->month);
                    $monthName = '';
                    try {
                        $monthName = \Carbon\Carbon::createFromFormat('!m', $r->month)->locale(app()->getLocale())->translatedFormat('F');
                    } catch (\Throwable $e) {
                        $monthName = (string) $r->month;
                    }

                    if ($currentYm !== null && $currentYm !== $ym) {
                        fputcsv($handle, ['', '', '', __('Subtotal for') . ' ' . $currentYm, $subtotal]);
                        $subtotal = 0;
                    }

                    fputcsv($handle, [
                        $r->device_name,
                        $r->protocol,
                        $r->year,
                        $monthName,
                        $r->device_total,
                    ]);

                    $currentYm = $ym;
                    $subtotal += (int) $r->device_total;
                    $grandTotal += (int) $r->device_total;
                }
            });

            if ($currentYm !== null) {
                fputcsv($handle, ['', '', '', __('Subtotal for') . ' ' . $currentYm, $subtotal]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['', '', '', __('Grand total'), $grandTotal]);

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
