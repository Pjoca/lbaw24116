<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="background-color: #f4f4f9; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    
    <!-- Wrapper -->
    <div style="max-width: 600px; margin: 50px auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background-color: #4A90E2; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">Reset Your Password</h1>
        </div>

        <!-- Body -->
        <div style="padding: 30px 40px; text-align: center; color: #333;">
            <p style="font-size: 18px; margin-bottom: 20px;">Hi <strong>{{ $name }}</strong>,</p>
            
            <p style="font-size: 16px; line-height: 1.6; color: #555;">We received a request to reset the password for your account. If you requested this change, click the button below to reset your password.</p>
            
            <a href="{{ $url }}" 
                style="display: inline-block; margin-top: 20px; background-color: #4A90E2; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold; padding: 15px 30px; border-radius: 5px; text-align: center;">
                Reset Password
            </a>
            
            <p style="margin-top: 30px; font-size: 14px; color: #777;">If you did not request a password reset, you can safely ignore this email. Your password will remain the same.</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f4f4f9; padding: 20px; text-align: center;">
            <p style="font-size: 14px; color: #888;">Thank you for using our application!</p>
            <p style="font-size: 12px; color: #bbb;">© {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>

    </div>
</body>
</html>