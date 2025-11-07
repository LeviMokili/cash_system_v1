{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Cash Transfer System')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://kit.fontawesome.com/a2d9d5a64b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* public/css/admin.css */
:root {
    --primary: #007bff;
    --secondary: #6c757d;
    --success: #28a745;
    --danger: #dc3545;
    --warning: #ffc107;
    --info: #17a2b8;
    --light: #f8f9fa;
    --dark: #343a40;
    --sidebar-width: 250px;
    --sidebar-collapsed: 70px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f6fa;
    color: #333;
}

.admin-layout {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: var(--sidebar-width);
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
    transition: all 0.3s ease;
    position: fixed;
    height: 100vh;
    z-index: 1000;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed);
}

.sidebar-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header h2 {
    font-size: 1.2rem;
    white-space: nowrap;
    overflow: hidden;
}

.toggle-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 5px;
}

.sidebar-menu {
    list-style: none;
    padding: 20px 0;
}

.sidebar-menu li {
    margin: 5px 0;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: rgba(255,255,255,0.1);
    color: white;
    border-left: 4px solid var(--primary);
}

.sidebar-menu a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.sidebar.collapsed .sidebar-menu a span {
    display: none;
}

.sidebar.collapsed .sidebar-header h2 {
    display: none;
}

/* Main Content */
.main-content-wrapper {
    flex: 1;
    margin-left: var(--sidebar-width);
    transition: margin-left 0.3s ease;
}

.sidebar.collapsed ~ .main-content-wrapper {
    margin-left: var(--sidebar-collapsed);
}

.main-content {
    padding: 20px;
}

.admin-header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Summary Widgets */
.summary-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.widget {
    background: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.widget:hover {
    transform: translateY(-5px);
}

.widget h3 {
    font-size: 2rem;
    margin-bottom: 5px;
    color: var(--dark);
}

.widget p {
    color: var(--secondary);
    font-weight: 500;
}

.widget.total { border-top: 4px solid var(--primary); }
.widget.pending { border-top: 4px solid var(--warning); }
.widget.completed { border-top: 4px solid var(--success); }
.widget.rejected { border-top: 4px solid var(--danger); }
.widget.users { border-top: 4px solid var(--info); }
.widget.today {
    background: linear-gradient(135deg, #007bff, #66b3ff);
    color: white;
}

.widget.today h3,
.widget.today p {
    color: white;
}

/* Chart Container */
.chart-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.chart-container h3 {
    margin-bottom: 20px;
    color: var(--dark);
}

/* Dashboard Tables */
.dashboard-content {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dashboard-content h3 {
    margin-bottom: 15px;
    color: var(--dark);
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.dashboard-table th,
.dashboard-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.dashboard-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: var(--dark);
}

.dashboard-table tr:hover {
    background-color: #f8f9fa;
}

/* Status Badges */
.status {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
}

.status.pending {
    background: #fff3cd;
    color: #856404;
}

.status.confirmed {
    background: #d1edff;
    color: #0c5460;
}

.status.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.status.completed {
    background: #d4edda;
    color: #155724;
}

/* Logout Link */
.logout {
    color: #e74c3c !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: var(--sidebar-collapsed);
    }
    
    .sidebar:not(.collapsed) {
        width: var(--sidebar-width);
    }
    
    .main-content-wrapper {
        margin-left: var(--sidebar-collapsed);
    }
    
    .summary-widgets {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-coins"></i> Admin</h2>
                <button class="toggle-btn" id="toggleBtn"><i class="fas fa-bars"></i></button>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();" 
                           class="logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
        }
    </script>
    
    @yield('scripts')
</body>
</html>