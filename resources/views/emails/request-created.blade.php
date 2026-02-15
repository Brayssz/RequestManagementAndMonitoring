<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Created Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #555;
            margin-bottom: 25px;
        }
        .status-badge {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 25px;
        }
        .details-box h3 {
            margin: 0 0 15px 0;
            color: #007bff;
            font-size: 16px;
        }
        .detail-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            width: 180px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #333;
        }
        .info-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .info-box strong {
            display: block;
            margin-bottom: 5px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .email-footer .logo {
            margin-bottom: 10px;
        }
        .email-footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">📋</div>
            <h1>Request Received</h1>
        </div>
        
        <div class="email-body">
            <p class="greeting">Dear {{ $requestorName }},</p>
            
            <p class="message">
                We are pleased to inform you that your request from <strong>{{ $schoolName }}</strong> has been successfully received and recorded in our Records Management System.
            </p>
            
            <span class="status-badge">✓ Received</span>
            
            <div class="info-box">
                <strong>Next Steps:</strong>
                Your request is now being processed by the SGOD office. You will be notified via email when your request is transmitted or if any additional information is needed.
            </div>
            
            <div class="details-box">
                <h3>Request Details</h3>
                <div class="detail-row">
                    <span class="detail-label">DTS Tracker Number:</span>
                    <span class="detail-value">{{ $requestData['dts_tracker_number'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">DTS Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($requestData['dts_date'])->format('F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Requesting Office/School:</span>
                    <span class="detail-value">{{ $schoolName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nature of Request:</span>
                    <span class="detail-value">{{ $requestData['nature_of_request'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">₱{{ number_format($requestData['amount'], 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">SGOD Date Received:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($requestData['sgod_date_received'])->format('F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Allotment Year:</span>
                    <span class="detail-value">{{ $requestData['allotment_year'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date Created:</span>
                    <span class="detail-value">{{ now()->format('F d, Y h:i A') }}</span>
                </div>
            </div>
            
            <p class="message">
                Please keep this DTS Tracker Number (<strong>{{ $requestData['dts_tracker_number'] }}</strong>) for your records. You can use it to track the status of your request.
            </p>
            
            <p class="message">
                If you have any questions or need to provide additional information, please contact the SGOD office.
            </p>
        </div>
        
        <div class="email-footer">
            <div class="logo">
                <strong>SGOD - Records Management System</strong>
            </div>
            <p>Department of Education - Koronadal City Division</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

