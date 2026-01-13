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
                'downloads.id',
                'downloads.device_id',
                'downloads.year',
                'downloads.month',
                'downloads.count',
                'downloads.created_at',
                'devices.name as device_name',
                'devices.protocol',
                'devices.area as device_area',
            ])
            ->join('devices', 'downloads.device_id', '=', 'devices.id')
            ->orderBy('downloads.created_at', 'desc');

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

        $filename = __('Download history') . '-' . now()->format('dmY-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($query, $start, $end) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $headerRow = ['id', 'device_id', 'device_name', 'protocol', 'device_area', 'year', 'month', 'count', 'created_at'];
            fputcsv($handle, $headerRow);

            $query->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    $values = [
                        $r->id ?? '—',
                        $r->device_id ?? '—',
                        $r->device_name ?? '—',
                        $r->protocol ?? '—',
                        $r->device_area ?? '—',
                        $r->year ?? '—',
                        $r->month ?? '—',
                        $r->count ?? '—',
                        isset($r->created_at)
                            ? Carbon::parse($r->created_at)->format('Y-m-d H:i:s') . ' UTC-6'
                            : '—',
                    ];
                    fputcsv($handle, $values);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
