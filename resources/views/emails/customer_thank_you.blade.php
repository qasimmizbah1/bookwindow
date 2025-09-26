<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Registering</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            margin: 20px;
        }
        .email-container {
                background-color: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                width: 600px;
                margin: 20px auto;
                box-shadow: 5px 5px 5px 14px #ddd;
                border:1px solid #ddd;
                text-align: left;
        }
        .header {
            background-color: #ddd;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eeeeee;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #eeeeee;
            font-size: 12px;
            color: #777777;
            background-color: #f5f5f5;
        }
        .social-icons {
            margin: 20px 0;
        }
        .social-icons a {
            margin: 0 10px;
            text-decoration: none;
        }
      
        .label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 160px;
        }
         .message-box {
            background-color: #f5f5f5;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
     <div class="email-container">
    <div class="header">
        <img src="https://admin.bookwindow.in/storage/logo.png" alt="{{ config('app.name') }} Logo" class="logo">
    </div>
    
    <div class="content">
        <h1>Welcome, {{ $customer->first_name }}!</h1>
        
        <p>Thank you for registering with <strong>{{ config('app.name') }}</strong>. We're thrilled to have you as part of our community!</p>
        
        <p>Your account has been successfully created, and you're now ready to explore all the benefits we offer.</p>
        
        <div>
            <a href="{{ config('app.frontend_url') }}/sign-in" class="button">Access Your Account</a>
        </div>
            
        <p>If you have any questions or need assistance, our support team is here to help. Simply reply to this email or contact us at <a href="mailto:info@bookwindow.in">info@bookwindow.in</a>.</p>
        
        <p>Happy exploring!</p>
        
        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>
    
    <div class="footer">
      <!--   <div class="social-icons">
            <a href="{{ config('app.frontend_url') }}/facebook">Facebook</a>
            <a href="{{ config('app.frontend_url') }}/twitter">Twitter</a>
            <a href="{{ config('app.frontend_url') }}/instagram">Instagram</a>
        </div> -->
        
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>
            <a href="{{ config('app.frontend_url') }}/privacy">Privacy Policy</a> | 
            <a href="{{ config('app.frontend_url') }}/terms">Terms of Service</a>
        </p>
        
        <p>
            <!-- <small>
                You're receiving this email because you registered at {{ config('app.name') }}.
                <br>
                <a href="{{ config('app.frontend_url') }}/unsubscribe">Unsubscribe</a>
            </small> -->
        </p>
    </div>
     </div>
</body>
</html>