<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Event Management System</title>
    <meta name="description" content="Professional Event Management System - Manage events, orders, payments and more.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #0f172a;
            --sidebar-bg: #0f172a;
            --sidebar-width: 260px;
            --header-height: 64px;
            --card-border-radius: 16px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        /* === SIDEBAR === */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
            box-shadow: 0 4px 15px rgba(99,102,241,0.4);
        }

        .sidebar-brand .brand-text h5 {
            color: #f1f5f9; font-size: 14px; font-weight: 700;
            margin: 0; line-height: 1.2;
        }

        .sidebar-brand .brand-text span {
            color: #64748b; font-size: 11px; font-weight: 400;
        }

        .sidebar-nav { padding: 12px 0; }

        .nav-section-title {
            padding: 8px 20px 4px;
            font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            color: #475569;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: var(--transition);
            border-radius: 0;
            position: relative;
        }

        .sidebar-link:hover {
            color: #f1f5f9;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-link.active {
            color: #f1f5f9;
            background: linear-gradient(90deg, rgba(99,102,241,0.2) 0%, transparent 100%);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            border-radius: 0 2px 2px 0;
        }

        .sidebar-link i { font-size: 15px; min-width: 18px; }

        .sidebar-submenu { padding-left: 10px; }

        .sidebar-submenu .sidebar-link {
            font-size: 12.5px;
            padding: 7px 20px 7px 42px;
            color: #64748b;
        }

        .sidebar-submenu .sidebar-link:hover,
        .sidebar-submenu .sidebar-link.active { color: #a5b4fc; }

        /* === TOPBAR === */
        .topbar {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            z-index: 999;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: var(--transition);
        }

        .topbar .page-title {
            font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;
        }

        .topbar .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
        }

        .topbar-btn:hover { background: #6366f1; color: white; border-color: #6366f1; }

        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            width: 18px; height: 18px;
            background: #ef4444;
            border-radius: 50%;
            font-size: 10px; font-weight: 700;
            color: white; display: flex; align-items: center; justify-content: center;
            border: 2px solid white;
        }

        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 15px;
            cursor: pointer;
        }

        /* === MAIN CONTENT === */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
            transition: var(--transition);
        }

        /* === CARDS === */
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
            background: white;
            transition: var(--transition);
        }

        .card:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.08); }

        .card-header {
            border-bottom: 1px solid #f1f5f9;
            background: transparent;
            padding: 16px 20px;
            border-radius: var(--card-border-radius) var(--card-border-radius) 0 0 !important;
        }

        .card-header h6, .card-header h5, .card-header h4 {
            font-weight: 700; margin: 0; color: #0f172a;
        }

        .card-body { padding: 20px; }

        /* === STAT CARDS === */
        .stat-card {
            background: white;
            border-radius: var(--card-border-radius);
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: var(--transition);
            text-decoration: none; color: inherit;
        }

        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); color: inherit; }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }

        .stat-icon.purple { background: rgba(99,102,241,0.1); color: var(--primary); }
        .stat-icon.green { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-icon.amber { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.blue { background: rgba(6,182,212,0.1); color: var(--info); }
        .stat-icon.red { background: rgba(239,68,68,0.1); color: var(--danger); }

        .stat-label { font-size: 12px; color: #64748b; font-weight: 500; margin: 0; }
        .stat-value { font-size: 22px; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.1; }
        .stat-sub { font-size: 11px; color: #94a3b8; margin: 0; margin-top: 2px; }

        /* === BADGES === */
        .badge-status {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }

        .badge-pending { background: rgba(245,158,11,0.1); color: #d97706; }
        .badge-confirmed { background: rgba(59,130,246,0.1); color: #2563eb; }
        .badge-completed { background: rgba(16,185,129,0.1); color: #059669; }
        .badge-cancelled { background: rgba(239,68,68,0.1); color: #dc2626; }
        .badge-partial { background: rgba(245,158,11,0.1); color: #d97706; }
        .badge-paid { background: rgba(16,185,129,0.1); color: #059669; }
        .badge-draft { background: rgba(100,116,139,0.1); color: #64748b; }
        .badge-sent { background: rgba(59,130,246,0.1); color: #2563eb; }
        .badge-accepted { background: rgba(16,185,129,0.1); color: #059669; }
        .badge-rejected { background: rgba(239,68,68,0.1); color: #dc2626; }
        .badge-direct { background: rgba(239,68,68,0.1); color: #dc2626; }
        .badge-normal { background: rgba(100,116,139,0.1); color: #64748b; }

        /* === BUTTONS === */
        .btn { border-radius: 10px; font-weight: 600; font-size: 13.5px; transition: var(--transition); }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
        .btn-sm { border-radius: 8px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }

        /* === TABLES === */
        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 16px;
        }

        .table tbody td {
            padding: 12px 16px;
            color: #374151;
            font-size: 13.5px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover td { background: #fafafa; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* === FORMS === */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 13.5px;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .form-label { font-weight: 600; font-size: 12.5px; color: #374151; margin-bottom: 6px; }

        /* === PAGE HEADER === */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h4 { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; }
        .page-header .breadcrumb { margin: 0; font-size: 12px; }

        /* === ALERTS === */
        .alert { border-radius: 12px; border: none; font-size: 13.5px; }
        .alert-success { background: rgba(16,185,129,0.08); color: #059669; }
        .alert-danger { background: rgba(239,68,68,0.08); color: #dc2626; }
        .alert-info { background: rgba(6,182,212,0.08); color: #0891b2; }
        .alert-warning { background: rgba(245,158,11,0.08); color: #d97706; }

        /* === LINE ITEMS TABLE === */
        .line-items-table {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .line-items-table .table { margin: 0; }

        /* === MODAL === */
        .modal-content { border-radius: 16px; border: none; }
        .modal-header { border-bottom: 1px solid #f1f5f9; padding: 20px 24px 16px; }
        .modal-header h5 { font-weight: 700; color: #0f172a; }
        .modal-footer { border-top: 1px solid #f1f5f9; padding: 16px 24px 20px; }

        /* === CUSTOM SCROLLBAR === */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .topbar, .main-content { margin-left: 0; left: 0; }
        }

        /* === SUMMARY BOX === */
        .summary-box {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: var(--card-border-radius);
            padding: 24px;
            color: white;
        }

        /* === NOTIFICATION DOT === */
        .notification-dot {
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* === DROPDOWN === */
        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 6px;
        }

        .dropdown-item {
            border-radius: 8px;
            font-size: 13.5px;
            padding: 8px 12px;
            color: #374151;
        }

        .dropdown-item:hover { background: #f8fafc; color: #6366f1; }

        /* === SIDEBAR TOGGLE === */
        .sidebar-collapse .sidebar-link span { display: none; }

        /* === PRINT STYLES === */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-content { margin: 0 !important; padding: 20px !important; }
        }
    </style>

    @yield('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <div class="brand-icon"><i class="bi bi-calendar-event-fill"></i></div>
        <div class="brand-text">
            <h5>EventPro</h5>
            <span>Management System</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <!-- Masters -->
        <div class="nav-section-title">Masters</div>
        <a href="{{ route('event-categories.index') }}" class="sidebar-link {{ request()->routeIs('event-categories.*') ? 'active' : '' }}">
            <i class="bi bi-bookmark-fill"></i>
            <span>Event Categories</span>
        </a>
        <a href="{{ route('event-sub-categories.index') }}" class="sidebar-link {{ request()->routeIs('event-sub-categories.*') ? 'active' : '' }}">
            <i class="bi bi-collection-fill"></i>
            <span>Sub-Categories</span>
        </a>
        <a href="{{ route('services.index') }}" class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i>
            <span>Services</span>
        </a>

        <!-- Customers -->
        <div class="nav-section-title">Customers</div>
        <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Customers</span>
        </a>

        <!-- Events -->
        <div class="nav-section-title">Events</div>
        <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.index') || request()->routeIs('events.show') || request()->routeIs('events.edit') ? 'active' : '' }}">
            <i class="bi bi-calendar2-event-fill"></i>
            <span>All Events</span>
        </a>
        <a href="{{ route('events.create') }}" class="sidebar-link {{ request()->routeIs('events.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Register Event</span>
        </a>
        <a href="{{ route('calendar') }}" class="sidebar-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i>
            <span>Calendar</span>
        </a>

        <!-- Orders -->
        <div class="nav-section-title">Orders</div>
        <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('quotations.index') }}" class="sidebar-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Quotations</span>
        </a>

        <!-- Finance -->
        <div class="nav-section-title">Finance</div>
        <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Payments</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Expenses</span>
        </a>
        <a href="{{ route('cash-book') }}" class="sidebar-link {{ request()->routeIs('cash-book') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i>
            <span>Cash Book</span>
        </a>
        <a href="{{ route('bank-book') }}" class="sidebar-link {{ request()->routeIs('bank-book') ? 'active' : '' }}">
            <i class="bi bi-bank2"></i>
            <span>Bank Book</span>
        </a>
        <a href="{{ route('bank-transactions.index') }}" class="sidebar-link {{ request()->routeIs('bank-transactions.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i>
            <span>Transactions</span>
        </a>

        <!-- Reports -->
        <div class="nav-section-title">Reports</div>
        <a href="{{ route('reports.order-pl') }}" class="sidebar-link {{ request()->routeIs('reports.order-pl') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Order P&L</span>
        </a>
        <a href="{{ route('reports.monthly-pl') }}" class="sidebar-link {{ request()->routeIs('reports.monthly-pl') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Monthly P&L</span>
        </a>
        <a href="{{ route('reports.customer-credit') }}" class="sidebar-link {{ request()->routeIs('reports.customer-credit') ? 'active' : '' }}">
            <i class="bi bi-person-check-fill"></i>
            <span>Credit Report</span>
        </a>
        <a href="{{ route('reports.order-history') }}" class="sidebar-link {{ request()->routeIs('reports.order-history') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Order History</span>
        </a>
    </div>
</nav>

<!-- Topbar -->
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="topbar-btn border-0" onclick="toggleSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
        <h6 class="page-title d-none d-md-block">@yield('page-title', 'Dashboard')</h6>
    </div>
    <div class="topbar-right">
        <!-- Notifications -->
        <a href="{{ route('notifications.index') }}" class="topbar-btn" title="Notifications">
            <i class="bi bi-bell-fill"></i>
            @php
                $notifCount = \App\Models\Event::whereDate('event_date', \Carbon\Carbon::today()->addDays(7))->where('notification_7_sent', false)->where('status', '!=', 'cancelled')->count()
                    + \App\Models\Event::whereDate('event_date', \Carbon\Carbon::today()->addDays(3))->where('notification_3_sent', false)->where('status', '!=', 'cancelled')->count();
            @endphp
            @if($notifCount > 0)
                <span class="notification-badge">{{ $notifCount }}</span>
            @endif
        </a>
        <!-- New Event Quick Link -->
        <a href="{{ route('events.create') }}" class="topbar-btn" title="New Event">
            <i class="bi bi-plus-lg"></i>
        </a>
        <!-- User -->
        <div class="user-avatar" title="Admin">A</div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-info-circle-fill"></i>
            {{ session('info') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }

    // CSRF setup for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@stack('scripts')
@yield('scripts')
</body>
</html>
