<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shared Logbook</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f7f7f7; padding: 20px; color: #333;">

    <div style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px;">
        
        <h2 style="color:#0d6efd; margin-bottom: 20px;">Driver Logbook</h2>

        <p>Hello,</p>

        <p>The driver <strong>{{ $driver->name }}</strong> has shared their logbook with you.</p>

        <p>The logbook PDF is attached to this email.</p>

        <br>

        <p>If you did not expect this email, you may safely ignore it.</p>

        <hr>

        <p style="font-size: 12px; color:#888;">
            This message was sent automatically by the Driver Logbook System.
        </p>

    </div>

</body>
</html>
