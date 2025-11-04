<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: normal;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .message {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
            line-height: 1.4;
        }
        .otp-container {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 8px;
        }
        .otp-code {
            font-size: 28px;
            font-weight: bold;
            color: #4a90e2;
            letter-spacing: 4px;
            font-family: monospace;
        }
        .expiry {
            background-color: #fff8e1;
            border: 1px solid #ffcc02;
            border-radius: 4px;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
            color: #8a6914;
        }
        .security-tip {
            background-color: #f0f8ff;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            font-size: 13px;
        }
        .security-tip h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #4a90e2;
        }
        .warning {
            background-color: #ffe6e6;
            border: 1px solid #ffb3b3;
            border-radius: 4px;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
            color: #cc0000;
        }
        .footer {
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 13px;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 3px 0;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .otp-code {
                font-size: 24px;
                letter-spacing: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                <strong>Hello!</strong>
            </div>
            
            <div class="message">
                You are receiving this email because we received a password reset request for your account.
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your OTP Code</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="expiry">
                <strong>This OTP will expire in 10 minutes.</strong>
            </div>
            
            <div class="security-tip">
                <h3>Security Tips</h3>
                <p>• Never share this OTP with anyone<br>
                • We will never ask for your OTP via phone or email<br>
                • If you didn't request this, please ignore this email</p>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> If you did not request a password reset, no further action is required. Your account remains secure.
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Regards,</strong></p>
            <p>{{ config('app.name') }} Team</p>
            <p style="margin-top: 15px; font-size: 12px; color: #999;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>