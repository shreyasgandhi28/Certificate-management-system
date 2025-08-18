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
            position: relative;
            padding: 0 40px;
            min-height: 100px;
        }

        .signature, .date {
            width: 200px;
            position: relative;
        }

        .signature {
            text-align: left;
        }

        .date {
            position: absolute;
            right: 40px;
            bottom: 0;
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin-bottom: 5px;
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
            <div class="signature">
                <div class="signature-line"></div>
                <div>Manoj Gandhi</div>
                <div>CEO of USS</div>
            </div>

            <div class="date">
                <div>{{ $issued_at ?? now()->format('F j, Y') }}</div>
                <div class="signature-line"></div>
                <div>Date</div>
            </div>
        </div>
    </div>
</body>
</html>
