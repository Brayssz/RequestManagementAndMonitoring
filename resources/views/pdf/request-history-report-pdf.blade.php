<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 9px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
            position: absolute;
            top: 0px;
            left: 50px;
        }

        .logo img {
            width: 90px;
        }

        .currency-sign {
            font-family: DejaVu Sans !important;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ public_path('img/logo.jpg') }}" alt="Logo">
    </div>

    <h4 style="text-align: center; margin-bottom: 10px;">Koronadal City Division - Back Office</h4>
    <p style="text-align: center; margin-bottom: 5px;">Koronadal City, South Cotabato</p>
    <p style="text-align: center; margin-bottom: 20px;">Region XII</p>

    <div style="border-top: 1px solid #000; margin: 20px 0;"></div>

    <h3 style="text-align: center; margin-top: 20px;">Request History Report</h3>

    @if ($month || $year)
        <h4 style="text-align: center; margin-bottom: 10px;"> {{ ($month ? $month : "") . " " . ($year ? $year : "") }}</h4>
        
    @endif

    @if ($fundSource)
        <h4 style="text-align: center; margin-bottom: 10px;">{{ $fundSource->name }}</h4>
    @endif

    

    <table>
        <thead>
            <tr>
                <th style="background-color: #f0f0f0;">DTS Date</th>
                <th style="background-color: #f0f0f0;">DTS Tracker No.</th>
                <th style="background-color: #f0f0f0;">SGOD Date Received</th>
                <th style="background-color: #f0f0f0;">Requesting School/Office</th>
                <th style="background-color: #f0f0f0;">Requestor</th>
                <th style="background-color: #f0f0f0;">Fund Source</th>
                <th style="background-color: #f0f0f0;">Amount</th>
                <th style="background-color: #f0f0f0;">Utilized Amount</th>
                <th style="background-color: #f0f0f0;">Nature of Request</th>
                <th style="background-color: #f0f0f0;">Date Transmitted</th>
                <th style="background-color: #f0f0f0;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $record)
                <tr>
                    <td>{{ $record['dts_date'] }}</td>
                    <td>{{ $record['dts_tracker_number'] }}</td>
                    <td>{{ $record['sgod_date_received'] }}</td>
                    <td>{{ $record['requesting_office'] }}</td>
                    <td>{{ $record['requestor'] }}</td>
                    <td>{{ $record['fund_source'] }}</td>
                    <td class="currency-sign">&#8369; {{ number_format($record['amount'], 2) }}</td>
                    <td class="currency-sign">&#8369; {{ number_format($record['utilize_amount'], 2) }}</td>
                    <td>{{ $record['nature_of_request'] }}</td>
                    <td>{{ $record['date_transmitted'] ?? '-' }}</td>
                    <td>{{ $record['remarks'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No record found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
