<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Certificate is Ready - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 30px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4f46e5;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Certificate is Ready!</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $applicant->name }},</p>
        
        <p>We are pleased to inform you that your certificate has been successfully processed and is now ready for download. This certificate is a testament to your achievement and we congratulate you on this milestone.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $certificateUrl }}" class="button">Download Your Certificate</a>
        </div>
        
        <p>Certificate Details:</p>
        <ul>
            <li><strong>Certificate ID:</strong> {{ $certificate->serial_number }}</li>
            <li><strong>Issued On:</strong> {{ $certificate->generated_at->format('F d, Y') }}</li>
        </ul>
        
        <p>If you have any questions or need further assistance, please don't hesitate to contact our support team.</p>
        
        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
