<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Authenticity</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Roboto:wght@300;400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e9e9e9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .certificate-container {
            width: 1000px;
            height: 700px;
            margin: auto;
            background: white;
            padding: 60px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 10px solid transparent;
            border-image: linear-gradient(45deg, #4a90e2, #9013fe) 1;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-family: 'Merriweather', serif;
            font-size: 48px;
            font-weight: 700;
            color: #222;
            letter-spacing: 2px;
            margin: 0;
        }

        .subtitle {
            font-size: 20px;
            font-weight: 300;
            color: #777;
            margin-top: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .certificate-body {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .presented-to {
            font-size: 18px;
            font-weight: 400;
            color: #555;
            margin-bottom: 15px;
        }

        .recipient-name {
            font-family: 'Merriweather', serif;
            font-size: 56px;
            font-weight: 700;
            color: #4a90e2;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .certificate-description {
            font-size: 18px;
            line-height: 1.8;
            max-width: 700px;
            margin: 20px auto;
            color: #444;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 40px;
            border-top: 2px solid #eee;
        }

        .signature-block, .date-block {
            text-align: center;
        }

        .signature-line {
            width: 220px;
            height: 2px;
            background: #ccc;
            margin: 0 auto 10px;
        }

        .signature-title {
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }

        .serial-number {
            position: absolute;
            bottom: 25px;
            right: 35px;
            font-size: 12px;
            color: #999;
        }
        
        .seal {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
        }

    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-header">
            <h1 class="logo">Verification</h1>
            <p class="subtitle">Certificate of Authenticity</p>
        </div>

        <div class="certificate-body">
            <p class="presented-to">This certificate is awarded to</p>
            <h2 class="recipient-name">{{ $name }}</h2>
            <p class="certificate-description">
                for successfully completing the verification process and demonstrating full compliance with our documentation standards.
            </p>
        </div>

        <div class="certificate-footer">
            <div class="date-block">
                <p class="signature-title">Issued On</p>
                <div class="signature-line"></div>
                <p class="signature-title"><strong>{{ $issued_at }}</strong></p>
            </div>
            <div class="signature-block">
                <p class="signature-title">Authorised Signature</p>
                <div class="signature-line"></div>
                <p class="signature-title"></p>
            </div>
        </div>

        <div class="serial-number">
            Serial: <strong>{{ $serial_number }}</strong>
        </div>
        
        <img src="https://www.freepnglogos.com/uploads/certified-png-logo/certified-stamp-png-logo-14.png" alt="Official Seal" class="seal"/>
    </div>
</body>
</html>
