<style>
    @font-face {
        font-family: 'OldEnglishTextMT';
        src: url('{{ asset('fonts/oldenglishtextmts.ttf') }}') format('truetype');
        font-weight: bold;
    }

    @page {
        margin: 20px;
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
        font-size: 10px;
        text-align: left;
        vertical-align: top;
    }

    th {
        background-color: #f0f0f0;
    }

    /* Keep a copy together if possible */
    .copy-page {
        page-break-inside: avoid;
        break-inside: avoid;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #999;
    }

    .copy-page:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-weight: bold;
        background: #d9e6f2;
    }

    .sign-space {
        height: 55px;
    }

    .slip-value {
        font-weight: bold;
    }

    .copy-label {
        text-align: center;
        font-size: 10px;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pdf-footer {
        margin-top: 15px;
        padding-top: 8px;
    }

    .pdf-footer table,
    .pdf-footer tr,
    .pdf-footer td {
        border: none !important;
    }

    .pdf-footer td {
        padding: 0 !important;
        vertical-align: top;
        font-size: 10px;
    }
</style>

<body>

@php
    $statusLabels = [
        'transmitted' => 'Forwarded',
        'returned' => 'Returned',
        'received' => 'Received',
        'pending' => 'Pending',
    ];
    $statusLabel = $statusLabels[$documentTracker->status] ?? ucfirst($documentTracker->status);
@endphp

@foreach (['Office Copy', 'Customer Copy'] as $copyLabel)

<div class="copy-page">

<h3 style="text-align:center; margin-top:0; margin-bottom:6px;">
    Document Tracking Slip
</h3>

<div class="copy-label">{{ $copyLabel }}</div>

<h4 style="text-align:center; margin-bottom:10px;">
    {{ collect([
        'Tracking No. ' . $documentTracker->tracking_number,
        $documentTracker->requestor_name,
        optional($documentTracker->currentOffice)->name,
        $documentTracker->document_type,
        $statusLabel,
    ])->filter()->implode(' | ') }}
</h4>

<table>
    <tr>
        <td colspan="4"
            class="section-title"
            style="text-align:center;font-size:12px;">
            Document Information
        </td>
    </tr>

    <tr>
        <th style="width:20%;">Tracking Number</th>
        <td style="width:30%;" class="slip-value">
            {{ $documentTracker->tracking_number }}
        </td>

        <th style="width:20%;">Status</th>
        <td style="width:30%;" class="slip-value">
            {{ $statusLabel }}
        </td>
    </tr>

    <tr>
        <th>Requestor</th>
        <td class="slip-value">{{ $documentTracker->requestor_name }}</td>

        <th>Current Office</th>
        <td class="slip-value">
            {{ optional($documentTracker->currentOffice)->name ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Document Type</th>
        <td class="slip-value">{{ $documentTracker->document_type }}</td>

        <th>Received At</th>
        <td class="slip-value">
            {{ $documentTracker->received_at
                ? \Carbon\Carbon::parse($documentTracker->received_at)->setTimezone(config('app.display_timezone'))->format('F d, Y')
                : '-' }}
        </td>
    </tr>

    <tr>
        <th>Released At</th>
        <td class="slip-value">
            {{ $documentTracker->released_at
                ? \Carbon\Carbon::parse($documentTracker->released_at)->setTimezone(config('app.display_timezone'))->format('F d, Y')
                : '-' }}
        </td>

        <th>Received By</th>
        <td class="slip-value">
            {{ optional($documentTracker->receivedByUser)->name ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Released By</th>
        <td class="slip-value">
            {{ optional($documentTracker->releasedByUser)->name ?? '-' }}
        </td>

        <th>Details</th>
        <td class="slip-value">
            {{ $documentTracker->details ?? '-' }}
        </td>
    </tr>
</table>

<table style="margin-top:14px;">
    <tr>
        <td colspan="2"
            class="section-title"
            style="text-align:center;">
            Releasing Transaction
        </td>
    </tr>

    <tr>
        <th style="width:25%;">Signature</th>
        <td class="sign-space">&nbsp;</td>
    </tr>

    <tr>
        <th>Fullname</th>
        <td>
            {{ optional($documentTracker->releasedByUser)->name ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Position/Designation</th>
        <td>
            {{ optional($documentTracker->releasedByUser)->position ?? '-' }}
        </td>
    </tr>

    <tr>
        <th>Date/Time</th>
        <td>
            {{ $documentTracker->released_at
                ? \Carbon\Carbon::parse($documentTracker->released_at)->setTimezone(config('app.display_timezone'))->format('F d, Y h:i A')
                : '-' }}
        </td>
    </tr>

    <tr>
        <th>Release Number</th>
        <td>{{ $documentTracker->id }}</td>
    </tr>
</table>

<div class="pdf-footer">
    <table>
        <tr>
            <td style="width:35%; text-align:left;">
                <img
                    src="{{ public_path('img/deped-matatag-logos.png') }}"
                    style="width:135px; margin-right:5px;">

                <img
                    src="{{ public_path('img/logo.jpg') }}"
                    style="width:60px;">
            </td>

            <td style="width:65%; padding-left:8px !important;">
                <p style="margin:1px 0;">
                    <strong>Address:</strong>
                    Jaycee Avenue, Corner Rizal St.,
                    Brgy. Zone IV, City of Koronadal
                </p>

                <p style="margin:1px 0;">
                    <strong>Telephone Nos:</strong>
                    (083) 228-1209 / (083) 228-9706
                </p>

                <p style="margin:1px 0;">
                    <strong>Email Address:</strong>
                    Koronadal.city@deped.gov.ph
                </p>

                <p style="margin:1px 0;">
                    <strong>Date Generated:</strong>
                    {{ \Carbon\Carbon::now(config('app.display_timezone'))->format('F d, Y h:i A') }}
                </p>
            </td>
        </tr>
    </table>
</div>

</div>

@endforeach

</body>
