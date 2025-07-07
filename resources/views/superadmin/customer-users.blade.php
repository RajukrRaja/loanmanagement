@extends('superadmin.superadmin')

@section('title', 'Telecaller Admin Users')

@section('styles')
<style>
    /* CSS Variables */
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --success: #22c55e;
        --warning: #facc15;
        --danger: #ef4444;
        --info: #38bdf8;
        --purple: #6a1b9a; /* Deep purple for table headers */
        --teal: #14b8a6;
        --bg-light: #f8fafc;
        --bg-dark: #121212;
        --text-dark: #1e293b;
        --text-muted: #6b7280;
        --shadow: 0 4px 12px rgba(0,0,0,0.1);
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-theme="dark"] {
        --bg-light: #121212;
        --bg-dark: #1e1e1e;
        --text-dark: #e0e0e0;
        --text-muted: #9ca3af;
    }

    body {
        background: var(--bg-light);
        font-family: 'Segoe UI', sans-serif;
        color: var(--text-dark);
        transition: var(--transition);
        overflow-x: hidden;
    }

    /* Sidebar Styles */
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

    .dropdown-container {
        position: relative;
        width: 100%;
    }

    .dropdown-toggle {
        color: #d1d5db;
        font-weight: 500;
        border-radius: 0.5rem;
        margin: 0.3rem 0.5rem;
        padding: 0.8rem 1rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: none;
        width: 100%;
        text-align: left;
    }

    .dropdown-toggle:hover, .dropdown-toggle.show {
        color: #fff;
        background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
        box-shadow: var(--shadow-sm);
        transform: translateX(4px);
    }

    .dropdown-toggle::after {
        margin-left: auto;
        transition: transform 0.3s ease;
        border: none;
        font: normal normal normal 14px/1 FontAwesome;
        content: "\f078";
    }

    .dropdown-toggle.show::after {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        display: none;
        background: linear-gradient(135deg, #1e293b 0%, #2d3748 100%);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        min-width: calc(100% - 1rem);
        margin: 0.5rem;
        padding: 0.75rem 0;
        animation: slideIn 0.3s ease-out;
        position: static;
        max-height: 60vh;
        overflow-y: auto;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
    }

    .dropdown-item {
        color: #e5e7eb;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-radius: 0.25rem;
        margin: 0.2rem 0.5rem;
    }

    .dropdown-item:hover {
        background: var(--primary);
        color: #fff;
        transform: translateX(4px);
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* Main Content */
    .main-content {
        transition: margin-left 0.3s ease;
        padding: 1.5rem;
        background-color: var(--bg-light);
    }

    /* Header Section */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .header-section h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    /* Table Container */
    .table-container {
        overflow-x: auto;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin: 30px 0;
        max-width: 100%;
        background-color: #ffffff;
        box-shadow: var(--shadow);
    }

    /* Table Styles */
    .user-table {
        width: 100%;
        border-collapse: collapse;
    }

    .user-table th,
    .user-table td {
        border: 1px solid #e0e0e0;
        padding: 14px 16px;
        text-align: left;
        font-size: 18px;
        vertical-align: middle;
        font-weight: bold;
    }

    .user-table td {
        border: 1px solid #ddd;
        padding: 12px 16px;
        text-align: left;
        font-size: 17px;
        vertical-align: middle;
        font-weight: 500;
        color: #616161;
        background-color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
        transition: background-color 0.2s ease;
    }

    .user-table th {
      background-color: #6a1b9a; /* Deep purple */
color: #ffffff;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 10;
        cursor: pointer;
        transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        font-size: 18px;
        white-space: nowrap;
        padding: 14px 18px;
        border-bottom: 2px solid #0b3d91;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        letter-spacing: 0.5px;
    }

    /* Specific column widths */
    .user-table th[data-sort="s_no"],
    .user-table td:nth-child(1) {
        width: 60px;
        min-width: 50px;
    }

    .user-table th[data-sort="email"],
    .user-table td:nth-child(3) {
        width: 200px;
        min-width: 150px;
    }

    /* Buttons */
    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: var(--primary);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
    }

    .btn-sm:hover {
        transform: scale(1.05);
    }

    .export-btn {
        background-color: var(--success);
        color: #fff;
        border: none;
        position: relative;
    }

    .export-btn:hover {
        background-color: #16a34a;
    }

    .export-btn.loading::after,
    .btn-primary.loading::after,
    .btn-outline-danger.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }

    .theme-toggle {
        background-color: var(--primary);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .theme-toggle:hover {
        background-color: var(--primary-dark);
        transform: rotate(20deg);
    }

    /* Search Bar */
    .search-bar {
        max-width: 300px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        font-family: 'Segoe UI', sans-serif;
        transition: border-color 0.2s ease;
    }

    .search-bar:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none;
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
        padding: 16px;
        font-size: 15px;
        margin: 20px 0;
        box-shadow: var(--shadow-sm);
    }

    .alert-success {
        background-color: #e7f3e7;
        color: #2f692f;
    }

    .alert-danger, .alert-warning {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Modal */
    .modal-content {
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .modal-header {
        border-bottom: 1px solid #e0e0e0;
    }

    /* Form Controls */
    .form-control, .form-select {
        border-radius: 8px;
        font-family: 'Segoe UI', sans-serif;
        transition: border-color 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--text-dark);
    }

    .text-danger {
        font-size: 0.85rem;
    }

    /* Footer */
    footer {
        font-size: 14px;
        color: var(--text-muted);
        padding: 20px 0;
        border-top: 1px solid #e0e0e0;
    }

    /* Dark Mode */
    [data-theme="dark"] {
        background-color: var(--bg-dark);
        color: var(--text-dark);
    }

    [data-theme="dark"] .main-content {
        background-color: var(--bg-dark);
    }

    [data-theme="dark"] .header-section {
        border-bottom-color: #444;
    }

    [data-theme="dark"] .table-container {
        background-color: #1e1e1e;
        border-color: #444;
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
    }

    [data-theme="dark"] .user-table th,
    [data-theme="dark"] .user-table td {
        border-color: #444;
        background-color: #1e1e1e;
        color: var(--text-dark);
    }

    [data-theme="dark"] .user-table th {
        background-color: #2a2a2a;
        color: #ffffff;
    }

    [data-theme="dark"] .user-table tr:hover td {
        background-color: #2c2c2c;
    }

    [data-theme="dark"] .btn-primary {
        background-color: #80bdff;
    }

    [data-theme="dark"] .btn-primary:hover {
        background-color: #66a3ff;
    }

    [data-theme="dark"] .btn-outline-secondary {
        border-color: #6c757d;
        color: var(--text-dark);
    }

    [data-theme="dark"] .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    [data-theme="dark"] .export-btn {
        background-color: var(--success);
    }

    [data-theme="dark"] .export-btn:hover {
        background-color: #16a34a;
    }

    [data-theme="dark"] .theme-toggle {
        background-color: #80bdff;
    }

    [data-theme="dark"] .theme-toggle:hover {
        background-color: #66a3ff;
    }

    [data-theme="dark"] .alert-success {
        background-color: #2a4f2a;
        color: #80ff80;
    }

    [data-theme="dark"] .alert-danger, [data-theme="dark"] .alert-warning {
        background-color: #4a2529;
        color: #f5b7b1;
    }

    [data-theme="dark"] .modal-content {
        background-color: #1e1e1e;
        border-color: #444;
    }

    [data-theme="dark"] .modal-header {
        border-bottom-color: #444;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
        background-color: #2a2a2a;
        border-color: #444;
        color: var(--text-dark);
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        border-color: #80bdff;
    }

    [data-theme="dark"] .form-label {
        color: var(--text-dark);
    }

    [data-theme="dark"] .user-table .employee-name {
        color: #ffcc80;
    }

    [data-theme="dark"] .user-table .email {
        color: #ff6666 !important;
    }

    [data-theme="dark"] .user-table .status-kyc {
        color: #34c759 !important;
    }

    [data-theme="dark"] .user-table .status-current {
        color: #ff4d4f !important;
    }

    [data-theme="dark"] .bg-branch {
        background-color: #1b2c3a !important;
    }

    [data-theme="dark"] .bg-status {
        background-color: #2a2a2a !important;
    }

    [data-theme="dark"] footer {
        color: #adb5bd;
        border-top-color: #444;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .user-table th,
        .user-table td {
            font-size: 14px;
            padding: 12px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
    }

    @media (max-width: 991.98px) {
        .sidebar {
            position: fixed;
            left: -260px;
            width: 260px;
            z-index: 1040;
            transition: left 0.3s ease;
        }

        .sidebar.show {
            left: 0;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 15px;
        }

        .sidebar-toggle {
            display: flex;
        }

        .dropdown-menu {
            min-width: calc(100% - 2rem);
            margin: 0.5rem 1rem;
            max-height: 50vh;
        }
    }

    @media (max-width: 768px) {
        .user-table th,
        .user-table td {
            font-size: 13px;
            padding: 10px;
        }

        .user-table th:nth-child(5),
        .user-table td:nth-child(5) {
            display: none;
        }

        .btn {
            padding: 8px 16px;
            font-size: 13px;
        }

        .search-bar {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .user-table th,
        .user-table td {
            font-size: 12px;
            padding: 8px;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-section h1 {
            font-size: 1.5rem;
        }
    }

    /* Accessibility */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    /* Sorting Indicators */
    .sort-icon::after {
        content: '\f0dc';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-left: 10px;
        opacity: 0.6;
        font-size: 12px;
    }

    .sort-asc::after {
        content: '\f0de';
        opacity: 1;
    }

    .sort-desc::after {
        content: '\f0dd';
        opacity: 1;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-track {
        background: #f8f9fa;
    }

    [data-theme="dark"] ::-webkit-scrollbar-track {
        background: #1e1e1e;
    }
</style>
@endsection

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('content')
    @if(!auth()->check())
        <div class="alert alert-danger text-center" role="alert">
            You must be logged in to view this page.
        </div>
    @else
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4 main-content">
            <!-- Session Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error') || isset($error))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') ?? $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($error))
                <div class="alert alert-danger text-center">{{ $error }}</div>
            @else
                <div class="header-section">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1>Customer  Users</h1>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <input type="text" class="form-control search-bar" placeholder="Search..." id="userSearch" aria-label="Search users" {{ $permissions['can_read'] ? '' : 'disabled' }}>
                        @if($permissions['can_create'])
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUserModal"><i class="fas fa-plus"></i> Add User</button>
                        @endif
                        <button class="btn export-btn" id="exportBtn" aria-label="Export to CSV"><i class="fas fa-download"></i> Export</button>
                        <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                    </div>
                </div>

                @if($permissions['can_read'])
                    <!-- Telecaller Admin Users Table -->
                    <div class="table-container">
                        <table class="user-table" id="userTable" role="grid" aria-describedby="telecallerAdminUsersTable">
                            <thead>
                                <tr>
                                    <th data-sort="s_no" class="sort-icon">S.No</th>
                                    <th data-sort="name" class="sort-icon">Name</th>
                                    <th data-sort="email" class="sort-icon">Email</th>
                                    <th data-sort="role" class="sort-icon bg-status">Role</th>
                                    <th data-sort="branch" class="sort-icon bg-branch">Branch</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                @forelse ($users as $index => $user)
                                    <tr data-user-id="{{ $user->id }}" data-role-id="{{ $user->role->id ?? '' }}" data-branch-id="{{ $user->branch_id ?? '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td class="employee-name">{{ $user->name }}</td>
                                        <td class="email">{{ $user->email }}</td>
                                        <td class="status-kyc bg-status">{{ $user->role->name ?? 'Telecaller Admin' }}</td>
                                        <td class="status-current bg-branch">
                                            @if ($user->branch)
                                                {{ $user->branch->branch_name }}
                                            @else
                                                <span style="color: #dc3545;">Error: Branch not fetched for user ID {{ $user->id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($permissions['can_read'])
                                                <a href="" class="btn btn-sm btn-outline-secondary view-user-btn" data-user-id="{{ $user->id }}" title="View" data-bs-toggle="tooltip"><i class="fas fa-eye"></i></a>
                                            @endif
                                            @if($permissions['can_update'])
                                                <button class="btn btn-sm btn-primary edit-user-btn" data-user-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#editUserModal" title="Edit" data-bs-toggle="tooltip"><i class="fas fa-edit"></i></button>
                                            @endif
                                            @if($permissions['can_delete'])
                                                <button class="btn btn-sm btn-outline-danger delete-user-btn" data-user-id="{{ $user->id }}" title="Delete" data-bs-toggle="tooltip"><i class="fas fa-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center">You do not have permission to view users.</div>
                @endif

                <!-- Edit User Modal -->
                @if($permissions['can_update'])
                    <div class="modal fade" id="editUserModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editUserForm">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="userId">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" maxlength="50" required>
                                            @error('name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" maxlength="100" required>
                                            @error('email')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password (leave blank to keep unchanged)</label>
                                            <input type="password" name="password" class="form-control" minlength="8">
                                            @error('password')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role_id" class="form-select" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <select name="branch_id" class="form-select">
                                                <option value="">No Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- New User Modal -->
                @if($permissions['can_create'])
                    <div class="modal fade" id="newUserModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Add New User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="newUserForm" action="" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" maxlength="50" required>
                                            @error('name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" maxlength="100" required>
                                            @error('email')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" minlength="8" required>
                                            @error('password')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role_id" class="form-select" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <select name="branch_id" class="form-select">
                                                <option value="">No Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Footer -->
            <footer class="text-center">
                © 2025 Loan Management System
            </footer>
        </main>
    @endif
@endsection

@section('scripts')
<script>
    // Pass Laravel routes and permissions to JavaScript
    window.routes = {
        updateUser: "",
        deleteUser: "",
    };
    window.permissions = @json($permissions);

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);

        // Initialize Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                const mainContent = document.querySelector('.main-content');
                if (mainContent) {
                    mainContent.style.marginLeft = sidebar.classList.contains('show') ? '260px' : '0';
                    mainContent.style.transition = 'margin-left 0.3s ease';
                }
            });
        }

        // Dropdown Toggle
        document.querySelectorAll('.dropdown-container .dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdown = toggle.nextElementSibling;
                const isShown = dropdown.classList.contains('show');
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
                document.querySelectorAll('.dropdown-toggle').forEach(t => t.classList.remove('show'));
                if (!isShown) {
                    dropdown.classList.add('show');
                    toggle.classList.add('show');
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            const toggles = document.querySelectorAll('.dropdown-toggle');
            if (!Array.from(toggles).some(toggle => toggle.contains(e.target)) && !Array.from(dropdowns).some(dropdown => dropdown.contains(e.target))) {
                dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
                toggles.forEach(toggle => toggle.classList.remove('show'));
            }
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                const icon = themeToggle.querySelector('i');
                icon.classList.toggle('fa-moon');
                icon.classList.toggle('fa-sun');
                localStorage.setItem('theme', newTheme);
            });

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
                if (savedTheme === 'dark') {
                    themeToggle.querySelector('i').classList.replace('fa-moon', 'fa-sun');
                }
            }
        }

        // Table Sorting Functionality
        const table = document.getElementById('userTable');
        if (table) {
            const headers = table.querySelectorAll('th[data-sort]');
            let currentSortKey = null;
            let isAscending = true;

            headers.forEach(header => {
                header.addEventListener('click', () => {
                    if (!window.permissions.can_read) {
                        alert('You do not have permission to view users.');
                        return;
                    }
                    const sortKey = header.getAttribute('data-sort');
                    isAscending = currentSortKey === sortKey ? !isAscending : true;
                    currentSortKey = sortKey;

                    headers.forEach(h => {
                        h.classList.remove('sort-asc', 'sort-desc');
                        h.classList.add('sort-icon');
                        h.setAttribute('aria-sort', 'none');
                    });
                    header.classList.remove('sort-icon');
                    header.classList.add(isAscending ? 'sort-asc' : 'sort-desc');
                    header.setAttribute('aria-sort', isAscending ? 'ascending' : 'descending');

                    sortTable(sortKey, isAscending);
                });
            });

            function sortTable(key, ascending) {
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.sort((a, b) => {
                    let aValue, bValue;
                    const columnIndex = getColumnIndex(key);

                    if (key === 's_no') {
                        aValue = parseInt(a.cells[0].textContent) || 0;
                        bValue = parseInt(b.cells[0].textContent) || 0;
                    } else {
                        aValue = a.cells[columnIndex].textContent.toLowerCase();
                        bValue = b.cells[columnIndex].textContent.toLowerCase();
                    }

                    if (aValue < bValue) return ascending ? -1 : 1;
                    if (aValue > bValue) return ascending ? 1 : -1;
                    return 0;
                });

                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            }

            function getColumnIndex(key) {
                const headers = ['s_no', 'name', 'email', 'role', 'branch'];
                return headers.indexOf(key);
            }
        }

        // Export to CSV Functionality
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                if (!window.permissions.can_read) {
                    alert('You do not have permission to view users.');
                    return;
                }
                exportBtn.classList.add('loading');
                setTimeout(() => {
                    const table = document.getElementById('userTable');
                    const rows = table.querySelectorAll('tr');
                    let csvContent = 'data:text/csv;charset=utf-8,';
                    const headers = Array.from(table.querySelectorAll('th'))
                        .slice(0, -1)
                        .map(th => `"${th.textContent.replace(/"/g, '""')}"`)
                        .join(',');
                    csvContent += headers + '\r\n';

                    rows.forEach(row => {
                        const rowData = Array.from(row.cells)
                            .slice(0, -1)
                            .map(cell => `"${cell.textContent.replace(/"/g, '""').replace(/[\n\r]+/g, '')}"`)
                            .join(',');
                        csvContent += rowData + '\r\n';
                    });

                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement('a');
                    link.setAttribute('href', encodedUri);
                    link.setAttribute('download', `telecaller_admin_users_${new Date().toISOString().split('T')[0]}.csv`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    exportBtn.classList.remove('loading');
                }, 500);
            });
        }

        // Form Submission Handler with Loading Indicator
        const handleFormSubmit = async (formId, url, method, successMsg, errorMsg, modalId) => {
            const form = document.getElementById(formId);
            const submitBtn = form.querySelector('button[type="submit"]');
            form.addEventListener('submit', async e => {
                e.preventDefault();
                if ((formId === 'newUserForm' && !window.permissions.can_create) || 
                    (formId === 'editUserForm' && !window.permissions.can_update)) {
                    alert('You do not have permission to perform this action.');
                    return;
                }
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                try {
                    const response = await fetch(url || form.action, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.error('Non-JSON response:', text);
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 50));
                    }
                    const responseData = await response.json();
                    if (response.ok) {
                        alert(successMsg);
                        if (modalId) bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                        window.location.reload();
                    } else {
                        alert(errorMsg + ': ' + (responseData.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('An error occurred: ' + error.message);
                } finally {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }
            });
        };

        if (window.permissions.can_create) {
            handleFormSubmit('newUserForm', null, 'POST', 'User added successfully', 'Error adding user', 'newUserModal');
        }

        if (window.permissions.can_update) {
            handleFormSubmit('editUserForm', null, 'PUT', 'User updated successfully', 'Error updating user', 'editUserModal');
        }

        // Edit User Button
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (!window.permissions.can_update) {
                    alert('You do not have permission to edit users.');
                    return;
                }
                const row = btn.closest('tr');
                const form = document.getElementById('editUserForm');
                const userId = btn.dataset.userId;
                form.action = window.routes.updateUser.replace(':user', userId);
                form.querySelector('[name="userId"]').value = userId;
                form.querySelector('[name="name"]').value = row.cells[1].textContent.trim();
                form.querySelector('[name="email"]').value = row.cells[2].textContent.trim();
                form.querySelector('[name="role_id"]').value = row.dataset.roleId || '';
                form.querySelector('[name="branch_id"]').value = row.dataset.branchId || '';
            });
        });

        // Delete User Button
        document.querySelectorAll('.delete-user-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (!window.permissions.can_delete) {
                    alert('You do not have permission to delete users.');
                    return;
                }
                if (confirm('Are you sure you want to delete this user?')) {
                    btn.classList.add('loading');
                    btn.disabled = true;
                    try {
                        const response = await fetch(window.routes.deleteUser.replace(':user', btn.dataset.userId), {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text);
                            throw new Error('Server returned non-JSON response: ' + text.substring(0, 50));
                        }
                        const responseData = await response.json();
                        if (response.ok) {
                            alert('User deleted successfully');
                            window.location.reload();
                        } else {
                            alert('Error deleting user: ' + (responseData.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        alert('An error occurred: ' + error.message);
                    } finally {
                        btn.classList.remove('loading');
                        btn.disabled = false;
                    }
                }
            });
        });

        // Search Functionality
        const userSearch = document.getElementById('userSearch');
        if (userSearch) {
            userSearch.addEventListener('input', function() {
                if (!window.permissions.can_read) {
                    alert('You do not have permission to view users.');
                    this.value = '';
                    return;
                }
                const searchValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#userTableBody tr');
                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const role = row.cells[3].textContent.toLowerCase();
                    const branch = row.cells[4].textContent.toLowerCase();
                    if (name.includes(searchValue) || email.includes(searchValue) || role.includes(searchValue) || branch.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Keyboard Accessibility for Buttons
        document.querySelectorAll('.view-user-btn, .edit-user-btn, .delete-user-btn, .btn-primary, .theme-toggle, .sidebar-toggle').forEach(button => {
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });

        // Highlight active sidebar link
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '') {
                link.classList.add('active');
            }
        });
    });
</script>
@endsection