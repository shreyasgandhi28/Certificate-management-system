<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap');
        
        body { 
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        
        .certificate-container {
            width: 800px;
            margin: 40px auto;
            padding: 50px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 20px solid #f8f9fa;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDQwIDQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIiBwYXR0ZXJuVHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZmZmIi8+PHBhdGggZD0iTTAgMGg0MHY0MEgweiIgZmlsbD0iI2Y4ZjlmYSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
        }
        
        .border-decoration {
            position: absolute;
            width: 90%;
            height: 90%;
            top: 5%;
            left: 5%;
            border: 2px solid #e74c3c;
            pointer-events: none;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #e74c3c, #3498db);
        }
        
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 10px;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin: 0;
            letter-spacing: 8px;
            text-transform: uppercase;
        }
        
        .content {
            text-align: center;
            margin: 50px 0;
        }
        
        .presented-to {
            font-size: 18px;
            margin-bottom: 30px;
            color: #7f8c8d;
        }
        
        .name {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #2c3e50;
            margin: 20px 0;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .description {
            font-size: 18px;
            line-height: 1.6;
            color: #34495e;
            max-width: 600px;
            margin: 0 auto 40px;
        }
        
        .details {
            display: flex;
            justify-content: space-around;
            margin: 40px 0;
            flex-wrap: wrap;
        }
        
        .detail-item {
            text-align: center;
            padding: 0 20px;
            margin: 10px 0;
        }
        
        .detail-label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .signature {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            width: 150px;
            height: 1px;
            background: #bdc3c7;
            margin: 40px auto 10px;
        }
        
        .signature-name {
            font-weight: 600;
            margin: 5px 0;
        }
        
        .signature-title {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .serial-number {
            position: absolute;
            bottom: 20px;
            right: 30px;
            font-size: 12px;
            color: #95a5a6;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: 700;
            color: rgba(44, 62, 80, 0.05);
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
            font-family: 'Playfair Display', serif;
        }
        
        .seal {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #e74c3c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            color: #e74c3c;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.9);
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-decoration"></div>
        <div class="watermark">Certificate</div>
        
        <div class="header">
            <div class="title">Certificate of Achievement</div>
            <div class="subtitle">This is to certify that</div>
        </div>
        
        <div class="content">
            <div class="presented-to">This certificate is proudly presented to</div>
            <div class="name">{{ $name }}</div>
            <div class="description">
                In recognition of successfully completing all verification requirements 
                and demonstrating outstanding commitment to excellence in document submission.
            </div>
            
            <div class="details">
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $email }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">{{ $phone }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date Issued</div>
                    <div class="detail-value">{{ $issued_at }}</div>
                </div>
            </div>
            
            <div class="seal">
                Official<br>Seal
            </div>
        </div>
        
        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">John Smith</div>
                <div class="signature-title">Director of Verification</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Sarah Johnson</div>
                <div class="signature-title">Head of Operations</div>
            </div>
        </div>
        
        <div class="serial-number">Certificate #{{ $serial_number }}</div>
    </div>
</body>
</html>
