<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ __('Report Resolved Notification') }}
    </title>
    <style>
        body {
            font-family: 'Arial', Helvetica, sans-serif;
            background-color: #121212;
            color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #222;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-left: 6px solid #ffffff;
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
        }

        h2 {
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }

        .details,
        .categories {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #ffffff;
        }

        .details h3,
        .categories h3 {
            margin-top: 0;
            color: #f1f1f1;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 12px;
            overflow: hidden;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #ffffff;
            color: #222;
            font-weight: bold;
        }

        td {
            background: #252525;
            color: #ddd;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 5px;
            text-transform: uppercase;
        }

        .badge-status {
            background: #374151;
            color: #fff;
        }

        .badge-type {
            background: #057A55;
            color: #fff;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #bbb;
            margin-top: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .footer-title {
            margin: 5px 0 2px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .footer-subtitle {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>
                {{ __('Report Resolved Notification') }}
            </h2>
        </div>
        <div class="details">
            <h3>
                📌 {{ __('Report Details') }}
            </h3>
            <table>
                <tr>
                    <th>
                        {{ __('Folio') }}
                    </th>
                    <td>
                        {{ $report->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('Type') }}
                    </th>
                    <td>
                        <span class="badge badge-type">
                            {{ ucfirst($report->type) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('Status') }}
                    </th>
                    <td>
                        <span class="badge badge-status">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('Reported By') }}
                    </th>
                    <td>
                        {{ $reportedBy->name }} ({{ $reportedBy->email }})
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('Created At') }}
                    </th>
                    <td>
                        {{ $report->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>
        @foreach ($categories as $category)
            <div class="categories">
                <h3>
                    📡 {{ $category['name'] }}
                </h3>
                <table>
                    <tr>
                        <th>
                            {{ __('Channel') }}
                        </th>
                        <th>
                            {{ __('Stage') }}
                        </th>
                        <th>
                            {{ __('Media') }}
                        </th>
                    </tr>
                    @if (count($category['channels']) > 0)
                        @foreach ($category['channels'] as $channel)
                            <tr>
                                <td>
                                    {{ $channel['number'] }} {{ $channel['name'] }}
                                </td>
                                <td>
                                    {{ $channel['stage'] }}
                                </td>
                                <td>
                                    {{ $channel['media'] }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3"
                                style="
                        text-align: center;
                        font-weight: bold;
                        color: #057A55;
                        background: #252525;
                        padding: 12px;
                            ">
                                {{ __('All channels on this function are working properly.') }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endforeach
        <div class="footer">
            <h3 class="footer-title">
                SCOTT
            </h3>
            <p class="footer-subtitle">
                {{ __('OTT Communications System') }}
            </p>
        </div>
    </div>
</body>

</html>
