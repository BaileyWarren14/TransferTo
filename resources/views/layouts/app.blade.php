<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'TruckApp')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { transition: margin-left 0.3s; }
    #sidebar { left: -250px; z-index: 1000; }
    #sidebar.active { left: 0; }
    #content { margin-left: 0; transition: margin-left 0.3s; }
    #content.shifted { margin-left: 250px; }
    .hamburger {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        font-size: 1.5rem;
        background: none;
        border: none;
        color: #007bff;
    }
    .nav-link:hover { background-color: rgba(255,255,255,0.1); border-radius: 5px; }
</style>
</head>
<body>

<button class="hamburger d-md-none">☰</button>

@include('layouts.sidebar')

<div id="content" class="container-fluid p-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const hamburger = document.querySelector('.hamburger');
    const closeBtn = document.getElementById('closeSidebar');

    hamburger.addEventListener('click', () => {
        sidebar.classList.add('active');
        content.classList.add('shifted');
    });

    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('active');
        content.classList.remove('shifted');
    });
</script>

</body>
</html>
