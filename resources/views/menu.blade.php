<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>
    <link {{ asset('css/mis-estilos.css') }}>
</head>
<body>
    <div class="menu">
        <h1>Menú Principal</h1>
        <button onclick="location.href='{{ url('/new') }}'">New</button>
        <button onclick="location.href='{{ url('/details') }}'">Details</button>
    </div>
</body>
</html>