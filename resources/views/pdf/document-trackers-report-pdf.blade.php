<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Trackers Report</title>
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
            vertical-align: top;
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

        .text-center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
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

            <h3 style="text-align: center; margin-top: 0px;">Document Trackers Report</h3>
        </div>
        <h4 style="text-align: center; margin-bottom: 10px;">
            {{ collect([$reportSubtitle, 'Total: ' . $documentTrackers->count()])->filter()->implode(' | ') }}
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
                        {{ \Carbon\Carbon::now(config('app.display_timezone'))->format('F d, Y h:i A') }}</p>
                </td>
                <td style="width: 10%; text-align: right; vertical-align: top; border: none; font-family: Arial, sans-serif;">
                    <div class="page-number"></div>
                </td>
            </tr>

        </table>

    </footer>

    @php
        $statusLabels = [
            'pending' => 'Pending',
            'received' => 'Received',
            'transmitted' => 'Forwarded',
            'returned' => 'Returned',
            'completed' => 'Completed',
        ];

        $formatDate = function ($value) {
            return $value
                ? \Carbon\Carbon::parse($value)->setTimezone(config('app.display_timezone'))->format('F d, Y')
                : '-';
        };
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 8%;">Tracking Number</th>
                <th style="width: 12%;">Requestor</th>
                <th style="width: 12%;">Requesting Office</th>
                <th style="width: 12%;">Current Office</th>
                <th style="width: 10%;">Document Type</th>
                <th>Details</th>
                <th style="width: 7%;">Status</th>
                <th style="width: 9%;">Date Received</th>
                <th style="width: 9%;">Date Released</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documentTrackers as $documentTracker)
                @php
                    // Mirror the on-screen table: fall back to the requestor name and
                    // flag the row as external when no office is linked.
                    $requestingOfficeName = optional($documentTracker->requestingOffice)->name
                        ?? ($documentTracker->requestor_name ? $documentTracker->requestor_name . ' (External)' : '-');
                @endphp
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td class="nowrap">{{ $documentTracker->tracking_number }}</td>
                    <td>{{ $documentTracker->requestor_name ?? '-' }}</td>
                    <td>{{ $requestingOfficeName }}</td>
                    <td>{{ optional($documentTracker->currentOffice)->name ?? '-' }}</td>
                    <td>{{ $documentTracker->document_type ?? '-' }}</td>
                    <td>{{ $documentTracker->details ?? '-' }}</td>
                    <td class="nowrap">{{ $statusLabels[$documentTracker->status] ?? ucfirst($documentTracker->status ?? '-') }}</td>
                    <td class="nowrap">{{ $formatDate($documentTracker->received_at) }}</td>
                    <td class="nowrap">{{ $formatDate($documentTracker->released_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No record found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
