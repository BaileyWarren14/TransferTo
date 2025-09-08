<!-- Sidebar -->
<style>
body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    background-color: #2a5298;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 60px;
}

.sidebar a {
    padding: 15px 30px;
    text-decoration: none;
    font-size: 18px;
    color: #fff;
    display: block;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #1e3c72;
}

.sidebar .closebtn {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 36px;
}

.hamburger {
    font-size: 30px;
    cursor: pointer;
    color: #2a5298;
    padding: 10px;
    position: fixed;
    top: 10px;
    left: 10px;
    z-index: 1100;
    transition: 0.3s;
}

.hamburger:hover {
    color: #1e3c72;
}

.main-content {
    transition: margin-left 0.5s;
    padding: 20px;
}
</style>

<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    <a href="{{ url('/dashboard') }}">Dashboard</a>
    <a href="{{ url('/new') }}">New</a>
    <a href="{{ url('/details') }}">Details</a>
</div>

<span class="hamburger" onclick="openSidebar()">&#9776;</span>

<script>
function openSidebar() {
    document.getElementById("mySidebar").style.width = "250px";
    document.querySelector('.main-content').style.marginLeft = "250px";
}
function closeSidebar() {
    document.getElementById("mySidebar").style.width = "0";
    document.querySelector('.main-content').style.marginLeft = "0";
}
</script>
