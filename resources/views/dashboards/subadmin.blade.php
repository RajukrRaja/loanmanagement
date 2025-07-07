<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #22c55e;
            --warning: #facc15;
            --danger: #ef4444;
            --info: #38bdf8;
            --purple: #8b5cf6;
            --teal: #14b8a6;
            --bg-light: #f8fafc;
            --bg-dark: #1e293b;
            --text-dark: #1e293b;
            --text-muted: #6b7280;
            --shadow: 0 6px 24px rgba(0,0,0,0.1);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [data-theme="dark"] {
            --bg-light: #1e293b;
            --bg-dark: #111827;
            --text-dark: #f1f5f9;
            --text-muted: #9ca3af;
        }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            transition: var(--transition);
            overflow-x: hidden;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-dark);
            color: #f1f5f9;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
            transition: var(--transition);
            position: sticky;
            top: 0;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0.3rem 0.5rem;
            padding: 0.8rem 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
            transform: translateX(4px);
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .profile-img:hover {
            transform: scale(1.05);
        }
        .sidebar h5 {
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .badge.bg-success {
            background: linear-gradient(90deg, var(--success) 60%, #16a34a 100%) !important;
            box-shadow: var(--shadow-sm);
        }
        .card-stats {
            border-radius: 1rem;
            background: #fff;
            transition: var(--transition);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .card-stats:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        .card-stats .icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .card-stats.employees .icon { background: var(--primary); }
        .card-stats.loans .icon { background: var(--warning); }
        .card-stats.emi .icon { background: var(--success); }
        .card-stats.leads .icon { background: var(--info); }
        .card-header {
            background: transparent !important;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
        }
        .search-bar {
            max-width: 320px;
            border-radius: 2rem;
            border: 1px solid #e5e7eb;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .search-bar:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            transform: scale(1.02);
        }
        .btn-primary, .btn-outline-primary {
            border-radius: 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: var(--primary);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }
        .btn-outline-success:hover, .btn-outline-success:focus {
            background: var(--success);
            color: #fff;
        }
        .btn-outline-warning:hover, .btn-outline-warning:focus {
            background: var(--warning);
            color: #fff;
        }
        .btn-outline-info:hover, .btn-outline-info:focus {
            background: var(--info);
            color: #fff;
        }
        .list-group-item {
            background: transparent;
            border: none;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem;
            transition: var(--transition);
        }
        .list-group-item:hover {
            background: rgba(37,99,235,0.05);
        }
        .item-card {
            border-radius: 1rem;
            background: #fff;
            box-shadow: var(--shadow);
            padding: 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #e5e7eb;
            position: relative;
        }
        .item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-color: var(--primary);
        }
        .item-card .content {
            flex: 1;
        }
        .item-card .actions {
            display: flex;
            gap: 0.5rem;
        }
        .item-card h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .item-card p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }
        .item-card .progress {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.5rem;
        }
        .item-card.employee { border-left: 4px solid var(--purple); }
        .item-card.employee .progress-bar { background: var(--purple); }
        .item-card.loan { border-left: 4px solid var(--warning); }
        .item-card.loan .progress-bar { background: var(--warning); }
        .item-card.emi { border-left: 4px solid var(--success); }
        .item-card.emi .progress-bar { background: var(--success); }
        .item-card.lead { border-left: 4px solid var(--info); }
        .item-card.lead .progress-bar { background: var(--info); }
        .item-card.report { border-left: 4px solid var(--teal); }
        .item-card.report .progress-bar { background: var(--teal); }
        .btn-sm {
            border-radius: 1rem;
            padding: 0.3rem 0.7rem;
            font-size: 0.8rem;
        }
        .btn-sm:hover {
            transform: scale(1.1);
        }
        .sidebar-toggle {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .theme-toggle {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            background: var(--bg-dark);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .theme-toggle:hover {
            background: var(--primary);
            transform: rotate(20deg);
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }
        .time-section {
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            transition: var(--transition);
            background: #fff;
            box-shadow: var(--shadow);
        }
        .time-section.today {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
        }
        .time-section.month {
            background: linear-gradient(135deg, #fefce8 0%, #fffbeb 100%);
        }
        .time-section.year {
            background: linear-gradient(135deg, #f0fdf4 0%, #f7fee7 100%);
        }
        .time-section .header {
            position: sticky;
            top: 0;
            background: inherit;
            z-index: 1;
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .time-section h4 {
            font-weight: 700;
            letter-spacing: 0.02em;
            margin: 0;
        }
        .time-section .toggle-btn {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.2rem;
        }
        .content-icon {
            font-size: 1.1rem;
            margin-right: 0.5rem;
            color: var(--text-muted);
        }
        .modal-content {
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }
        .modal-header {
            border-bottom: 1px solid #e5e7eb;
        }
        .tooltip-inner {
            background: var(--bg-dark);
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
        }
        .notification-badge {
            position: relative;
        }
        .notification-badge::after {
            content: '3';
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger);
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -240px;
                width: 240px;
                z-index: 1040;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            main {
                margin-left: 0 !important;
            }
        }
        @media (min-width: 992px) {
            .sidebar-toggle {
                display: none;
            }
        }
        @media (max-width: 575.98px) {
            .search-bar {
                max-width: 100%;
            }
            .item-card {
                flex-direction: column;
                align-items: flex-start;
            }
            .item-card .actions {
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block sidebar py-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Sub+Admin" alt="Profile" class="profile-img mb-2">
                <h5>Sub Admin</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-bs-toggle="tooltip" title="Dashboard Overview"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Manage Employees"><i class="fas fa-users"></i> Manage Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-toggle="tooltip" title="Loan Operations"><i class="fas fa-money-check-alt"></i> Loans</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#todaySection">Loan Approvals</a></li>
                        <li><a class="dropdown-item" href="#todaySection">EMI Collections</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Lead Allocation"><i class="fas fa-user-plus"></i> Lead Allocation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="View Reports"><i class="fas fa-chart-line"></i> Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#creditBureauSection" data-bs-toggle="tooltip" title="Credit Bureau Export"><i class="fas fa-upload"></i> Credit Bureau Export</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link notification-badge" href="#" data-bs-toggle="tooltip" title="View Notifications"><i class="fas fa-bell"></i> Notifications</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="{{ route('logout') }}" data-bs-toggle="tooltip" title="Sign Out"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <!-- Main Content -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2 class="h3 mb-0 fw-bold">Sub Admin Dashboard</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search...">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEmployeeModal"><i class="fas fa-plus"></i> Add Employee</button>
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="row mb-4 g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats employees shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-users"></i></div>
                            <div>
                                <h6 class="mb-1">Sub Admins</h6>
                                <h4 class="mb-0 fw-bold">{{ $subAdmins->count() }}</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> {{ $subAdmins->count() }} active</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats loans shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-money-bill-wave"></i></div>
                            <div>
                                <h6 class="mb-1">Active Loans</h6>
                                <h4 class="mb-0 fw-bold">80</h4>
                                <small class="text-muted"><i class="fas fa-arrow-down text-danger"></i> 2 less</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats emi shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-hand-holding-usd"></i></div>
                            <div>
                                <h6 class="mb-1">EMI Collected</h6>
                                <h4 class="mb-0 fw-bold">$8,500</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> 5% this month</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats leads shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <h6 class="mb-1">New Leads</h6>
                                <h4 class="mb-0 fw-bold">15</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> 3 this week</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newEmployeeModal" data-bs-toggle="tooltip" title="Add New Employee"><i class="fas fa-user-plus me-2"></i>Add Employee</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-success w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#assignLeadModal" data-bs-toggle="tooltip" title="Assign Lead"><i class="fas fa-user-check me-2"></i>Assign Lead</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-info w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#generateReportModal" data-bs-toggle="tooltip" title="Generate Report"><i class="fas fa-file-alt me-2"></i>Generate Report</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#creditBureauModal" data-bs-toggle="tooltip" title="Credit Bureau Export"><i class="fas fa-upload me-2"></i>Credit Export</a>
                </div>
            </div>
            <!-- Modals -->
            <div class="modal fade" id="newEmployeeModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Add New Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newEmployeeForm" action="" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="employee">Employee</option>
                                        <option value="team_manager">Team Manager</option>
                                        <option value="telecaller">Telecaller</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="assignLeadModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Assign Lead</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="assignLeadForm">
                                <div class="mb-3">
                                    <label class="form-label">Lead ID</label>
                                    <input type="text" name="leadId" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Assign To</label>
                                    <select name="employee" class="form-control" required>
                                        @foreach ($subAdmins as $subAdmin)
                                            <option value="{{ $subAdmin->id }}">{{ $subAdmin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Assign</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="generateReportModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Generate Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="reportForm">
                                <div class="mb-3">
                                    <label class="form-label">Report Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="loan_summary">Loan Summary</option>
                                        <option value="emi_collection">EMI Collection</option>
                                        <option value="lead_performance">Lead Performance</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Range</label>
                                    <input type="text" name="dateRange" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="creditBureauModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Credit Bureau Export</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="creditBureauForm">
                                <div class="mb-3">
                                    <label class="form-label">Bureau</label>
                                    <select name="bureau" class="form-control" required>
                                        <option value="CIBIL">CIBIL</option>
                                        <option value="Experian">Experian</option>
                                        <option value="Equifax">Equifax</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Range</label>
                                    <input type="text" name="dateRange" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Export</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Credit Bureau Export Section -->
            <div class="card mb-4 shadow-sm" id="creditBureauSection">
                <div class="card-header">
                    <h5 class="mb-0">Credit Bureau Export</h5>
                </div>
                <div class="card-body">
                    <form id="creditBureauExportForm">
                        <div class="mb-3">
                            <label class="form-label">Bureau</label>
                            <select name="bureau" class="form-control" required>
                                <option value="CIBIL">CIBIL</option>
                                <option value="Experian">Experian</option>
                                <option value="Equifax">Equifax</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <input type="text" name="dateRange" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                </div>
            </div>
            <!-- Today Section -->
            <section class="time-section today" id="todaySection">
                <div class="header">
                    <h4>Today’s Sub-Admins</h4>
                    <button class="toggle-btn" data-target="today-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="today-collapse">
                    <div class="row g-4">
                        @forelse ($subAdmins as $subAdmin)
                            <div class="col-lg-3 col-md-6">
                                <div class="card item-card employee shadow-sm">
                                    <div class="content">
                                        <h6><i class="fas fa-user content-icon"></i>Sub-Admin: {{ $subAdmin->name }}</h6>
                                        <p><strong>ID:</strong> #{{ $subAdmin->id }}</p>
                                        <p><strong>Email:</strong> {{ $subAdmin->email }}</p>
                                        <p><strong>Role:</strong> {{ $subAdmin->role }}</p>
                                        <div class="progress"><progress value="80" max="100"></progress></div>
                                    </div>
                                    <div class="actions">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>No sub-admins available.</p>
                            </div>
                        @endforelse
                        <!-- Static Data for Other Items -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card loan shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-bill-wave content-icon"></i>Loan: #LN1002</h6>
                                    <p><strong>Borrower:</strong> John Smith</p>
                                    <p><strong>Amount:</strong> $3,200</p>
                                    <p><strong>Date:</strong> 2025-06-02</p>
                                    <div class="progress"><progress value="40" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-success approve-loan" data-loan-id="LN1002" data-bs-toggle="tooltip" title="Approve"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-outline-danger reject-loan" data-loan-id="LN1002" data-bs-toggle="tooltip" title="Reject"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card emi shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-hand-holding-usd content-icon"></i>EMI: #EMI001</h6>
                                    <p><strong>Loan ID:</strong> #LN1001</p>
                                    <p><strong>Amount:</strong> $500</p>
                                    <p><strong>Due:</strong> 2025-06-15</p>
                                    <div class="progress"><progress value="0" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card lead shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user-plus content-icon"></i>Lead: #LD100</h6>
                                    <p><strong>Name:</strong> Mary Johnson</p>
                                    <p><strong>Contact:</strong> mary@example.com</p>
                                    <p><strong>Assigned:</strong> Unassigned</p>
                                    <div class="progress"><progress value="20" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-primary assign-lead" data-lead-id="LD100" data-bs-toggle="modal" data-bs-target="#assignLeadModal" data-bs-toggle="tooltip" title="Assign"><i class="fas fa-user-check"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card report shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-chart-line content-icon"></i>Report: #RP1001</h6>
                                    <p><strong>Type:</strong> Loan Summary</p>
                                    <p><strong>Range:</strong> 2025-05-01 to 2025-06-02</p>
                                    <p><strong>Generated:</strong> 2025-06-02</p>
                                    <div class="progress"><progress value="100" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Download"><i class="fas fa-download"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Month Section -->
            <section class="time-section month">
                <div class="header">
                    <h4>Monthly Activities (Downtown)</h4>
                    <button class="toggle-btn" data-target="month-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="month-collapse">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card employee shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user content-icon"></i>Employee: Sarah Lee</h6>
                                    <p><strong>ID:</strong> #E1002</p>
                                    <p><strong>Email:</strong> sarah@example.com</p>
                                    <p><strong>Role:</strong> Clerk</p>
                                    <div class="progress"><progress value="70" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card loan shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-bill-wave content-icon"></i>Loan: #LN1003</h6>
                                    <p><strong>Borrower:</strong> Sarah Lee</p>
                                    <p><strong>Amount:</strong> $4,000</p>
                                    <p><strong>Date:</strong> 2025-06-01</p>
                                    <div class="progress"><progress value="30" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-success approve-loan" data-loan-id="LN1003" data-bs-toggle="tooltip" title="Approve"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-outline-danger reject-loan" data-loan-id="LN1003" data-bs-toggle="tooltip" title="Reject"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card lead shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user-plus content-icon"></i>Lead: #LD101</h6>
                                    <p><strong>Name:</strong> Alice Brown</p>
                                    <p><strong>Contact:</strong> alice@example.com</p>
                                    <p><strong>Assigned:</strong> John Smith</p>
                                    <div class="progress"><progress value="50" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-primary assign-lead" data-lead-id="LD101" data-bs-toggle="modal" data-bs-target="#assignLeadModal" data-bs-toggle="tooltip" title="Reassign"><i class="fas fa-user-check"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Year Section -->
            <section class="time-section year">
                <div class="header">
                    <h4>Yearly Activities (Downtown)</h4>
                    <button class="toggle-btn" data-target="year-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="year-collapse">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card employee shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user content-icon"></i>Employee: Alice Brown</h6>
                                    <p><strong>ID:</strong> #E1003</p>
                                    <p><strong>Email:</strong> alice@example.com</p>
                                    <p><strong>Role:</strong> Loan Officer</p>
                                    <div class="progress"><progress value="90" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card report shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-chart-line content-icon"></i>Report: #RP1002</h6>
                                    <p><strong>Type:</strong> EMI Collection</p>
                                    <p><strong>Range:</strong> 2025-01-01 to 2025-12-31</p>
                                    <p><strong>Generated:</strong> 2025-01-15</p>
                                    <div class="progress"><progress value="100" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Download"><i class="fas fa-download"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Announcements -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Branch meeting scheduled for <strong>2025-06-05</strong> at 10:00 AM.</li>
                        <li class="list-group-item">New EMI collection policy update. <a href="#">Read more</a></li>
                        <li class="list-group-item">Employee training session on <strong>2025-06-10</strong>.</li>
                    </ul>
                </div>
            </div>
            <!-- Footer -->
            <footer class="pt-3 mt-4 text-muted border-top text-center small">
                © 2025 Loan Management System. All rights reserved.
            </footer>
        </main>
    </div>
</div>
<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        document.getElementById('sidebarMenu').classList.toggle('show');
    });

    // Theme Toggle
    document.getElementById('themeToggle').addEventListener('click', () => {
        const isDark = document.body.getAttribute('data-theme') === 'dark';
        document.body.setAttribute('data-theme', isDark ? 'light' : 'dark');
        document.getElementById('themeToggle').innerHTML = `<i class="fas fa-${isDark ? 'moon' : 'sun'}"></i>`;
    });

    // Section Toggle
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            const isCollapsed = target.style.display === 'none';
            target.style.display = isCollapsed ? 'block' : 'none';
            btn.innerHTML = `<i class="fas fa-chevron-${isCollapsed ? 'down' : 'up'}"></i>`;
        });
    });

    // Form Handlers
    const handleFormSubmit = async (formId, url, method, successMsg, errorMsg, modalId) => {
        const form = document.getElementById(formId);
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            try {
                const response = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    alert(successMsg);
                    if (modalId) bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                    window.location.reload();
                } else {
                    alert(errorMsg);
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        });
    };

    // New Employee
    handleFormSubmit('newEmployeeForm', '', 'POST', 'Employee added successfully', 'Error adding employee', 'newEmployeeModal');

    // Assign Lead
    document.querySelectorAll('.assign-lead').forEach(btn => {
        btn.addEventListener('click', () => {
            const leadId = btn.dataset.leadId;
            const form = document.getElementById('assignLeadForm');
            form.querySelector('[name="leadId"]').value = leadId;
        });
    });
    handleFormSubmit('assignLeadForm', '/api/leads/LD100/assign', 'PATCH', 'Lead assigned successfully', 'Error assigning lead', 'assignLeadModal');

    // Generate Report
    handleFormSubmit('reportForm', '/api/reports', 'POST', 'Report generated successfully', 'Error generating report', 'generateReportModal');

    // Credit Bureau Export
    handleFormSubmit('creditBureauForm', '/api/credit-bureau/export', 'POST', 'Credit bureau data exported successfully', 'Error exporting data', 'creditBureauModal');
    handleFormSubmit('creditBureauExportForm', '/api/credit-bureau/export', 'POST', 'Credit bureau data exported successfully', 'Error exporting data');

    // Approve Loan
    document.querySelectorAll('.approve-loan').forEach(btn => {
        btn.addEventListener('click', async () => {
            const loanId = btn.dataset.loanId;
            if (confirm('Approve this loan?')) {
                try {
                    const response = await fetch(`/api/loans/${loanId}/approve`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
                    });
                    alert(response.ok ? 'Loan approved successfully' : 'Error approving loan');
                } catch (error) {
                    alert('Network error: ' + error.message);
                }
            }
        });
    });

    // Reject Loan
    document.querySelectorAll('.reject-loan').forEach(btn => {
        btn.addEventListener('click', async () => {
            const loanId = btn.dataset.loanId;
            if (confirm('Reject this loan?')) {
                try {
                    const response = await fetch(`/api/loans/${loanId}/reject`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
                    });
                    alert(response.ok ? 'Loan rejected successfully' : 'Error rejecting loan');
                } catch (error) {
                    alert('Network error: ' + error.message);
                }
            }
        });
    });
</script>
</body>
</html>