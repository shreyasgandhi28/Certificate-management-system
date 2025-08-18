<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');
        
        body { 
            margin: 0;
            padding: 20px;
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .certificate-container {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            padding: 50px 70px;
            background: #fff;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.08);
            position: relative;
            border: 20px solid #f5f2e9;
            background-image: 
                linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,253,247,0.9) 100%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-29c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23d4af37' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            position: relative;
            overflow: hidden;
        }
        
        .border-decoration {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            pointer-events: none;
            z-index: 1;
        }
        
        .header {
            text-align: center;
            margin: 0 auto 40px;
            position: relative;
            padding-bottom: 20px;
            max-width: 700px;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, #d4af37, #f4d03f, #d4af37);
            border-radius: 2px;
        }
        
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 40px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        
        .subtitle {
            font-size: 18px;
            color: #7f8c8d;
            margin: 0;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-weight: 400;
        }
        
        .content {
            text-align: center;
            margin: 40px auto 60px;
            position: relative;
            z-index: 2;
            max-width: 800px;
        }
        
        .presented-to {
            font-size: 20px;
            margin: 0 0 40px;
            color: #7f8c8d;
            font-weight: 400;
            letter-spacing: 1px;
        }
        
        .name {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 600;
            color: #2c3e50;
            margin: 30px 0 40px;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 4px;
            position: relative;
            display: inline-block;
            padding: 0 40px;
        }
        
        .name::before,
        .name::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #d4af37);
        }
        
        .name::before {
            left: -40px;
        }
        
        .name::after {
            right: -40px;
            background: linear-gradient(90deg, #d4af37, transparent);
        }
        
        .description {
            font-size: 20px;
            line-height: 1.8;
            color: #3a4a5e;
            max-width: 680px;
            margin: 0 auto 50px;
            font-weight: 400;
            position: relative;
            padding: 0 20px;
            font-family: 'Playfair Display', serif;
        }
        
        .details {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 50px 0 30px;
            flex-wrap: wrap;
        }
        
        .detail-item {
            text-align: center;
            padding: 15px 25px;
            background: rgba(255, 253, 247, 0.7);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            min-width: 180px;
        }
        
        .detail-label {
            font-size: 13px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            font-family: 'Montserrat', sans-serif;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin: 70px auto 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(212, 175, 55, 0.3);
            position: relative;
            z-index: 2;
            max-width: 700px;
        }
        
        .signature {
            text-align: center;
            flex: 1;
            padding: 0 20px;
        }
        
        .signature-line {
            width: 160px;
            height: 1px;
            background: #d4af37;
            margin: 0 auto 10px;
            position: relative;
        }
        
        .signature-line::before {
            content: '';
            position: absolute;
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 10px;
            background: #d4af37;
            border-radius: 50%;
        }
        
        .signature-name {
            font-weight: 600;
            margin: 15px 0 5px;
            font-size: 16px;
            color: #2c3e50;
            letter-spacing: 1px;
        }
        
        .signature-title {
            font-size: 13px;
            color: #7f8c8d;
            letter-spacing: 1px;
            font-weight: 500;
        }
        
        .serial-number {
            position: absolute;
            bottom: 20px;
            right: 30px;
            font-size: 12px;
            color: #bdc3c7;
            letter-spacing: 1px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 160px;
            font-weight: 800;
            color: rgba(0, 0, 0, 0.03);
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
            font-family: 'Playfair Display', serif;
            text-transform: uppercase;
            letter-spacing: 15px;
            opacity: 0.7;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .certificate-container {
                padding: 30px 20px;
                border-width: 15px;
            }
            
            .title {
                font-size: 28px;
            }
            
            .subtitle {
                font-size: 14px;
                letter-spacing: 2px;
            }
            
            .name {
                font-size: 32px;
                padding: 0 30px;
                margin: 20px 0 30px;
            }
            
            .name::before,
            .name::after {
                width: 30px;
            }
            
            .description {
                font-size: 16px;
                padding: 0 10px;
            }
            
            .details {
                flex-direction: column;
                gap: 15px;
                margin: 30px 0;
            }
            
            .detail-item {
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }
            
            .signatures {
                flex-direction: column;
                gap: 30px;
                margin-top: 40px;
            }
            
            .watermark {
                font-size: 100px;
                letter-spacing: 8px;
            }
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
                This certificate is awarded in appreciation of your dedication and commitment 
                to maintaining the highest standards of professionalism.
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
