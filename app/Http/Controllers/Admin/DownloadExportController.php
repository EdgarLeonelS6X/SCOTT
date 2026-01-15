<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DownloadExportController extends Controller
{
    public function historyCSV(Request $request)
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

        $filename = __('Download history') . ' - ' . now()->format(format: 'dmY-His') . '.csv';

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

    public function historyData(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $deviceId = $request->query('device_id');

        $data = [
            'year' => $year,
            'device_id' => $deviceId,
            'devices' => [],
            'period_labels' => [],
            'download_rows' => [],
            'summary' => [
                'total' => 0,
                'average' => 0,
                'top_month_label' => null,
                'top_month_value' => 0,
            ],
        ];

        try {
            $s = Carbon::create($year, 1, 1)->startOfDay();
            $e = Carbon::create($year, 12, 31)->endOfDay();

            $period = CarbonPeriod::create($s->copy()->startOfMonth(), '1 month', $e->copy()->startOfMonth());
            $months = [];
            foreach ($period as $m) {
                $months[] = $m->format('Y-m');
            }

            $auth = Auth::user();
            $areaFilter = null;
            if ($auth && $auth->id !== 1) {
                if ($viewerArea = $auth->area) {
                    $areaFilter = $viewerArea;
                } else {
                    \Log::info('historyData: User not admin, no area assigned, returning empty', ['user_id' => $auth->id]);
                    return response()->json($data);
                }
            }

            \Log::info('historyData: Starting query', [
                'year' => $year,
                'device_id' => $deviceId,
                'area_filter' => $areaFilter,
                'user_id' => $auth?->id,
                'is_admin' => $auth?->id === 1,
                'months' => $months,
            ]);

            $rowsQuery = DB::table('downloads')
                ->selectRaw('downloads.device_id, downloads.month as month, downloads.year as year, SUM(downloads.count) as total, devices.name')
                ->join('devices', 'downloads.device_id', '=', 'devices.id')
                ->where('downloads.year', $year);

            if ($areaFilter) {
                $rowsQuery->where('devices.area', $areaFilter);
            }

            if ($deviceId) {
                $rowsQuery->where('downloads.device_id', $deviceId);
            }

            $rows = $rowsQuery->groupBy('downloads.device_id', 'downloads.month', 'downloads.year', 'devices.name')
                ->orderBy('devices.name')
                ->get();

            \Log::info('historyData: Rows query result', [
                'count' => $rows->count(),
                'sample' => $rows->take(3)->toArray(),
            ]);

            $downloadsQuery = DB::table('downloads')
                ->select([
                    'downloads.id',
                    'downloads.device_id',
                    'devices.name as device_name',
                    'devices.protocol',
                    'devices.area as device_area',
                    'downloads.year',
                    'downloads.month',
                    'downloads.count',
                    'downloads.created_at',
                ])
                ->join('devices', 'downloads.device_id', '=', 'devices.id')
                ->where('downloads.year', $year)
                ->orderBy('downloads.created_at', 'desc');

            if ($areaFilter) {
                $downloadsQuery->where('devices.area', $areaFilter);
            }

            if ($deviceId) {
                $downloadsQuery->where('downloads.device_id', $deviceId);
            }

            $downloadRowsCollection = $downloadsQuery->get();

            \Log::info('historyData: Download rows query result', [
                'count' => $downloadRowsCollection->count(),
                'sample' => $downloadRowsCollection->take(3)->toArray(),
            ]);

            $downloadRows = $downloadRowsCollection->map(function ($r) {
                return [
                    'id' => $r->id,
                    'device_id' => $r->device_id,
                    'device_name' => $r->device_name,
                    'protocol' => $r->protocol,
                    'device_area' => $r->device_area ?? '',
                    'year' => $r->year,
                    'month' => $r->month,
                    'count' => $r->count,
                    'created_at' => isset($r->created_at) ? Carbon::parse($r->created_at)->format('Y-m-d H:i:s') : null,
                ];
            })->toArray();

            $data['download_rows'] = $downloadRows;

            $devices = [];
            foreach ($rows as $r) {
                $did = $r->device_id;
                if (!isset($devices[$did])) {
                    $devices[$did] = [
                        'id' => $did,
                        'name' => $r->name,
                        'image' => $r->image ?? null,
                        'months' => array_fill(0, count($months), 0),
                    ];
                }
                $monthNum = intval($r->month);
                $index = $monthNum - 1;
                if ($index >= 0 && $index < count($months)) {
                    $devices[$did]['months'][$index] = (int)$r->total;
                }
            }

            $devicesList = [];
            foreach ($devices as $dev) {
                $counts = $dev['months'];
                $total = array_sum($counts);
                $avg = count($counts) ? round($total / count($counts), 2) : 0;
                $topIndex = array_search(max($counts), $counts);
                $topLabel = isset($months[$topIndex]) ? Carbon::createFromFormat('Y-m', $months[$topIndex])->format('M Y') : null;
                $topValue = $counts[$topIndex] ?? 0;

                $devicesList[] = [
                    'id' => $dev['id'],
                    'name' => $dev['name'],
                    'image' => $dev['image'] ?? null,
                    'counts' => $counts,
                    'total' => $total,
                    'average' => $avg,
                    'top_month_label' => $topLabel,
                    'top_month_value' => $topValue,
                ];
            }

            foreach ($devicesList as &$dev) {
                $counts = $dev['counts'];
                $max = max($counts) ?: 1;
                $w = 260; $h = 48; $pad = 6;
                $pts = [];
                $n = count($counts);
                for ($i = 0; $i < $n; $i++) {
                    $x = $pad + ($i * ($w - $pad * 2) / max(1, $n - 1));
                    $y = $h - $pad - (($counts[$i] / $max) * ($h - $pad * 2));
                    $pts[] = round($x,1) . ',' . round($y,1);
                }
                $points = implode(' ', $pts);
                $dev['sparkline'] = '<svg width="' . $w . '" height="' . $h . '" xmlns="http://www.w3.org/2000/svg">'
                    . '<polyline fill="none" stroke="#0f6fec" stroke-width="2" points="' . $points . '"/>'
                    . '</svg>';
            }
            unset($dev);

            $overallTotal = array_sum(array_map(fn($d) => $d['total'], $devicesList));
            $monthsCount = count($months) ?: 1;
            $overallAvg = round($overallTotal / $monthsCount, 2);

            $monthlyTotals = array_fill(0, $monthsCount, 0);
            foreach ($devicesList as $d) {
                foreach ($d['counts'] as $i => $c) {
                    $monthlyTotals[$i] += $c;
                }
            }
            $overallTopIndex = array_search(max($monthlyTotals), $monthlyTotals);
            $overallTopLabel = isset($months[$overallTopIndex]) ? Carbon::createFromFormat('Y-m', $months[$overallTopIndex])->format('M Y') : null;
            $overallTopValue = $monthlyTotals[$overallTopIndex] ?? 0;

            $data['devices'] = $devicesList;
            $data['period_labels'] = array_map(function ($m) { return Carbon::createFromFormat('Y-m', $m)->format('M Y'); }, $months);
            $data['summary'] = [
                'total' => $overallTotal,
                'average' => $overallAvg,
                'top_month_label' => $overallTopLabel,
                'top_month_value' => $overallTopValue,
            ];

            \Log::info('historyData: Final response prepared', [
                'devices_count' => count($data['devices']),
                'download_rows_count' => count($data['download_rows']),
                'summary_total' => $data['summary']['total'],
            ]);
        } catch (\Throwable $e) {
            \Log::error('historyData exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $data['devices'] = [];
            $data['period_labels'] = [];
        }

        return response()->json($data);
    }

    public function historyPDF(Request $request)
    {
        $charts = $request->input('charts', []);
        $year = $request->input('year', date('Y'));
        $deviceId = $request->input('device_id');

        $monthly = $charts['monthly'] ?? null;
        $pie = $charts['pie'] ?? null;

        $data = [
            'monthlyImage' => $monthly,
            'pieImage' => $pie,
            'year' => $year,
            'device_id' => $deviceId,
            'devices' => [],
            'period_labels' => [],
            'download_rows' => [],
            'summary' => [
                'total' => 0,
                'average' => 0,
                'top_month_label' => null,
                'top_month_value' => 0,
            ],
        ];

        $prefetched = $request->input('data');
        if ($prefetched) {
            try {
                $pd = is_string($prefetched) ? json_decode($prefetched, true) : $prefetched;
                if (is_array($pd)) {
                    $data['download_rows'] = $pd['download_rows'] ?? [];
                    $data['devices'] = $pd['devices'] ?? [];
                    $data['period_labels'] = $pd['period_labels'] ?? [];
                    $data['summary'] = $pd['summary'] ?? $data['summary'];

                    \Log::info('PDF prefetched data received', [
                        'year' => $year,
                        'device_id' => $deviceId,
                        'download_rows_count' => count($data['download_rows']),
                        'devices_count' => count($data['devices']),
                        'has_summary' => !empty($data['summary']),
                        'has_period_labels' => !empty($data['period_labels']),
                    ]);
                }
            } catch (\Throwable $_e) {
                \Log::error('PDF prefetch JSON decode error', ['error' => $_e->getMessage()]);
            }
        } else {
            try {
                $s = Carbon::create($year, 1, 1)->startOfDay();
                $e = Carbon::create($year, 12, 31)->endOfDay();

                $period = CarbonPeriod::create($s->copy()->startOfMonth(), '1 month', $e->copy()->startOfMonth());
                $months = [];
                foreach ($period as $m) {
                    $months[] = $m->format('Y-m');
                }

                $rows = DB::table('downloads')
                    ->selectRaw('downloads.device_id, downloads.month as month, downloads.year as year, SUM(downloads.count) as total, devices.name')
                    ->join('devices', 'downloads.device_id', '=', 'devices.id')
                    ->where('downloads.year', $year)
                    ->groupBy('downloads.device_id', 'downloads.month', 'downloads.year', 'devices.name')
                    ->orderBy('devices.name')
                    ->get();

                $downloadsQuery = DB::table('downloads')
                    ->select([
                        'downloads.id',
                        'downloads.device_id',
                        'devices.name as device_name',
                        'devices.protocol',
                        'devices.area as device_area',
                        'downloads.year',
                        'downloads.month',
                        'downloads.count',
                        'downloads.created_at',
                    ])
                    ->join('devices', 'downloads.device_id', '=', 'devices.id')
                    ->where('downloads.year', $year)
                    ->orderBy('downloads.created_at', 'desc');

                $auth = Auth::user();
                if ($auth && $auth->id !== 1) {
                    if ($viewerArea = $auth->area) {
                        $downloadsQuery->where('devices.area', $viewerArea);
                    } else {
                        $downloadsQuery->whereRaw('1 = 0');
                    }
                }

                if ($deviceId) {
                    $downloadsQuery->where('downloads.device_id', $deviceId);
                }

                $downloadRows = $downloadsQuery->get()->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'device_id' => $r->device_id,
                        'device_name' => $r->device_name,
                        'protocol' => $r->protocol,
                        'device_area' => $r->device_area ?? '',
                        'year' => $r->year,
                        'month' => $r->month,
                        'count' => $r->count,
                        'created_at' => isset($r->created_at) ? Carbon::parse($r->created_at)->format('Y-m-d H:i:s') : null,
                    ];
                })->toArray();

                $data['download_rows'] = $downloadRows;

                $devices = [];
                foreach ($rows as $r) {
                    $did = $r->device_id;
                    if (!isset($devices[$did])) {
                        $devices[$did] = [
                            'id' => $did,
                            'name' => $r->name,
                            'image' => $r->image ?? null,
                            'months' => array_fill(0, count($months), 0),
                        ];
                    }
                    $monthNum = intval($r->month);
                    $index = $monthNum - 1;
                    if ($index >= 0 && $index < count($months)) {
                        $devices[$did]['months'][$index] = (int)$r->total;
                    }
                }

                $devicesList = [];
                foreach ($devices as $dev) {
                    $counts = $dev['months'];
                    $max = max($counts) ?: 1;
                    $w = 260; $h = 48; $pad = 6;
                    $pts = [];
                    $n = count($counts);
                    for ($i = 0; $i < $n; $i++) {
                        $x = $pad + ($i * ($w - $pad * 2) / max(1, $n - 1));
                        $y = $h - $pad - (($counts[$i] / $max) * ($h - $pad * 2));
                        $pts[] = round($x,1) . ',' . round($y,1);
                    }
                    $points = implode(' ', $pts);
                    $svg = '<svg width="' . $w . '" height="' . $h . '" xmlns="http://www.w3.org/2000/svg">'
                        . '<polyline fill="none" stroke="#0f6fec" stroke-width="2" points="' . $points . '"/>'
                        . '</svg>';

                    $total = array_sum($counts);
                    $avg = $n ? round($total / $n, 2) : 0;
                    $topIndex = array_search(max($counts), $counts);
                    $topLabel = isset($months[$topIndex]) ? Carbon::createFromFormat('Y-m', $months[$topIndex])->format('M Y') : null;
                    $topValue = $counts[$topIndex] ?? 0;

                    $devicesList[] = [
                        'id' => $dev['id'],
                        'name' => $dev['name'],
                        'image' => $dev['image'] ?? null,
                        'counts' => $counts,
                        'total' => $total,
                        'average' => $avg,
                        'top_month_label' => $topLabel,
                        'top_month_value' => $topValue,
                        'sparkline' => $svg,
                    ];
                }

                $overallTotal = array_sum(array_map(fn($d) => $d['total'], $devicesList));
                $monthsCount = count($months) ?: 1;
                $overallAvg = round($overallTotal / $monthsCount, 2);

                $monthlyTotals = array_fill(0, $monthsCount, 0);
                foreach ($devicesList as $d) {
                    foreach ($d['counts'] as $i => $c) {
                        $monthlyTotals[$i] += $c;
                    }
                }
                $overallTopIndex = array_search(max($monthlyTotals), $monthlyTotals);
                $overallTopLabel = isset($months[$overallTopIndex]) ? Carbon::createFromFormat('Y-m', $months[$overallTopIndex])->format('M Y') : null;
                $overallTopValue = $monthlyTotals[$overallTopIndex] ?? 0;

                $data['devices'] = $devicesList;
                $data['period_labels'] = array_map(function ($m) { return Carbon::createFromFormat('Y-m', $m)->format('M Y'); }, $months);
                $data['summary'] = [
                    'total' => $overallTotal,
                    'average' => $overallAvg,
                    'top_month_label' => $overallTopLabel,
                    'top_month_value' => $overallTopValue,
                ];

                try {
                    \Log::debug('PDF export data', [
                        'year' => $year,
                        'device_id' => $deviceId,
                        'download_rows_count' => count($downloadRows),
                        'devices_count' => count($devicesList),
                        'months' => $months,
                    ]);

                    \Log::debug('PDF export sample rows', [
                        'download_rows_sample' => array_slice($downloadRows, 0, 8),
                        'devices_sample' => array_slice($devicesList, 0, 8),
                    ]);
                } catch (\Throwable $_logEx) {
                }
            } catch (\Throwable $e) {
                $data['devices'] = [];
                $data['period_labels'] = [];
            }
        }

        $html = view('admin.devices.downloads.pdf', $data)->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();
        $filename = __('Download history') . ' - ' . now()->format(format: 'dmY-His') . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
