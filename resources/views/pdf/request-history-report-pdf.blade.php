<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History Report</title>
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
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 2px solid #000;
        }

        @page {
            margin-top: 300px;
            margin-bottom: 120px;
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

            <h3 style="text-align: center; margin-top: 0px;">Request History Report</h3>
        </div>
        @php
            $reqOffice = $requestingOffice && $requestingOffice->name ? 'Requested by: ' . $requestingOffice->name : null;
            $transOffice = $transmittedOffice && $transmittedOffice->name ? 'Transmitted to: ' . $transmittedOffice->name : null;
        @endphp
        <h4 style="text-align: center; margin-bottom: 10px;">
            {{ collect([$month, $year, optional($fundSource)->name, $reqOffice, $transOffice])
            ->filter(fn($value) => !empty($value))
            ->implode(' | ') }}
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
                <th style="background-color: #f0f0f0;">DTS Date</th>
                <th style="background-color: #f0f0f0;">DTS Tracker No.</th>
                <th style="background-color: #f0f0f0;">SGOD Date Received</th>
                <th style="background-color: #f0f0f0;">Requesting School/Office</th>
                <th style="background-color: #f0f0f0;">Requestor</th>
                <th style="background-color: #f0f0f0;">Fund Source</th>
                <th style="background-color: #f0f0f0;">Amount</th>
                <th style="background-color: #f0f0f0;">Utilized Amount</th>
                <th style="background-color: #f0f0f0;">Nature of Request</th>
                <th style="background-color: #f0f0f0;">Signed Chief</th>
                <th style="background-color: #f0f0f0;">Date Transmitted</th>
                <th style="background-color: #f0f0f0;">Transmitted Office</th>
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
                    <td>{{ $record['signed_chief_date'] ?? '-' }}</td>
                    <td>{{ $record['date_transmitted'] ?? '-' }}</td>
                    <td>{{ $record['transmitted_office'] ?? '-' }}</td>
                    <td>{{ $record['remarks'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">No record found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>