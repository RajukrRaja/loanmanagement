
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
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
        .card-stats.users .icon { background: var(--primary); }
        .card-stats.loans .icon { background: var(--warning); }
        .card-stats.revenue .icon { background: var(--danger); }
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
        .item-card.user { border-left: 4px solid var(--primary); }
        .item-card.user .progress-bar { background: var(--primary); }
        .item-card.loan { border-left: 4px solid var(--warning); }
        .item-card.loan .progress-bar { background: var(--warning); }
        .item-card.emi { border-left: 4px solid var(--success); }
        .item-card.emi .progress-bar { background: var(--success); }
        .item-card.lead { border-left: 4px solid var(--info); }
        .item-card.lead .progress-bar { background: var(--info); }
        .item-card.report { border-left: 4px solid var(--teal); }
        .item-card.report .progress-bar { background: var(--teal); }
        .item-card.accounting { border-left: 4px solid var(--danger); }
        .item-card.accounting .progress-bar { background: var(--danger); }
        .item-card.employee { border-left: 4px solid var(--purple); }
        .item-card.employee .progress-bar { background: var(--purple); }
        .item-card.kyc { border-left: 4px solid var(--success); }
        .item-card.kyc .progress-bar { background: var(--success); }
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
            content: '5';
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
        <nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block sidebar py-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Admin" alt="Profile" class="profile-img mb-2">
                <h5>Admin</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-bs-toggle="tooltip" title="Dashboard Overview"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Manage Users"><i class="fas fa-users"></i> Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-toggle="tooltip" title="Loan Operations"><i class="fas fa-money-check-alt"></i> Loans</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newLoanModal">Create Loan</a></li>
                        <li><a class="dropdown-item" href="#todaySection">View Loans</a></li>
                        <li><a class="dropdown-item" href="#todaySection">EMI Collections</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/employee/viewLeadDetails/${lead.lead_id}" data-bs-toggle="tooltip" title="Lead management"><i class="fas fa-user-plus"></i> Lead management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route ('admin.adminApprovedKycUserView')}}" data-bs-toggle="tooltip" title="View Reports"><i class="fas fa-chart-line"></i>Approved Kyc lead User</a>
                </li
                <li class="nav-item">
                    <a class="nav-link" href="{{route ('admin.adminApprovedEnachUserView')}}" data-bs-toggle="tooltip" title="Accounting Records"><i class="fas fa-book"></i>Approved for enach User </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Employee Monitoring"><i class="fas fa-user-clock"></i> Employee Monitoring</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="KYC & eNACH"><i class="fas fa-id-card"></i> KYC & eNACH</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#settingsSection" data-bs-toggle="tooltip" title="System Settings"><i class="fas fa-cogs"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link notification-badge" href="#" data-bs-toggle="tooltip" title="View Notifications"><i class="fas fa-bell"></i> Notifications</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="#" data-bs-toggle="tooltip" title="Sign Out"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2 class="h3 mb-0 fw-bold">Dashboard Overview</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search...">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUserModal"><i class="fas fa-plus"></i> Add User</button>
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                </div>
            </div>
            <div class="row mb-4 g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats users shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-users"></i></div>
                            <div>
                                <h6 class="mb-1">Total Users</h6>
                                <h4 class="mb-0 fw-bold">1,245</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> 2% this month</small>
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
                                <h4 class="mb-0 fw-bold">320</h4>
                                <small class="text-muted"><i class="fas fa-arrow-down text-danger"></i> 5 less</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats revenue shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon me-3"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <h6 class="mb-1">Revenue</h6>
                                <h4 class="mb-0 fw-bold">$45,000</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> 8% growth</small>
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
                                <h4 class="mb-0 fw-bold">45</h4>
                                <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> 10% this week</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newUserModal" data-bs-toggle="tooltip" title="Add New User"><i class="fas fa-user-plus me-2"></i>Add User</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-warning w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newLoanModal" data-bs-toggle="tooltip" title="Create New Loan"><i class="fas fa-file-invoice-dollar me-2"></i>New Loan</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-info w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#generateReportModal" data-bs-toggle="tooltip" title="Generate Report"><i class="fas fa-file-alt me-2"></i>Generate Report</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#todaySection" class="btn btn-outline-success w-100 py-3 shadow-sm" data-bs-toggle="tooltip" title="Manage KYC"><i class="fas fa-id-card me-2"></i>Manage KYC</a>
                </div>
            </div>
            <div class="modal fade" id="newUserModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newUserForm">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" id="newRole" class="form-control" required>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="admin">Admin</option>
                                        <option value="sub_admin">Sub Admin</option>
                                        <option value="region_head">Region Head</option>
                                        <option value="branch">Branch</option>
                                        <option value="sub_branch">Sub Branch</option>
                                        <option value="employee">Employee</option>
                                        <option value="customer">Customer</option>
                                        <option value="team_manager">Team Manager</option>
                                        <option value="telecaller">Telecaller</option>
                                        <option value="accountant">Accountant</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="newSubAdminIdGroup" style="display: none;">
                                    <label class="form-label">Sub Admin ID</label>
                                    <select name="sub_admin_id" id="newSubAdminId" class="form-control">
                                        <option value="SA0001">Sub Admin 1 (SA0001)</option>
                                        <option value="SA0002" selected>Sub Admin 2 (SA0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="newRegionIdGroup" style="display: none;">
                                    <label class="form-label">Region ID</label>
                                    <select name="region_id" id="newRegionId" class="form-control">
                                        <option value="RG0001" selected>Region 1 (RG0001)</option>
                                        <option value="RG0002">Region 2 (RG0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="newBranchIdGroup" style="display: none;">
                                    <label class="form-label">Branch ID</label>
                                    <select name="branch_id" id="newBranchId" class="form-control">
                                        <option value="BR0001">Branch 1 (BR0001)</option>
                                        <option value="BR0002">Branch 2 (BR0002)</option>
                                        <option value="BR0003">Branch 3 (BR0003)</option>
                                        <option value="GS0001">Branch GS0001 (GS0001)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="newEmployeeIdGroup" style="display: none;">
                                    <label class="form-label">Employee ID</label>
                                    <select name="employee_id" id="newEmployeeId" class="form-control">
                                        <option value="EMP0001" selected>Employee 1 (EMP0001)</option>
                                        <option value="EMP0002">Employee 2 (EMP0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="newTeamManagerIdGroup" style="display: none;">
                                    <label class="form-label">Team Manager ID</label>
                                    <select name="team_manager_id" id="newTeamManagerId" class="form-control">
                                        <option value="TM0001" selected>Team Manager 1 (TM0001)</option>
                                        <option value="TM0002">Team Manager 2 (TM0002)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editUserModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                <input type="hidden" name="userId" id="editUserId">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="editName" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" id="editEmail" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password (Leave blank to keep unchanged)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" id="editRole" class="form-control" required>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="admin">Admin</option>
                                        <option value="sub_admin">Sub Admin</option>
                                        <option value="region_head">Region Head</option>
                                        <option value="branch">Branch</option>
                                        <option value="sub_branch">Sub Branch</option>
                                        <option value="employee">Employee</option>
                                        <option value="customer">Customer</option>
                                        <option value="team_manager">Team Manager</option>
                                        <option value="telecaller">Telecaller</option>
                                        <option value="accountant">Accountant</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="editSubAdminIdGroup" style="display: none;">
                                    <label class="form-label">Sub Admin ID</label>
                                    <select name="sub_admin_id" id="editSubAdminId" class="form-control">
                                        <option value="SA0001">Sub Admin 1 (SA0001)</option>
                                        <option value="SA0002" selected>Sub Admin 2 (SA0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="editRegionIdGroup" style="display: none;">
                                    <label class="form-label">Region ID</label>
                                    <select name="region_id" id="editRegionId" class="form-control">
                                        <option value="RG0001" selected>Region 1 (RG0001)</option>
                                        <option value="RG0002">Region 2 (RG0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="editBranchIdGroup" style="display: none;">
                                    <label class="form-label">Branch ID</label>
                                    <select name="branch_id" id="editBranchId" class="form-control">
                                        <option value="BR0001">Branch 1 (BR0001)</option>
                                        <option value="BR0002">Branch 2 (BR0002)</option>
                                        <option value="BR0003">Branch 3 (BR0003)</option>
                                        <option value="GS0001">Branch GS0001 (GS0001)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="editEmployeeIdGroup" style="display: none;">
                                    <label class="form-label">Employee ID</label>
                                    <select name="employee_id" id="editEmployeeId" class="form-control">
                                        <option value="EMP0001" selected>Employee 1 (EMP0001)</option>
                                        <option value="EMP0002">Employee 2 (EMP0002)</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="editTeamManagerIdGroup" style="display: none;">
                                    <label class="form-label">Team Manager ID</label>
                                    <select name="team_manager_id" id="editTeamManagerId" class="form-control">
                                        <option value="TM0001" selected>Team Manager 1 (TM0001)</option>
                                        <option value="TM0002">Team Manager 2 (TM0002)</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="newLoanModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Create New Loan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newLoanForm">
                                <div class="mb-3">
                                    <label class="form-label">Borrower Name</label>
                                    <input type="text" name="borrower" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Loan Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Interest Rate (%)</label>
                                    <input type="number" step="0.01" name="interest" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tenure (Months)</label>
                                    <input type="number" name="tenure" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editLoanModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Loan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editLoanForm">
                                <input type="hidden" name="loanId">
                                <div class="mb-3">
                                    <label class="form-label">Borrower Name</label>
                                    <input type="text" name="borrower" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Loan Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Interest Rate (%)</label>
                                    <input type="number" step="0.01" name="interest" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tenure (Months)</label>
                                    <input type="number" name="tenure" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
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
                                        <option value="emi_status">EMI Status</option>
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
            <div class="time-section" id="settingsSection">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">System Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="settingsForm">
                            <div class="mb-3">
                                <label class="form-label">System Name</label>
                                <input type="text" name="systemName" class="form-control" value="Loan Management System">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Interest Rate (%)</label>
                                <input type="number" step="0.25" name="defaultInterest" class="form-control" value="5.5">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Max Loan Amount ($)</label>
                                <input type="number" name="maxLoanAmount" class="form-control" value="50000">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
            <section class="time-section today" id="todaySection">
                <div class="header">
                    <h4>Today’s Activities</h4>
                    <button class="toggle-btn" data-target="today-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="today-collapse">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card user shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user content-icon"></i>User: Jane Doe</h6>
                                    <p><strong>Email:</strong> jane@example.com</p>
                                    <p><strong>Role:</strong> Loan Officer</p>
                                    <p><strong>Joined:</strong> 2025-06-02</p>
                                    <div class="progress"><progress value="80" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-user-btn" data-user-id="U001" data-name="Jane Doe" data-email="jane@example.com" data-role="employee" data-branch-id="BR0003" data-sub-admin-id="" data-region-id="" data-employee-id="" data-team-manager-id="" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fas fa-edit"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card loan shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-bill-wave content-icon"></i>Loan: #LN1001</h6>
                                    <p><strong>Borrower:</strong> Jane Doe</p>
                                    <p><strong>Amount:</strong> $5,000</p>
                                    <p><strong>Issued:</strong> 2025-06-02</p>
                                    <div class="progress"><progress value="50" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-loan-btn" data-loan-id="LN1001" data-bs-toggle="modal" data-bs-target="#editLoanModal"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-warning disburse-loan" data-loan-id="LN1001" data-bs-toggle="tooltip" title="Disburse"><i class="fas fa-hand-holding-usd"></i></button>
                                    <button class="btn btn-sm btn-outline-danger close-loan" data-loan-id="LN1001" data-bs-toggle="tooltip" title="Close"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card emi shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-check-alt content-icon"></i>EMI: #EMI001</h6>
                                    <p><strong>Loan ID:</strong> #LN1001</p>
                                    <p><strong>Amount:</strong> $500</p>
                                    <p><strong>Due:</strong> 2025-07-01</p>
                                    <div class="progress"><progress value="0" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-success approve-emi" data-emi-id="EMI001" data-bs-toggle="tooltip" title="Approve"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-outline-danger reject-emi" data-emi-id="EMI001" data-bs-toggle="tooltip" title="Reject"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card lead shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user-plus content-icon"></i>Lead: #LD1001</h6>
                                    <p><strong>Name:</strong> John Smith</p>
                                    <p><strong>Contact:</strong> john@example.com</p>
                                    <p><strong>Assigned:</strong> Jane Doe</p>
                                    <div class="progress"><progress value="30" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-primary assign-lead" data-lead-id="LD1001" data-bs-toggle="tooltip" title="Assign"><i class="fas fa-user-check"></i></button>
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
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card accounting shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-book content-icon"></i>Transaction: #TX1001</h6>
                                    <p><strong>Type:</strong> Loan Disbursement</p>
                                    <p><strong>Amount:</strong> $5,000</p>
                                    <p><strong>Date:</strong> 2025-06-02</p>
                                    <div class="progress"><progress value="100" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card employee shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user-clock content-icon"></i>Employee: Jane Doe</h6>
                                    <p><strong>Role:</strong> Loan Officer</p>
                                    <p><strong>Tasks:</strong> 25</p>
                                    <p><strong>Last Active:</strong> 2025-06-02 10:00 AM</p>
                                    <div class="progress"><progress value="90" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card kyc shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-id-card content-icon"></i>Customer: #CUS1001</h6>
                                    <p><strong>Name:</strong> Jane Doe</p>
                                    <p><strong>KYC:</strong> Pending</p>
                                    <p><strong>eNACH:</strong> Pending</p>
                                    <div class="progress"><progress value="20" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-success verify-kyc" data-cus-id="CUS1001" data-bs-toggle="tooltip" title="Verify KYC"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-outline-info verify-enach" data-cus-id="CUS1001" data-bs-toggle="tooltip" title="Verify eNACH"><i class="fas fa-credit-card"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="time-section month">
                <div class="header">
                    <h4>Monthly Activities</h4>
                    <button class="toggle-btn" data-target="month-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="month-collapse">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card user shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user content-icon"></i>User: John Smith</h6>
                                    <p><strong>Email:</strong> john@example.com</p>
                                    <p><strong>Role:</strong> Clerk</p>
                                    <p><strong>Joined:</strong> 2025-06-01</p>
                                    <div class="progress"><progress value="70" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-user-btn" data-user-id="U002" data-name="John Smith" data-email="john@example.com" data-role="employee" data-branch-id="BR0003" data-sub-admin-id="" data-region-id="" data-employee-id="" data-team-manager-id="" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fas fa-edit"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card loan shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-bill-wave content-icon"></i>Loan: #LN1002</h6>
                                    <p><strong>Borrower:</strong> John Smith</p>
                                    <p><strong>Amount:</strong> $10,000</p>
                                    <p><strong>Issued:</strong> 2025-06-01</p>
                                    <div class="progress"><progress value="40" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-loan-btn" data-loan-id="LN1002" data-bs-toggle="modal" data-bs-target="#editLoanModal"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-warning disburse-loan" data-loan-id="LN1002" data-bs-toggle="tooltip" title="Disburse"><i class="fas fa-hand-holding-usd"></i></button>
                                    <button class="btn btn-sm btn-outline-danger close-loan" data-loan-id="LN1002" data-bs-toggle="tooltip" title="Close"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card emi shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-check-alt content-icon"></i>EMI: #EMI002</h6>
                                    <p><strong>Loan ID:</strong> #LN1002</p>
                                    <p><strong>Amount:</strong> $1,000</p>
                                    <p><strong>Due:</strong> 2025-07-01</p>
                                    <div class="progress"><progress value="0" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-success approve-emi" data-emi-id="EMI002" data-bs-toggle="tooltip" title="Approve"><i class="fas fa-check"></i></button>
                                    <button class="btn btn-sm btn-outline-danger reject-emi" data-emi-id="EMI002" data-bs-toggle="tooltip" title="Reject"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card lead shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user-plus content-icon"></i>Lead: #LD1002</h6>
                                    <p><strong>Name:</strong> Alice Johnson</p>
                                    <p><strong>Contact:</strong> alice@example.com</p>
                                    <p><strong>Assigned:</strong> Unassigned</p>
                                    <div class="progress"><progress value="20" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-primary assign-lead" data-lead-id="LD1002" data-bs-toggle="tooltip" title="Assign"><i class="fas fa-user-check"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="time-section year">
                <div class="header">
                    <h4>Yearly Activities</h4>
                    <button class="toggle-btn" data-target="year-collapse"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="time-content" id="year-collapse">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card user shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-user content-icon"></i>User: Alice Johnson</h6>
                                    <p><strong>Email:</strong> alice@example.com</p>
                                    <p><strong>Role:</strong> Admin</p>
                                    <p><strong>Joined:</strong> 2025-01-15</p>
                                    <div class="progress"><progress value="90" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-user-btn" data-user-id="U003" data-name="Alice Johnson" data-email="alice@example.com" data-role="admin" data-branch-id="" data-sub-admin-id="" data-region-id="" data-employee-id="" data-team-manager-id="" data-bs-toggle="modal" data-bs-target="#editUserModal"><i class="fas fa-edit"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card loan shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-money-bill-wave content-icon"></i>Loan: #LN1003</h6>
                                    <p><strong>Borrower:</strong> Alice Johnson</p>
                                    <p><strong>Amount:</strong> $15,000</p>
                                    <p><strong>Issued:</strong> 2025-01-15</p>
                                    <div class="progress"><progress value="30" max="100"></progress></div>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="View"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary edit-loan-btn" data-loan-id="LN1003" data-bs-toggle="modal" data-bs-target="#editLoanModal"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-warning disburse-loan" data-loan-id="LN1003" data-bs-toggle="tooltip" title="Disburse"><i class="fas fa-hand-holding-usd"></i></button>
                                    <button class="btn btn-sm btn-outline-danger close-loan" data-loan-id="LN1003" data-bs-toggle="tooltip" title="Close"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card item-card report shadow-sm">
                                <div class="content">
                                    <h6><i class="fas fa-chart-line content-icon"></i>Report: #RP1002</h6>
                                    <p><strong>Type:</strong> EMI Status</p>
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
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">System maintenance on <strong>2025-06-10</strong> at 2:00 AM.</li>
                        <li class="list-group-item">New loan policy update. <a href="#">Read more</a></li>
                        <li class="list-group-item">Welcome to our new branch in <strong>Downtown</strong>!</li>
                    </ul>
                </div>
            </div>
            <footer class="pt-3 mt-4 text-muted border-top text-center small">
                © 2025 Loan Management System
            </footer>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sample data for dropdowns
    const sampleData = {
        subAdmins: [
            { id: "SA0001", name: "Sub Admin 1" },
            { id: "SA0002", name: "Sub Admin 2" }
        ],
        regions: [
            { id: "RG0001", name: "Region 1" },
            { id: "RG0002", name: "Region 2" }
        ],
        branches: [
            { id: "BR0001", name: "Branch 1" },
            { id: "BR0002", name: "Branch 2" },
            { id: "BR0003", name: "Branch 3" },
            { id: "GS0001", name: "Branch GS" }
        ],
        employees: [
            { id: "EMP0001", name: "Employee 1" },
            { id: "EMP0002", name: "Employee 2" }
        ],
        teamManagers: [
            { id: "TM0001", name: "Team Manager 1" },
            { id: "TM0002", name: "Team Manager 2" }
        ]
    };

    // Populate dropdowns with pre-selected values
    function populateDropdown(selectElement, data, defaultValue = '') {
        selectElement.innerHTML = '';
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.name} (${item.id})`;
            if (item.id === defaultValue) option.selected = true;
            selectElement.appendChild(option);
        });
    }

    // Initialize dropdowns
    function initializeDropdowns(prefix) {
        const subAdminSelect = document.getElementById(`${prefix}SubAdminId`);
        const regionSelect = document.getElementById(`${prefix}RegionId`);
        const branchSelect = document.getElementById(`${prefix}BranchId`);
        const employeeSelect = document.getElementById(`${prefix}EmployeeId`);
        const teamManagerSelect = document.getElementById(`${prefix}TeamManagerId`);

        populateDropdown(subAdminSelect, sampleData.subAdmins, 'SA0002');
        populateDropdown(regionSelect, sampleData.regions, 'RG0001');
        populateDropdown(branchSelect, sampleData.branches);
        populateDropdown(employeeSelect, sampleData.employees, 'EMP0001');
        populateDropdown(teamManagerSelect, sampleData.teamManagers, 'TM0001');

        const roleSelect = document.getElementById(`${prefix}Role`);
        roleSelect.addEventListener('change', () => {
            const role = roleSelect.value;
            if (role === 'sub_branch') branchSelect.value = 'BR0002';
            if (role === 'employee') branchSelect.value = 'BR0003';
        });
    }

    initializeDropdowns('new');
    initializeDropdowns('edit');

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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    alert(successMsg);
                    if (modalId) bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                } else {
                    alert(errorMsg);
                }
            } catch (error) {
                alert('Network error');
            }
        });
    };

    // Dynamic Field Visibility
    function toggleConditionalFields(roleSelectId, groups) {
        const roleSelect = document.getElementById(roleSelectId);
        const {
            subAdminIdGroupId, regionIdGroupId,
            branchIdGroupId, employeeIdGroupId, teamManagerIdGroupId
        } = groups;

        const subAdminIdGroup = document.getElementById(subAdminIdGroupId);
        const regionIdGroup = document.getElementById(regionIdGroupId);
        const branchIdGroup = document.getElementById(branchIdGroupId);
        const employeeIdGroup = document.getElementById(employeeIdGroupId);
        const teamManagerIdGroup = document.getElementById(teamManagerIdGroupId);

        function updateFieldsVisibility() {
            const role = roleSelect.value;
            subAdminIdGroup.style.display = 'none';
            regionIdGroup.style.display = 'none';
            branchIdGroup.style.display = 'none';
            employeeIdGroup.style.display = 'none';
            teamManagerIdGroup.style.display = 'none';

            if (role === 'sub_branch' || role === 'employee') {
                branchIdGroup.style.display = 'block';
            }
            if (role === 'region_head' || role === 'team_manager') {
                subAdminIdGroup.style.display = 'block';
            }
            if (role === 'branch') {
                regionIdGroup.style.display = 'block';
            }
            if (role === 'customer') {
                employeeIdGroup.style.display = 'block';
            }
            if (role === 'telecaller') {
                teamManagerIdGroup.style.display = 'block';
            }
        }

        roleSelect.addEventListener('change', updateFieldsVisibility);
        updateFieldsVisibility();
    }

    toggleConditionalFields('newRole', {
        subAdminIdGroupId: 'newSubAdminIdGroup',
        regionIdGroupId: 'newRegionIdGroup',
        branchIdGroupId: 'newBranchIdGroup',
        employeeIdGroupId: 'newEmployeeIdGroup',
        teamManagerIdGroupId: 'newTeamManagerIdGroup'
    });

    toggleConditionalFields('editRole', {
        subAdminIdGroupId: 'editSubAdminIdGroup',
        regionIdGroupId: 'editRegionIdGroup',
        branchIdGroupId: 'editBranchIdGroup',
        employeeIdGroupId: 'editEmployeeIdGroup',
        teamManagerIdGroupId: 'editTeamManagerIdGroup'
    });

    // New User
    handleFormSubmit('newUserForm', '/api/users', 'POST', 'User added', 'Error adding user', 'newUserModal');

    // Edit User
    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById('editUserForm');
            document.getElementById('editUserId').value = btn.dataset.userId;
            document.getElementById('editName').value = btn.dataset.name;
            document.getElementById('editEmail').value = btn.dataset.email;
            document.getElementById('editRole').value = btn.dataset.role;

            const role = btn.dataset.role;
            const branchSelect = document.getElementById('editBranchId');
            if (role === 'sub_branch') branchSelect.value = 'BR0002';
            if (role === 'employee') branchSelect.value = 'BR0003';

            populateDropdown(document.getElementById('editSubAdminId'), sampleData.subAdmins, btn.dataset.subAdminId || 'SA0002');
            populateDropdown(document.getElementById('editRegionId'), sampleData.regions, btn.dataset.regionId || 'RG0001');
            populateDropdown(document.getElementById('editBranchId'), sampleData.branches, btn.dataset.branchId);
            populateDropdown(document.getElementById('editEmployeeId'), sampleData.employees, btn.dataset.employeeId || 'EMP0001');
            populateDropdown(document.getElementById('editTeamManagerId'), sampleData.teamManagers, btn.dataset.teamManagerId || 'TM0001');

            const roleSelect = document.getElementById('editRole');
            const event = new Event('change');
            roleSelect.dispatchEvent(event);
        });
    });
    handleFormSubmit('editUserForm', '/api/users/U001', 'PUT', 'User updated', 'Error updating user', 'editUserModal');

    // New Loan
    handleFormSubmit('newLoanForm', '/api/loans', 'POST', 'Loan created', 'Error creating loan', 'newLoanModal');

    // Edit Loan
    document.querySelectorAll('.edit-loan-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.item-card');
            const form = document.getElementById('editLoanForm');
            form.querySelector('[name="loanId"]').value = btn.dataset.loanId;
            form.querySelector('[name="borrower"]').value = card.querySelector('p:nth-child(2)').textContent.replace('Borrower: ', '').trim();
            form.querySelector('[name="amount"]').value = parseFloat(card.querySelector('p:nth-child(3)').textContent.replace('Amount: $', '').replace(',', ''));
            form.querySelector('[name="interest"]').value = 5.5;
            form.querySelector('[name="tenure"]').value = 12;
        });
    });
    handleFormSubmit('editLoanForm', '/api/loans/LN1001', 'PUT', 'Loan updated', 'Error updating loan', 'editLoanModal');

    // Disburse Loan
    document.querySelectorAll('.disburse-loan').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Disburse this loan?')) {
                try {
                    const response = await fetch(`/api/loans/${btn.dataset.loanId}/disburse`, { method: 'PATCH' });
                    alert(response.ok ? 'Loan disbursed' : 'Error disbursing loan');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // Close Loan
    document.querySelectorAll('.close-loan').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Close this loan?')) {
                try {
                    const response = await fetch(`/api/loans/${btn.dataset.loanId}/close`, { method: 'PATCH' });
                    alert(response.ok ? 'Loan closed' : 'Error closing loan');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // EMI Approval/Rejection
    document.querySelectorAll('.approve-emi').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Approve this EMI payment?')) {
                try {
                    const response = await fetch(`/api/emi/${btn.dataset.emiId}/approve`, { method: 'PATCH' });
                    alert(response.ok ? 'EMI approved' : 'Error approving EMI');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });
    document.querySelectorAll('.reject-emi').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Reject this EMI payment?')) {
                try {
                    const response = await fetch(`/api/emi/${btn.dataset.emiId}/reject`, { method: 'PATCH' });
                    alert(response.ok ? 'EMI rejected' : 'Error rejecting EMI');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // Lead Assignment
    document.querySelectorAll('.assign-lead').forEach(btn => {
        btn.addEventListener('click', async () => {
            const officer = prompt('Enter Loan Officer Name for Assignment:');
            if (officer) {
                try {
                    const response = await fetch(`/api/leads/${btn.dataset.leadId}/assign`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ assignedTo: officer })
                    });
                    alert(response.ok ? 'Lead assigned' : 'Error assigning lead');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // Generate Report
    handleFormSubmit('reportForm', '/api/reports', 'POST', 'Report generated', 'Error generating report', 'generateReportModal');

    // KYC Verification
    document.querySelectorAll('.verify-kyc').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Verify KYC for this customer?')) {
                try {
                    const response = await fetch(`/api/kyc/${btn.dataset.cusId}/verify`, { method: 'PATCH' });
                    alert(response.ok ? 'KYC verified' : 'Error verifying KYC');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // eNACH Verification
    document.querySelectorAll('.verify-enach').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Verify eNACH for this customer?')) {
                try {
                    const response = await fetch(`/api/enach/${btn.dataset.cusId}/verify`, { method: 'PATCH' });
                    alert(response.ok ? 'eNACH verified' : 'Error verifying eNACH');
                } catch (error) {
                    alert('Network error');
                }
            }
        });
    });

    // System Settings
    handleFormSubmit('settingsForm', '/api/settings', 'PUT', 'Settings updated', 'Error updating settings');
</script>
</body>
</html>