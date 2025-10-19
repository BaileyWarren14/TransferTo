{{-- Sidebar links --}}
@php
$links = [
    ['url'=>url('/driver/dashboard'), 'icon'=>'fas fa-tachometer-alt', 'label'=>'Dashboard'],
    ['url'=>url('/driver/log_book'), 'icon'=>'fas fa-chart-line', 'label'=>'Logs'],
    ['url'=>url('/driver/change_duty_status'), 'icon'=>'fas fa-toggle-on', 'label'=>'Duty Status'],
    ['url'=>url('/driver/list'), 'icon'=>'fas fa-plus-circle', 'label'=>'DOT Inspection Mode'],
    ['url'=>url('/driver/menu'), 'icon'=>'fas fa-info-circle', 'label'=>'Work Order'],
    ['url'=>url('/driver/messages'), 'icon'=>'fas fa-envelope', 'label'=>'Messages'],
    ['url'=>url('/driver/safety'), 'icon'=>'fas fa-shield-alt', 'label'=>'Safety'],
    ['url'=>url('/driver/about'), 'icon'=>'fas fa-info-circle', 'label'=>'Truck Info'],
    ['url'=>url('/driver/notifications'), 'icon'=>'fas fa-bell', 'label'=>'Notifications'],
    ['url'=>url('/driver/documents'), 'icon'=>'fas fa-file-alt', 'label'=>'Documents'],
    
];
@endphp
<div class="sidebar-footer mt-auto" style="padding: 15px; color: #fff;">
    <label for="darkModeToggle" style="display: flex; align-items: center; cursor: pointer;">
        <i class="fas fa-moon me-2"></i>
        <span class="sidebar-text">Dark Mode</span>
        <input type="checkbox" id="darkModeToggle" style="margin-left: auto;">
    </label>
</div>
<div class="sidebar-footer mt-2" style="padding: 15px; color: #fff;">
    <label for="languageToggle" style="display: flex; align-items: center; cursor: pointer;">
        <i class="fas fa-language me-2"></i>
        <span class="sidebar-text" id="languageLabel">ES / EN</span>
        <input type="checkbox" id="languageToggle" style="margin-left: auto;">
    </label>
</div>
@foreach($links as $link)
    <a href="{{ $link['url'] }}">
        <i class="{{ $link['icon'] }}"></i> <span>{{ $link['label'] }}</span>
    </a>
@endforeach
