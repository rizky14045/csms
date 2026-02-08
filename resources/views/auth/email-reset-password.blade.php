<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>Password Reset Request</h2>

    <p>Hello,</p>

    <p>
        We received a request to reset your password.
        Click the link below to set a new password:
    </p>

    <p>
        <a href="{{ url('/reset-password/'.$token.'?email='.$email) }}"
           style="
                display: inline-block;
                padding: 10px 16px;
                background-color: #2563eb;
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
           ">
            Reset Password
        </a>
    </p>

    <p>
        If you did not request a password reset, please ignore this email.
    </p>

    <p>
        Regards,<br>
        CSMS
    </p>

</body>
</html>
