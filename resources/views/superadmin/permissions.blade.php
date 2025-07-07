<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Permission Management</title>
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
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table th:first-child {
            text-align: left;
        }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary-dark);
        }
        .btn-primary {
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            border: none;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .card {
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto;
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
                    <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile" class="profile-img mb-2">
                    <h5>Super Admin</h5>
                    <span class="badge bg-success">Online</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.dashboard') }}" data-bs-toggle="tooltip" title="Dashboard Overview">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown-container">
                        <a class="nav-link dropdown-toggle" href="#" id="masterUserDropdown" role="button" aria-expanded="false">
                            <i class="fas fa-users"></i> Master User
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="masterUserDropdown">
                            <li><a class="dropdown-item" href="{{ route('superadmin.manage_user') }}"><i class="fas fa-user-shield"></i> All User</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.adminUserView') }}"><i class="fas fa-user-cog"></i> Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.SubadminUserView') }}"><i class="fas fa-user-cog"></i> Sub Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.branchadminUserView') }}"><i class="fas fa-building"></i> Branch</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.subbranchadminUserView') }}"><i class="fas fa-building"></i> Sub Branch</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.teammanagerAdminUserView') }}"><i class="fas fa-user-tie"></i> Team Manager (Telecaller Team)</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.telecallerAdminUserView') }}"><i class="fas fa-headset"></i> Telecaller</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.AccountantAdminUserView') }}"><i class="fas fa-calculator"></i> Accountant</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.EmployeeAdminUserView') }}"><i class="fas fa-user"></i> Employee</a></li>
                            <li><a class="dropdown-item" href="{{ route('superadmin.CustomerAdminUserView') }}"><i class="fas fa-user-friends"></i> Customer</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('superadmin.permissions') }}" data-bs-toggle="tooltip" title="Manage Permissions">
                            <i class="fas fa-lock"></i> Permissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Manage Branches">
                            <i class="fas fa-building"></i> Manage Branches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-toggle="tooltip" title="Loan Operations">
                            <i class="fas fa-money-check-alt"></i> Loans
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newLoanModal">Create Loan</a></li>
                            <li><a class="dropdown-item" href="#">View Loans</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tooltip" title="View Reports">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Accounting Records">
                            <i class="fas fa-book"></i> Accounting
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Document Templates">
                            <i class="fas fa-file-alt"></i> Document Templates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tooltip" title="System Settings">
                            <i class="fas fa-cogs"></i> System Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link notification-badge" href="#" data-bs-toggle="tooltip" title="View Notifications">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="#" data-bs-toggle="tooltip" title="Sign Out">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- Main Content -->
            <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                        <h2 class="h3 mb-0 fw-bold">Permission Management</h2>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if (isset($error))
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endif
                        <form id="permissionForm" action="{{ route('superadmin.permissions.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Permission</th>
                                            @foreach ($roles as $role)
                                                <th class="text-center">{{ $role }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->name }}</td>
                                                @foreach ($roles as $role)
                                                    <td class="text-center">
                                                        <input type="checkbox"
                                                               name="permissions[{{ $role }}][{{ $permission->permission_id }}]"
                                                               {{ isset($matrix[$permission->permission_id][$role]) && $matrix[$permission->permission_id][$role] ? 'checked' : '' }}
                                                               class="form-check-input">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

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

        // Dropdown Toggle
        document.getElementById('masterUserDropdown').addEventListener('click', (e) => {
            e.preventDefault();
            const dropdown = document.querySelector('#masterUserDropdown + .dropdown-menu');
            const isShown = dropdown.classList.contains('show');
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => toggle.classList.remove('show'));
            if (!isShown) {
                dropdown.classList.add('show');
                e.target.classList.add('show');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.querySelector('#masterUserDropdown + .dropdown-menu');
            const toggle = document.getElementById('masterUserDropdown');
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                toggle.classList.remove('show');
            }
        });

        // Form Submission
        document.getElementById('permissionForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const data = { permissions: {} };

            // Build data structure
            const formData = new FormData(form);
            formData.forEach((value, key) => {
                const matches = key.match(/permissions\[(.+?)\]\[(.+?)\]/);
                if (matches) {
                    const role = matches[1];
                    const permId = matches[2];
                    if (!data.permissions[role]) {
                        data.permissions[role] = {};
                    }
                    data.permissions[role][permId] = value === 'on';
                }
            });

            try {
                const response = await fetch(form.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (response.ok) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert(result.message || 'Error updating permissions');
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
        });
    </script>
</body>
</html>