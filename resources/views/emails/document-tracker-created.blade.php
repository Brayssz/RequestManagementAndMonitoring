<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracker Created</title>
</head>
<body style="margin:0; padding:0; background-color:#eef1f6; font-family:Arial, Helvetica, sans-serif; color:#2d3748;">
    @php
        $statusLabels = [
            'transmitted' => 'Forwarded',
            'returned' => 'Returned',
            'received' => 'Received',
            'pending' => 'Pending',
        ];
        $statusLabel = $statusLabels[$documentTracker->status] ?? ucfirst($documentTracker->status ?? 'Pending');
    @endphp

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef1f6; padding:24px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(16,42,94,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0b3d91; padding:26px 32px;">
                            <p style="margin:0; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#aac4f0;">
                                DepEd Koronadal &mdash; SGOD Office
                            </p>
                            <h1 style="margin:6px 0 0; font-size:22px; line-height:1.3; color:#ffffff; font-weight:bold;">
                                Document Tracker Created
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <p style="margin:0 0 16px; font-size:15px; color:#2d3748;">
                                Dear <strong>{{ $documentTracker->requestor_name ?? 'Requestor' }}</strong>,
                            </p>

                            <p style="margin:0 0 24px; font-size:15px; line-height:1.6; color:#4a5568;">
                                Your document has been successfully registered in the Document Tracking and
                                Monitoring System. Please keep the tracking number below for your reference.
                            </p>

                            <!-- Tracking number highlight -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="background-color:#f0f5ff; border:1px solid #d6e2fb; border-radius:8px; padding:16px 20px;" align="center">
                                        <p style="margin:0; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#5a6b85;">Tracking Number</p>
                                        <p style="margin:4px 0 0; font-size:24px; font-weight:bold; letter-spacing:1px; color:#0b3d91;">
                                            {{ $documentTracker->tracking_number }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Details table -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0; border-radius:8px; border-collapse:separate; overflow:hidden;">
                                <tr>
                                    <td style="width:40%; padding:12px 16px; font-size:13px; color:#5a6b85; background-color:#f8fafc; border-bottom:1px solid #edf2f7;"><strong>Document Type</strong></td>
                                    <td style="padding:12px 16px; font-size:13px; color:#2d3748; border-bottom:1px solid #edf2f7;">{{ $documentTracker->document_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; font-size:13px; color:#5a6b85; background-color:#f8fafc; border-bottom:1px solid #edf2f7;"><strong>Details</strong></td>
                                    <td style="padding:12px 16px; font-size:13px; color:#2d3748; border-bottom:1px solid #edf2f7;">{{ $documentTracker->details ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; font-size:13px; color:#5a6b85; background-color:#f8fafc; border-bottom:1px solid #edf2f7;"><strong>Current Office</strong></td>
                                    <td style="padding:12px 16px; font-size:13px; color:#2d3748; border-bottom:1px solid #edf2f7;">{{ optional($documentTracker->currentOffice)->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; font-size:13px; color:#5a6b85; background-color:#f8fafc;"><strong>Status</strong></td>
                                    <td style="padding:12px 16px; font-size:13px;">
                                        <span style="display:inline-block; padding:3px 12px; font-size:12px; font-weight:bold; color:#0b3d91; background-color:#e5edfb; border-radius:999px;">{{ $statusLabel }}</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; font-size:12px; line-height:1.6; color:#8a97a8;">
                                This is an automated notification. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 32px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding-top:18px;" valign="middle">
                                        <img src="{{ $message->embed(public_path('img/deped-matatag-logos.png')) }}" alt="DepEd Matatag" width="135" style="display:inline-block; vertical-align:middle; margin-right:8px;">
                                        <img src="{{ $message->embed(public_path('img/logo.jpg')) }}" alt="Koronadal City Division" width="52" style="display:inline-block; vertical-align:middle;">
                                    </td>
                                    <td style="padding-top:18px; padding-left:8px; font-size:11px; line-height:1.7; color:#4a5568;" valign="middle">
                                        <p style="margin:1px 0;"><strong style="color:#0b3d91;">Address:</strong> Jaycee Avenue, Corner Rizal St., Brgy. Zone IV, City of Koronadal</p>
                                        <p style="margin:1px 0;"><strong style="color:#0b3d91;">Telephone Nos:</strong> (083) 228-1209 / (083) 228-9706</p>
                                        <p style="margin:1px 0;"><strong style="color:#0b3d91;">Email Address:</strong> Koronadal.city@deped.gov.ph</p>
                                        <p style="margin:1px 0;"><strong style="color:#0b3d91;">Date Generated:</strong> {{ \Carbon\Carbon::now(config('app.display_timezone'))->format('F d, Y h:i A') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
