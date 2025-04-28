<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Summary Report</title>
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

        td:first-child {
            text-align: left;
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

        .currency-sign{
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

    <h3 style="text-align: center; margin-top: 20px;">Monthly Summary Report</h3>

    @if ($fundSource)
        <h4 style="text-align: center; margin-bottom: 10px;">Fund Source: {{ $fundSource->name }}</h4>
    @endif

    @if ($year)
        <h4 style="text-align: center; margin-bottom: 10px;">Allotment Year: {{ $year }}</h4>
    @endif

    <p style="text-align: center; margin-top: 0;">
    </p>

    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">School Name</th>
                <th rowspan="2">Fund Source</th>
                <th rowspan="2">Allotment Year</th>
                <th colspan="3">Quarter 1</th>
                <th colspan="3">Quarter 2</th>
                <th colspan="3">Quarter 3</th>
                <th colspan="3">Quarter 4</th>
                <th rowspan="2">Total Amount</th>
            </tr>
            <tr>
                <th>January</th>
                <th>February</th>
                <th>March</th>
                <th>April</th>
                <th>May</th>
                <th>June</th>
                <th>July</th>
                <th>August</th>
                <th>September</th>
                <th>October</th>
                <th>November</th>
                <th>December</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAllotmentAmount = 0;
                $totalMonthly = [
                    'January' => 0, 'February' => 0, 'March' => 0,
                    'April' => 0, 'May' => 0, 'June' => 0,
                    'July' => 0, 'August' => 0, 'September' => 0,
                    'October' => 0, 'November' => 0, 'December' => 0
                ];
                $totalAmount = 0;
                $totalBalance = 0;
            @endphp
            @forelse ($report as $record)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $record['school_name'] }}</td>
                <td>{{ $record['fund_source'] }}</td>
                <td>{{ $record['year'] }}</td>
                @php
                    foreach ($record['monthly_request_amount'] as $month => $amount) {
                        $totalMonthly[$month] += $amount;
                    }
                    $totalAmount += $record['total_amount'];
                @endphp
                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                    <td class="currency-sign">
                        @if ($record['monthly_request_amount'][$month] > 0)
                            &#8369; {{ number_format($record['monthly_request_amount'][$month], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td class="currency-sign">&#8369; {{ number_format($record['total_amount'], 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="18" class="text-center">No record found</td>
            </tr>
            @endforelse
            <tr>
                <th colspan="4" style="text-align: right;">Total</th>
                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                    <th class="currency-sign">
                        @if ($totalMonthly[$month] > 0)
                            &#8369; {{ number_format($totalMonthly[$month], 2) }}
                        @else
                            -
                        @endif
                    </th>
                @endforeach
                <th class="currency-sign">&#8369; {{ number_format($totalAmount, 2) }}</th>
            </tr>
        </tbody>
    </table>

    {{-- <div>
        <h4 style="margin-bottom: 10px;">Prepared by:</h4>
        <h3 style="margin-bottom: 1px;">
            {{ Auth::user()->name }}
        </h3>
        <p style="font-size: 14px">Administrator</p>
        <p style="font-size: 14px">Generated on: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div> --}}
</body>

</html>
