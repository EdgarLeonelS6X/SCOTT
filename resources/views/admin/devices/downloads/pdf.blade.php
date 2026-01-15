<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Downloads report') }}</title>
    <style>
        :root {
            --brand: #9F24A5;
            --text: #1f2933;
            --muted: #52606d;
            --border: #e5e7eb;
            --bg: #f7f8fa;
        }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: var(--text); background: #ffffff; margin: 0; padding: 0; }
        .page { padding: 24px 28px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--brand); padding-bottom: 10px; margin-bottom: 18px; }
        .title { font-size: 20px; font-weight: 700; color: var(--brand); }
        .badge { background: var(--brand); color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 11px; letter-spacing: 0.4px; }
        .meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 16px; }
        .card { border: 1px solid var(--border); border-radius: 10px; background: var(--bg); padding: 12px 14px; margin-bottom: 12px; }
        .card h4 { margin: 0 0 6px 0; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); }
        .card .value { font-size: 14px; font-weight: 700; color: var(--text); }
        .section { margin-top: 14px; margin-bottom: 16px; }
        .section h3 { margin: 0 0 8px 0; font-size: 14px; color: var(--text); }
        .chart-box { text-align: center; border: 1px solid var(--border); border-radius: 10px; padding: 10px; background: #fff; }
        .chart-box img { max-width: 100%; height: auto; max-height: 360px; object-fit: contain; }
        .details-section { page-break-before: always; }
        .details-table { page-break-inside: avoid; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        .note { font-size: 11px; color: var(--muted); margin-top: 6px; }
        .footer { font-size: 11px; color: var(--muted); text-align: center; margin-top: 10px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="title">{{ __('Downloads report by devices') }}</div>
        </div>

        <div class="meta-grid">
            <div class="card">
                <h4>{{ __('Generated at') }}</h4>
                <div class="value">{{ now()->format('Y-m-d H:i:s') }}</div>
            </div>
            <div class="card">
                <h4>{{ __('Year') }}</h4>
                <div class="value">{{ $year ?? date('Y') }}</div>
            </div>
            @if(!empty($summary))
                <div class="card">
                    <h4>{{ __('Total downloads') }}</h4>
                    <div class="value">{{ $summary['total'] ?? 0 }}</div>
                </div>
                <div class="card">
                    <h4>{{ __('Average per month') }}</h4>
                    <div class="value">{{ $summary['average'] ?? 0 }}</div>
                </div>
                <div class="card">
                    <h4>{{ __('Top month') }}</h4>
                    <div class="value">{{ $summary['top_month_label'] ?? '—' }} ({{ $summary['top_month_value'] ?? 0 }})</div>
                </div>
            @endif
        </div>

        <br><br>

        @if(!empty($monthlyImage))
            <div class="section">
                <h3>{{ __('Monthly downloads') }}</h3>
                <div class="chart-box">
                    <img src="{{ $monthlyImage }}" alt="monthly chart">
                </div>
                <div class="note">{{ __('Figure 1. Monthly downloads for the selected year.') }}</div>
            </div>
        @endif

        @if(!empty($pieImage))
            <div class="section" style="margin-top: 12px;">
                <h3>{{ __('Protocol distribution') }}</h3>
                <div class="chart-box">
                    <img src="{{ $pieImage }}" alt="pie chart">
                </div>
                <div class="note">{{ __('Figure 2. Share of downloads by protocol.') }}</div>
            </div>
        @endif

        <br>

        <div class="section">
            <h3>{{ __('Downloads by device') }}</h3>
            @if(!empty($devices) && count($devices))
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    @foreach($devices as $d)
                        <div class="card" style="display:flex;flex-direction:column;gap:8px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if(!empty($d['image']))
                                    <img src="{{ $d['image'] }}" alt="" style="width:34px;height:34px;object-fit:contain;border-radius:6px;border:1px solid var(--border);background:#fff;" />
                                @endif
                                <div style="font-weight:700;font-size:13px;color:var(--text);">{{ $d['name'] }}</div>
                                <div style="margin-left:auto;font-size:12px;color:var(--muted);">{{ __('Total') }}: {{ $d['total'] }}</div>
                            </div>

                            <div style="width:100%">{!! $d['sparkline'] !!}</div>

                            <div style="font-size:11px;color:var(--muted);">
                                <strong>{{ __('Monthly counts') }}:</strong>
                                <span>
                                    @foreach($d['counts'] as $i => $c)
                                        @php $lbl = $period_labels[$i] ?? ''; @endphp
                                        <div style="display:inline-block;margin-right:8px;">{{ $lbl }}: {{ $c }}</div>
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="note">{{ __('Figure 3. Monthly download trends per device for the selected range.') }}</div>
            @else
                <div class="card">
                    <div style="font-size:12px;color:var(--muted);">{{ __('No device breakdown available for the selected range.') }}</div>
                </div>
            @endif
        </div>

        <br><br><br>

        <div class="section details-section" style="margin-top:18px;">
            <h3>{{ __('Detailed downloads') }}</h3>
            <div style="overflow:auto; border:1px solid var(--border); border-radius:8px; padding:8px; background:#fff;">
                @if(!empty($grouped_by_device) && count($grouped_by_device))
                    @foreach($grouped_by_device as $device)
                        <div class="card" style="margin-bottom:10px; padding:10px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                <div>
                                    <div style="font-weight:700;font-size:13px;color:var(--text);">{{ $device['name'] ?? $device['device_name'] ?? __('Unknown device') }}</div>
                                    <div style="font-size:11px;color:var(--muted);">{{ $device['protocol'] ?? '' }} @if(!empty($device['device_area'])) — {{ $device['device_area'] }} @endif</div>
                                </div>
                                <div style="font-weight:700;color:var(--text);">{{ __('Total') }}: {{ $device['total'] ?? (is_array($device['counts'] ?? null) ? array_sum($device['counts']) : 0) }}</div>
                            </div>

                            <div style="margin-top:8px;">
                                <table style="width:100%; border-collapse:collapse; font-size:11px;">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Month') }}</th>
                                            <th style="text-align:right; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Downloads') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counts = $device['counts'] ?? $device['months'] ?? [];
                                        @endphp
                                        @if(is_array($counts) && count($counts))
                                            @foreach($counts as $idx => $cnt)
                                                <tr>
                                                    <td style="padding:6px 8px; border-bottom:1px solid #f1f3f5;">{{ $period_labels[$idx] ?? ($device['months_labels'][$idx] ?? '') }}</td>
                                                    <td style="padding:6px 8px; text-align:right; border-bottom:1px solid #f1f3f5;">{{ $cnt }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2" style="padding:8px;color:var(--muted);">{{ __('No monthly counts for this device.') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @elseif(!empty($download_rows) && count($download_rows))
                    @php
                        $grouped = [];
                        foreach ($download_rows as $r) {
                            $deviceName = $r['device_name'] ?? ($r['device'] ?? '');
                            $protocol = $r['protocol'] ?? '';
                            $area = $r['device_area'] ?? ($r['area'] ?? '');
                            $month = $r['month'] ?? '';
                            $yearRow = $r['year'] ?? ($r['year'] ?? '');
                            $count = isset($r['count']) ? (int)$r['count'] : 0;
                            $key = implode('|', [$deviceName, $protocol, $area, $month, $yearRow]);
                            if (!isset($grouped[$key])) {
                                $grouped[$key] = [
                                    'device_name' => $deviceName,
                                    'protocol' => $protocol,
                                    'device_area' => $area,
                                    'month' => $month,
                                    'year' => $yearRow,
                                    'count' => 0,
                                ];
                            }
                            $grouped[$key]['count'] += $count;
                        }
                        $groupedRows = array_values($grouped);
                        usort($groupedRows, function($a, $b) {
                            $cmp = strcmp($a['device_name'] ?? '', $b['device_name'] ?? '');
                            if ($cmp !== 0) return $cmp;
                            if (($a['year'] ?? '') != ($b['year'] ?? '')) return (($a['year'] ?? 0) <=> ($b['year'] ?? 0));
                            return (($a['month'] ?? 0) <=> ($b['month'] ?? 0));
                        });
                    @endphp

                    <table style="width:100%; border-collapse:collapse; font-size:11px;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Device') }}</th>
                                <th style="text-align:left; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Protocol') }}</th>
                                <th style="text-align:right; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Month') }}</th>
                                <th style="text-align:right; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Year') }}</th>
                                <th style="text-align:right; padding:6px 8px; border-bottom:1px solid var(--border);">{{ __('Downloads') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedRows as $row)
                                <tr>
                                    <td style="padding:6px 8px; border-bottom:1px solid #f1f3f5;">{{ $row['device_name'] }}</td>
                                    <td style="padding:6px 8px; border-bottom:1px solid #f1f3f5;">{{ $row['protocol'] }}</td>
                                    <td style="padding:6px 8px; text-align:right; border-bottom:1px solid #f1f3f5;">{{ $row['month'] }}</td>
                                    <td style="padding:6px 8px; text-align:right; border-bottom:1px solid #f1f3f5;">{{ $row['year'] }}</td>
                                    <td style="padding:6px 8px; text-align:right; border-bottom:1px solid #f1f3f5;">{{ $row['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:12px;color:var(--muted);">{{ __('No detailed download records for the selected range.') }}</div>
                @endif
            </div>
            <div class="note">{{ __('Table. Full download records for the selected range.') }}</div>
        </div>

        <div class="footer">{{ config('app.name') }}</div>
    </div>
</body>
</html>
