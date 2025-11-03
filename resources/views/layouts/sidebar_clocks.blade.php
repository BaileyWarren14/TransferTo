<script>
    /* =================== Sidebar toggle =================== */
function toggleSidebar(){
    const sidebar = document.getElementById('bottomSidebar');
    sidebar.classList.toggle('collapsed');
    sidebar.classList.toggle('expanded');
}

  // Variables globales desde Laravel
window.TIMERS_ROUTE = "{{ route('driver.timers') }}";
window.DRIVER_NAME = "{{ auth()->guard('driver')->user()->name ?? 'Driver' }}";

/* =================== Helpers =================== */
function secondsToHMS(s){
    s = Math.max(0, Math.floor(s));
    const h = Math.floor(s/3600).toString().padStart(2,'0');
    const m = Math.floor((s%3600)/60).toString().padStart(2,'0');
    const sec = Math.floor(s%60).toString().padStart(2,'0');
    return `${h}:${m}:${sec}`;
}

function showRestAlert(timerName){
    Swal.fire({
        icon:'warning',
        title:'Rest Required',
        text:`Your ${timerName} time has ended. Please take a break and change duty status.`,
        confirmButtonText:'OK'
    });
}


/* =================== Constantes =================== */
const DRIVE_TOTAL = 11*3600;
const SHIFT_TOTAL = 14*3600;
const CYCLE_TOTAL = 70*3600;
const STORAGE_KEY = 'driver_timers_state';
const SYNC_INTERVAL = 10000; // 10 segundos
const CLIENT_TICK_INTERVAL = 1000; // 1 segundo

/* =================== Estado global =================== */
let timers = {
    drive:{remaining:DRIVE_TOTAL,total:DRIVE_TOTAL,running:false,chart:null,labelId:'driveLabel',canvasId:'driveChart',name:'Drive'},
    shift:{remaining:SHIFT_TOTAL,total:SHIFT_TOTAL,running:false,chart:null,labelId:'shiftLabel',canvasId:'shiftChart',name:'Shift'},
    cycle:{remaining:CYCLE_TOTAL,total:CYCLE_TOTAL,running:false,chart:null,labelId:'cycleLabel',canvasId:'cycleChart',name:'Cycle'}
};

let currentStatus = 'OFF';
let chartsCreated = false;
let syncIntervalHandle = null;
let tickIntervalHandle = null;

