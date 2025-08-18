<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Achievement</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            text-align: center;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .certificate-container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 50px;
            border: 10px solid #003a68;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        
        .certificate-title {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #555;
            margin-bottom: 40px;
        }
        
        .recipient {
            font-size: 26px;
            font-weight: bold;
            color: #000;
            margin: 20px 0;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding: 5px 30px;
        }
        
        .content {
            font-size: 18px;
            margin-top: 20px;
            color: #333;
        }
        
        .footer {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            box-sizing: border-box;
        }
        
        .signature {
            text-align: left;
            flex: 0 0 auto;
        }
        
        .signature-line {
            width: 150px;
            border-top: 1px solid #000;
            margin: 0 0 5px 0;
        }
        
        .signature-name {
            margin: 0;
            font-size: 16px;
            line-height: 1.2;
        }
        
        .signature-title {
            margin: 0;
            font-size: 14px;
            line-height: 1.2;
        }
        
        .date {
            text-align: right;
            flex: 0 0 auto;
            margin-top: 21px; /* This moves Date up to align with Manoj Gandhi */
        }
        
        .date-label {
            margin: 0 0 5px 0;
            font-size: 16px;
            line-height: 1.2;
        }
        
        .date-value {
            margin: 0;
            font-size: 16px;
            line-height: 1.2;
        }
        
        .logo {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="logo">
            <!-- Logo removed as it was causing issues -->
        </div>
        
        <div class="certificate-title">CERTIFICATE OF ACHIEVEMENT</div>
        <div class="subtitle">This certificate is proudly presented to</div>
        
        <div class="recipient">{{ $name ?? 'Recipient Name' }}</div>
        
        <div class="content">
            In recognition of outstanding dedication and achievement.<br>
            Awarded with appreciation for your efforts and accomplishments.
        </div>
        
        <div class="footer">
            <!-- Left side: Signature -->
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Manoj Gandhi</div>
                <div class="signature-title">CEO of USS</div>
            </div>
            
            <!-- Right side: Date -->
            <div class="date">
                <div class="date-label">Date</div>
                <div class="date-value">{{ $issued_at ?? now()->format('F j, Y') }}</div>
            </div>
        </div>
    </div>
</body>
</html>
