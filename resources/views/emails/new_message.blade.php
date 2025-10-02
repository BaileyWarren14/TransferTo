<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nuevo Mensaje</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <div style="max-width:600px; margin:auto; background:white; padding:20px; border-radius:8px;">
        <h2>¡Hola {{ $receiver->name }}!</h2>
        <p>Has recibido un nuevo mensaje de <strong>{{ $sender->name }}</strong>:</p>
        <blockquote style="background: #f4f4f4; padding: 10px; border-left: 5px solid #4caf50;">
            {{ $content }}
        </blockquote>
        <p>Puedes responder iniciando sesión en la plataforma.</p>
        <br>
        <small style="color:#777;">Este es un mensaje automático, por favor no respondas a este correo.</small>
    </div>
</body>
</html>
