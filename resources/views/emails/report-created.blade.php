<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        New Report Created
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
        .channels {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #ffffff;
        }

        .details h3,
        .channels h3 {
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

        .badge-pending {
            background: #e67e22;
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
                New Report Created Notification
            </h2>
        </div>
        <div class="details">
            <h3>
                📌 Report Details
            </h3>
            <table>
                <tr>
                    <th>
                        Folio
                    </th>
                    <td>
                        {{ $report->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Category
                    </th>
                    <td>
                        {{ $report->category }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        <span class="badge badge-pending">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>
                        Reported By
                    </th>
                    <td>
                        {{ $reportedBy->name }} ({{ $reportedBy->email }})
                    </td>
                </tr>
                <tr>
                    <th>
                        Reviewed By
                    </th>
                    <td>
                        {{ $report->reviewed_by }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Created At
                    </th>
                    <td>
                        {{ $report->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="channels">
            <h3>
                📡 Channels Involved
            </h3>
            <table>
                <tr>
                    <th>
                        Channel
                    </th>
                    <th>
                        Stage
                    </th>
                    <th>
                        Media
                    </th>
                </tr>
                @foreach ($report->reportDetails as $detail)
                    <tr>
                        <td>
                            {{ $detail->channel->number }} {{ $detail->channel->name }}
                        </td>
                        <td>
                            {{ $detail->stage->name }}
                        </td>
                        <td>
                            {{ $detail->media }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="footer">
            <h3 class="footer-title">
                SCOTT
            </h3>
            <p class="footer-subtitle">
                OTT Communications System
            </p>
        </div>
    </div>
</body>

</html>
