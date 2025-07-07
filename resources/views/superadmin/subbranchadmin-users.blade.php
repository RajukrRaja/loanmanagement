@extends('superadmin.superadmin')

@section('title', 'Sub Branch Admin Users')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
<style>
    /* Main Content */
    .main-content {
        transition: margin-left 0.3s ease;
        padding: 1.5rem;
        background-color: #f8fafc;
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        border-bottom: 2px solid #0b3d91; /* Darker blue border */
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
        background-color: #007bff;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
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
        background-color: #28a745;
        color: #fff;
        border: none;
        position: relative;
    }

    .export-btn:hover {
        background-color: #218838;
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
        background-color: #007bff;
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
        background-color: #0056b3;
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
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none;
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
        padding: 16px;
        font-size: 15px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .text-danger {
        font-size: 0.85rem;
    }

    /* Footer */
    footer {
        font-size: 14px;
        color: #6c757d;
        padding: 20px 0;
        border-top: 1px solid #e0e0e0;
    }

    /* Dark Mode */
    [data-theme="dark"] {
        background-color: #121212;
        color: #e0e0e0;
    }

    [data-theme="dark"] .main-content {
        background-color: #121212;
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
        color: #e0e0e0;
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
        color: #e0e0e0;
    }

    [data-theme="dark"] .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    [data-theme="dark"] .export-btn {
        background-color: #218838;
    }

    [data-theme="dark"] .export-btn:hover {
        background-color: #1e7e34;
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
        color: #e0e0e0;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        border-color: #80bdff;
    }

    [data-theme="dark"] .form-label {
        color: #e0e0e0;
    }

    [data-theme="dark"] .user-table .employee-name {
        color: #ffcc80; /* Lighter orange for dark mode */
    }

    [data-theme="dark"] .user-table .email {
        color: #ff6666 !important; /* Lighter red for dark mode */
    }

    [data-theme="dark"] .user-table .status-kyc {
        color: #34c759 !important; /* Lighter green for dark mode */
    }

    [data-theme="dark"] .user-table .status-current {
        color: #ff4d4f !important; /* Lighter red for dark mode */
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
            display: none; /* Hide Branch column on smaller screens */
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
        content: '\f0dc'; /* Font Awesome sort icon */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-left: 10px;
        opacity: 0.6;
        font-size: 12px;
    }

    .sort-asc::after {
        content: '\f0de'; /* Font Awesome sort-up icon */
        opacity: 1;
    }

    .sort-desc::after {
        content: '\f0dd'; /* Font Awesome sort-down icon */
        opacity: 1;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: #007bff;
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

            <div class="header-section">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Sub Branch Admin Users</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <input type="text" class="form-control search-bar" placeholder="Search..." id="userSearch" aria-label="Search users">
                    @if($permissions['can_create'])
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUserModal"><i class="fas fa-plus"></i> Add User</button>
                    @endif
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                </div>
            </div>

            @if($permissions['can_read'])
                <!-- Sub Branch Admin Users Table -->
                <div class="table-container">
                    <table class="user-table" id="userTable" role="grid" aria-describedby="subBranchAdminUsersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Branch</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @forelse ($users as $index => $user)
                                <tr data-user-id="{{ $user->id }}" data-role="{{ $user->role ?? 'Sub Branch Admin' }}" data-branch-id="{{ $user->branch_id ?? '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role ?? 'Sub Branch Admin' }}</td>
                                    <td>{{ $user->branch->branch_name ?? 'N/A' }}</td>
                                    <td>
                                        @if($permissions['can_read'])
                                            <button class="btn btn-sm btn-outline-secondary view-user-btn" data-user-id="{{ $user->id }}" title="View" data-bs-toggle="tooltip"><i class="fas fa-eye"></i></button>
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
                                <form id="editUserForm" method="POST">
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
                                        <label class="form-label">Password (Leave blank to keep unchanged)</label>
                                        <input type="password" name="password" class="form-control" minlength="8">
                                        @error('password')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="Sub Branch Admin">Sub Branch Admin</option>
                                        </select>
                                        @error('role')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Branch</label>
                                        <select name="branch_id" class="form-select">
                                            <option value="">No Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
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
                <div class="modal fade" id="newUserModal" tabindex="-1">
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
                                        <select name="role" class="form-select" required>
                                            <option value="Sub Branch Admin">Sub Branch Admin</option>
                                        </select>
                                        @error('role')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Branch</label>
                                        <select name="branch_id" class="form-select">
                                            <option value="">No Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
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
                form.querySelector('[name="role"]').value = row.cells[3].textContent.trim();
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