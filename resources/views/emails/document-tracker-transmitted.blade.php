<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Forwarded</title>
</head>
<body>
    <p>Dear {{ $documentTracker->requestor_name ?? 'requestor' }},</p>

    <p>Your document with tracking number <strong>{{ $documentTracker->tracking_number }}</strong> has been forwarded.</p>

    <p>Document type: {{ $documentTracker->document_type }}</p>

    <p>Details: {{ $documentTracker->details ?? '-' }}</p>

    <p>Current office: {{ optional($documentTracker->currentOffice)->name ?? '-' }}</p>

    <p>This is an automated notification. Please do not reply to this email.</p>
</body>
</html>
