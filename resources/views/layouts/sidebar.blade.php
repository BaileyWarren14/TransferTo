<!-- Sidebar -->
<div id="sidebar" class="bg-primary text-white position-fixed vh-100" style="width: 250px; transition: all 0.3s;">
    <div class="sidebar-header p-3 text-center">
        <h3>TruckApp</h3>
        <button id="closeSidebar" class="btn btn-light btn-sm d-md-none">✖</button>
    </div>
    <ul class="nav flex-column px-3">
        <li class="nav-item mb-2">
            <a href="{{ route('dashboard') }}" class="nav-link text-white">Dashboard</a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('inspection') }}" class="nav-link text-white">Inspection</a>
        </li>
        <li class="nav-item mb-2">
            <a href="{{ route('details') }}" class="nav-link text-white">Details</a>
        </li>
    </ul>
</div>
