<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Dashboard | Loan Management System</title>
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
        .card-stats.leads .icon { background: var(--info); }
        .card-stats.pending .icon { background: var(--warning); }
        .card-stats.approved .icon { background: var(--success); }
        .card-stats.rejected .icon { background: var(--danger); }
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
        .table th {
            background: #f8f9fa;
            border: none;
            cursor: pointer;
        }
        .table td {
            vertical-align: middle;
        }
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
        }
        .form-control {
            border-radius: 0.5rem;
            transition: var(--transition);
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .alert {
            border-radius: 0.5rem;
        }
        .sort-icon {
            margin-left: 5px;
            font-size: 0.8em;
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
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block sidebar py-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Employee' }}&background=0D8ABC&color=fff" alt="Profile" class="profile-img mb-2">
                <h5>{{ auth()->user()->name ?? 'Employee Name' }}</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('employee/dashboard') ? 'active' : '' }}" href="" data-bs-toggle="tooltip" title="Dashboard Overview"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('employee/ViewCreateLead') ? 'active' : '' }}" href="{{ route('employee.ViewCreateLead') }}" data-bs-toggle="tooltip" title="View Leads"><i class="fas fa-user-plus"></i> View Leads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('employee/employeeCreateLead') ? 'active' : '' }}" href="{{ route('employee.employeeCreateLead') }}" data-bs-toggle="tooltip" title="Create Lead"><i class="fas fa-plus"></i> Create Lead</a>
                </li>
                <li class="nav-item mt-4">
                    <form method="POST" action="/logout" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link text-danger p-0" style="text-decoration: none;" data-bs-toggle="tooltip" title="Sign Out"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2 class="h3 mb-0 fw-bold" id="pageTitle">
                        @if (request()->is('employee/employeeCreateLead'))
                            Create Lead
                        @elseif (request()->is('employee/ViewCreateLead'))
                            View Leads
                        @else
                            Employee Dashboard
                        @endif
                    </h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search by name or ID..." id="searchInput">
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                </div>
            </div>

            <!-- Session Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div id="alertPlaceholder"></div>

            <!-- Content Area -->
            <div id="contentArea">
                @if (request()->is('employee/employeeCreateLead'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Create Lead</h5>
                        </div>
                        <div class="card-body">
                            <form id="createLeadForm" method="POST" action="/employee/leads" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="loan_amount" class="form-label">Loan Amount</label>
                                    <input type="number" class="form-control" id="loan_amount" name="loan_amount" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Create Lead</button>
                            </form>
                        </div>
                    </div>
                @elseif (request()->is('employee/ViewCreateLead'))
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Leads</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-3 align-middle">
                                    <thead>
                                        <tr>
                                            <th data-sort="id">Lead ID <i class="fas fa-sort sort-icon"></i></th>
                                            <th data-sort="name">Name <i class="fas fa-sort sort-icon"></i></th>
                                            <th data-sort="email">Contact <i class="fas fa-sort sort-icon"></i></th>
                                            <th data-sort="loan_amount">Loan Amount <i class="fas fa-sort sort-icon"></i></th>
                                            <th data-sort="status">Status <i class="fas fa-sort sort-icon"></i></th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="leadsTableBody">
                                        <!-- Populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-stats leads">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon me-3"><i class="fas fa-user-plus"></i></div>
                                    <div>
                                        <h6 class="card-title text-muted mb-1">Total Leads</h6>
                                        <h3 class="fw-bold mb-0" id="totalLeads">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-stats pending">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon me-3"><i class="fas fa-hourglass-half"></i></div>
                                    <div>
                                        <h6 class="card-title text-muted mb-1">Pending</h6>
                                        <h3 class="fw-bold mb-0" id="pendingLeads">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-stats approved">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon me-3"><i class="fas fa-check-circle"></i></div>
                                    <div>
                                        <h6 class="card-title text-muted mb-1">Approved</h6>
                                        <h3 class="fw-bold mb-0" id="approvedLeads">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card card-stats rejected">
                                <div class="card-body d-flex align-items-center">
                                    <div class="icon me-3"><i class="fas fa-times-circle"></i></div>
                                    <div>
                                        <h6 class="card-title text-muted mb-1">Rejected</h6>
                                        <h3 class="fw-bold mb-0" id="rejectedLeads">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Welcome to the Employee Dashboard. Use the sidebar to navigate.</p>
                @endif
            </div>

            <!-- Footer -->
            <footer class="pt-3 mt-4 text-muted border-top text-center small">
                © 2025 Loan Management System
            </footer>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // CSRF Token Setup for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Escape HTML to prevent XSS
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Show alert message
    function showAlert(message, type) {
        const alertPlaceholder = document.getElementById('alertPlaceholder');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alertPlaceholder.appendChild(alert);
        setTimeout(() => {
            if (alert.parentNode) alert.remove();
        }, 5000);
    }

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

    // Initialize Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Simulate navigation
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            if (!link.classList.contains('text-danger')) {
                e.preventDefault();
                const href = link.getAttribute('href');
                window.history.pushState({}, '', href);
                window.location.href = href; // Navigate to actual URL
            }
        });
    });

    // Prevent actual logout form submission for demo
    document.getElementById('logoutForm').addEventListener('submit', (e) => {
        e.preventDefault();
        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.href = '/login';
            } else {
                showAlert('Error logging out', 'danger');
            }
        }).catch(() => {
            showAlert('Network error during logout', 'danger');
        });
    });

    // Sample leads data (replace with AJAX fetch in production)
    const leads = [
        { id: 1, name: 'John Doe', email: 'john@example.com', phone: '123-456-7890', loan_amount: 5000, status: 'Pending' },
        { id: 2, name: 'Jane Smith', email: 'jane@example.com', phone: '098-765-4321', loan_amount: 10000, status: 'Approved' }
    ];

    // Populate leads table
    function populateLeadsTable(data) {
        const tbody = document.getElementById('leadsTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';
        data.forEach(lead => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${lead.id}</td>
                <td>${escapeHtml(lead.name)}</td>
                <td>${escapeHtml(lead.email)}<br>${escapeHtml(lead.phone)}</td>
                <td>$${lead.loan_amount.toLocaleString()}</td>
                <td><span class="badge bg-${lead.status === 'Approved' ? 'success' : lead.status === 'Pending' ? 'warning' : 'danger'}">${lead.status}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1 viewLead" data-id="${lead.id}"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-outline-danger deleteLead" data-id="${lead.id}"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Update dashboard stats
    function updateDashboardStats() {
        const total = leads.length;
        const pending = leads.filter(l => l.status === 'Pending').length;
        const approved = leads.filter(l => l.status === 'Approved').length;
        const rejected = leads.filter(l => l.status === 'Rejected').length;
        if (document.getElementById('totalLeads')) {
            document.getElementById('totalLeads').textContent = total;
            document.getElementById('pendingLeads').textContent = pending;
            document.getElementById('approvedLeads').textContent = approved;
            document.getElementById('rejectedLeads').textContent = rejected;
        }
    }

    // Handle create lead form submission
    const createLeadForm = document.getElementById('createLeadForm');
    if (createLeadForm) {
        createLeadForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(createLeadForm);
            fetch('/employee/leads', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    showAlert('Lead created successfully', 'success');
                    createLeadForm.reset();
                } else {
                    showAlert(data.message || 'Error creating lead', 'danger');
                }
            }).catch(() => {
                showAlert('Network error creating lead', 'danger');
            });
        });
    }

    // Initialize content
    window.addEventListener('DOMContentLoaded', () => {
        if (window.location.pathname === '/employee/ViewCreateLead') {
            populateLeadsTable(leads);
        } else if (window.location.pathname === '/employee/dashboard') {
            updateDashboardStats();
        }
    });
</script>
</body>
</html>