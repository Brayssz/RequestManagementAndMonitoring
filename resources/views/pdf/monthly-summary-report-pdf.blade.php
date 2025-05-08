<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Summary Report</title>
    <style>
        @font-face {
            font-family: 'OldEnglishTextMT';
            src: url('{{ asset('fonts/oldenglishtextmts.ttf') }}') format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .old_english {
            font-family: 'OldEnglishTextMT' !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
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
            margin-bottom: 7px;
            margin-top: -2px;
        }

        .logo img {
            width: 90px;
        }

        .currency-sign {
            font-family: DejaVu Sans !important;
        }

        header {
            position: fixed;
            top: -270px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -85px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 2px solid #000;
        }

        @page {
            margin-top: 300px;
            margin-bottom: 140px;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
       
    </style>
</head>

<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('img/deped_logo.png') }}" alt="Logo">
            </div>

            <p style="text-align: center; margin-bottom: -17px; font-size: 13px;" class="old_english">Republic of the
                Philippines</p>
            <p style="text-align: center; margin-bottom: -8px; font-size: 17px;" class="old_english">Department of
                Education</p>
            <p style="text-align: center; margin-bottom: -10px; letter-spacing: 4px;">SOCCSKSARGEN REGION</p>
            <p style="text-align: center; margin-bottom: 20px; letter-spacing: 4px;">SCHOOLS DIVISION OF KORONADAL CITY
            </p>

            <div style="border-top: 2px solid #000; margin: 20px 0;"></div>

            <h3 style="text-align: center; margin-top: 0px;">Monthly Summary Report</h3>
        </div>
        @php
            $reqOffice = $requestingOffice && $requestingOffice->name ? 'Requested by: ' . $requestingOffice->name : null;
        @endphp
        <h4 style="text-align: center; margin-bottom: 10px;">
            {{ collect([$year, $fundSource ? $fundSource->name : null, $reqOffice])->filter()->implode(' | ') }}
        </h4>
    </header>

    <footer>
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: none;">
            <tr>
                <!-- Logo Section (left side) -->
                <td style="width: 19%; text-align: left; vertical-align: top; border: none;">
                    <img src="{{ public_path('img/deped-matatag-logos.png') }}" alt="Logo"
                        style="width: 140px; margin-right: 5px;">
                    <img src="{{ public_path('img/logo.jpg') }}" alt="Logo" style="width: 65px;">
                </td>

                <!-- Address Info (right side) -->
                <td
                    style="width: 71%; text-align: left; vertical-align: top; border: none; font-size: 11px; padding: 0px !important; font-family: Arial, sans-serif;">
                    <p style="margin: 1px 0;"><strong>Address:</strong> Jaycee Avenue, Corner Rizal St., Brgy. Zone IV,
                        City of Koronadal</p>
                    <p style="margin: 1px 0;"><strong>Telephone Nos:</strong> (083) 228-1209 / (083) 228-9706</p>
                    <p style="margin: 1px 0;"><strong>Email Address:</strong> Koronadal.city@deped.gov.ph</p>
                    <p style="margin: 1px 0;"><strong>Date Generated:</strong>
                        {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>
                </td>
                <td style="width: 10%; text-align: right; vertical-align: top; border: none; font-family: Arial, sans-serif;">
                    <div class="page-number"></div>
                </td>
            </tr>

        </table>

    </footer>

    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">School/Office Name</th>
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
