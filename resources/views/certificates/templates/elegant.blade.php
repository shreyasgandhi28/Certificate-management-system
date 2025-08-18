<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #f9f9f9;
            margin: 0;
            padding: 40px;
        }

        .certificate-container {
            background: #fff;
            padding: 60px;
            text-align: center;
            border: 12px solid #bfa14a; /* golden outer border */
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            position: relative;
        }

        /* Inner decorative border */
        .certificate-container::before {
            content: "";
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 6px dashed #d4af37;
            border-radius: 10px;
            pointer-events: none;
        }

        .certificate-title {
            font-size: 42px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .certificate-body {
            font-size: 20px;
            margin: 20px 0;
            line-height: 1.6;
        }

        .recipient-name {
            font-size: 32px;
            font-weight: bold;
            color: #222;
            margin: 30px 0;
            text-decoration: underline;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
        }

        .footer div {
            text-align: center;
            width: 45%;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1 class="certificate-title">Certificate of Achievement</h1>
        <p class="certificate-body">This certificate is proudly presented to</p>

        <div class="recipient-name">{{ $name }}</div>

        <p class="certificate-body">For successfully completing the course</p>
        <p class="certificate-body"><strong>{{ $course ?? '_________' }}</strong></p>

        <div class="footer">
            <div>
                <hr style="width:80%; margin:auto; border:1px solid #000;">
                <p>Authorized Signatory</p>
            </div>
            <div>
                <hr style="width:80%; margin:auto; border:1px solid #000;">
                <p>Date</p>
            </div>
        </div>
    </div>
</body>
</html>