/* =================== LocalStorage =================== */
function saveStateToStorage(){
    try {
        const state = {
            drive_remaining: timers.drive.remaining,
            shift_remaining: timers.shift.remaining,
            cycle_remaining: timers.cycle.remaining,
            current_status: currentStatus,
            timestamp: Date.now(),
            drive_running: timers.drive.running,
            shift_running: timers.shift.running,
            cycle_running: timers.cycle.running
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        console.log('💾 State saved');
    } catch(e) {
        console.warn('Could not save state:', e);
    }
}

function loadStateFromStorage(){
    try {
        const stored = localStorage.getItem(STORAGE_KEY);
        if(!stored) return null;
        
        const state = JSON.parse(stored);
        const age = Date.now() - (state.timestamp || 0);
        
        if(age > 30000) {
            console.log('Stored state too old, syncing with server');
            return null;
        }
        
        console.log('📦 Loading from localStorage (age: ' + Math.floor(age/1000) + 's)');
        return state;
    } catch(e) {
        console.warn('Could not load state:', e);
        return null;
    }
}

/* =================== Charts =================== */
function createDoughnutChart(canvasId, initialRemaining, total, color){
    const el = document.getElementById(canvasId);
    if(!el) return null;
    const ctx = el.getContext('2d');
    return new Chart(ctx,{
        type:'doughnut',
        data:{ 
            labels:['Remaining','Elapsed'], 
            datasets:[{ 
                data:[initialRemaining, Math.max(0,total-initialRemaining)], 
                backgroundColor:[color,'#e9ecef'], 
                borderWidth:0 
            }] 
        },
        options:{ 
            plugins:{legend:{display:false}}, 
            responsive:true, 
            maintainAspectRatio:false, 
            cutout:'70%' 
        }
    });
}

/* =================== Running states =================== */
function updateRunningStates(status){
    currentStatus = status;
    timers.drive.running = (status === 'D');
    timers.shift.running = ['D', 'ON', 'SB'].includes(status);
    timers.cycle.running = ['D', 'ON'].includes(status);
    
    console.log(`Status: ${status} | Drive: ${timers.drive.running} | Shift: ${timers.shift.running} | Cycle: ${timers.cycle.running}`);
}

/* =================== Update UI =================== */
function updateTimersUI(){
    Object.values(timers).forEach(timer => {
        if(timer.chart){
            const elapsed = Math.max(0, timer.total - timer.remaining);
            timer.chart.data.datasets[0].data = [timer.remaining, elapsed];
            timer.chart.update('none');
        }
        
        const labelEl = document.getElementById(timer.labelId);
        if(labelEl) labelEl.innerText = secondsToHMS(timer.remaining);
    });
    
    const shiftTimerEl = document.getElementById('shiftTimerText');
    if(shiftTimerEl) shiftTimerEl.innerText = secondsToHMS(timers.shift.remaining);
}

/* =================== Apply server data =================== */
function applyServerData(data){
    if(!data) return;

    console.log('📡 Server sync:', {
        drive: data.drive_remaining,
        shift: data.shift_remaining,
        cycle: data.cycle_remaining,
        status: data.current_status
    });

    const driveRem = Math.max(0, Math.min(Number(data.drive_remaining) || 0, DRIVE_TOTAL));
    const shiftRem = Math.max(0, Math.min(Number(data.shift_remaining) || 0, SHIFT_TOTAL));
    const cycleRem = Math.max(0, Math.min(Number(data.cycle_remaining) || 0, CYCLE_TOTAL));

    timers.drive.remaining = driveRem;
    timers.shift.remaining = shiftRem;
    timers.cycle.remaining = cycleRem;

    const status = (data.current_status || 'OFF').toString();
    updateRunningStates(status);

    if(!chartsCreated){
        timers.drive.chart = createDoughnutChart('driveChart', driveRem, DRIVE_TOTAL, '#007bff');
        timers.shift.chart = createDoughnutChart('shiftChart', shiftRem, SHIFT_TOTAL, '#28a745');
        timers.cycle.chart = createDoughnutChart('cycleChart', cycleRem, CYCLE_TOTAL, '#6c757d');
        chartsCreated = true;
    }

    updateTimersUI();
    
    const statusEl = document.getElementById('statusText');
    if(statusEl) statusEl.innerText = `Status: ${currentStatus}`;

    saveStateToStorage();
}

/* =================== Apply stored state =================== */
function applyStoredState(state){
    console.log('📦 Applying stored state');

    timers.drive.remaining = Math.max(0, Math.min(state.drive_remaining || DRIVE_TOTAL, DRIVE_TOTAL));
    timers.shift.remaining = Math.max(0, Math.min(state.shift_remaining || SHIFT_TOTAL, SHIFT_TOTAL));
    timers.cycle.remaining = Math.max(0, Math.min(state.cycle_remaining || CYCLE_TOTAL, CYCLE_TOTAL));

    updateRunningStates(state.current_status || 'OFF');

    if(!chartsCreated){
        timers.drive.chart = createDoughnutChart('driveChart', timers.drive.remaining, DRIVE_TOTAL, '#007bff');
        timers.shift.chart = createDoughnutChart('shiftChart', timers.shift.remaining, SHIFT_TOTAL, '#28a745');
        timers.cycle.chart = createDoughnutChart('cycleChart', timers.cycle.remaining, CYCLE_TOTAL, '#6c757d');
        chartsCreated = true;
    }

    updateTimersUI();
    
    const statusEl = document.getElementById('statusText');
    if(statusEl) statusEl.innerText = `Status: ${currentStatus}`;
}

/* =================== Client tick =================== */
function tickClient(){
    let needsUpdate = false;
    let shouldAlert = [];

    Object.values(timers).forEach(timer => {
        if(timer.running && timer.remaining > 0){
            timer.remaining = Math.max(0, timer.remaining - 1);
            needsUpdate = true;

            if(timer.remaining === 0){
                shouldAlert.push(timer.name);
            }
        }
    });

    if(needsUpdate){
        updateTimersUI();
        saveStateToStorage();
    }

    shouldAlert.forEach(name => showRestAlert(name));
}

/* =================== Sync with server =================== */
async function syncWithServer(){
    try {
        console.log('🔄 Fetching from:', window.TIMERS_ROUTE);
        
        const res = await fetch(window.TIMERS_ROUTE, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if(!res.ok) {
            throw new Error(`HTTP ${res.status}: ${res.statusText}`);
        }

        const data = await res.json();
        applyServerData(data);
        
    } catch(err) {
        console.error('❌ Sync error:', err);
    }
}

/* =================== Initialize =================== */
async function initTimers(){
    console.log('🚀 Initializing timer system...');
    console.log('Route:', window.TIMERS_ROUTE);
    
    const storedState = loadStateFromStorage();
    
    if(storedState){
        applyStoredState(storedState);
        syncWithServer();
    } else {
        await syncWithServer();
    }
    
    tickIntervalHandle = setInterval(tickClient, CLIENT_TICK_INTERVAL);
    syncIntervalHandle = setInterval(syncWithServer, SYNC_INTERVAL);
    
    document.addEventListener('visibilitychange', () => {
        if(document.visibilityState === 'visible'){
            console.log('👁️ Tab visible - syncing...');
            syncWithServer();
        }
    });
    
    console.log('✅ Timer system ready');
}

/* =================== Cleanup =================== */
window.addEventListener('beforeunload', () => {
    saveStateToStorage();
    if(tickIntervalHandle) clearInterval(tickIntervalHandle);
    if(syncIntervalHandle) clearInterval(syncIntervalHandle);
});

/* =================== Startup =================== */
document.addEventListener('DOMContentLoaded', function(){
    initTimers();
    
    // Saludo
    const name = window.DRIVER_NAME || 'Driver';
    const now = new Date();
    const hour = now.getHours();
    let greeting = '';

    if(hour >= 5 && hour < 12){
        greeting = 'Good morning';
    } else if(hour >= 12 && hour < 18){
        greeting = 'Good afternoon';
    } else if(hour >= 18 && hour < 22){
        greeting = 'Good evening';
    } else {
        greeting = 'Good night';
    }

    const greetingEl = document.getElementById('greeting');
    if(greetingEl) greetingEl.innerText = `${greeting}, ${name}!`;
});
</script>