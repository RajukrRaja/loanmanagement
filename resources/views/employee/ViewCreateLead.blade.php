
@extends('superadmin.superadmin')

@section('title', 'View Leads')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
  <style>
    /* Main Content */
    .main-content {
        transition: margin-left 0.3s ease;
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
    .excel-table-container {
        overflow-x: auto;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin: 30px 0;
        max-width: 100%;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Table Styles */
    .excel-table {
        width: 100%;
        border-collapse: collapse;
    }

    .excel-table th,
    .excel-table td {
        border: 1px solid #e0e0e0;
        padding: 14px 16px;
        text-align: left;
        font-size: 18px;
        vertical-align: middle;
        font-weight: bold;
    }

    .excel-table td {
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

    /* Column-specific text colors */
    .excel-table .branch-name {
        color: orange /* Sky blue text color */
    }

    .excel-table .user-name {
        color: #cc6600; /* Orange text color */
    }

   



    .excel-table th {
       background-color: #6a1b9a; /* Deep purple */
color: #ffffff;
        font-weight: 700;
        position: sticky;
        top: 0;
        z-index: 10;
        cursor: pointer;
        transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        font-size: 18px;
   
        padding: 14px 18px;
        border-bottom: 2px solid #0b3d91; /* Darker blue border */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        letter-spacing: 0.5px;
        height:100px; !important
    }

    /* Specific column widths */
    .excel-table th[data-sort="index"],
    .excel-table td:nth-child(1) {
        width: 60px;
        min-width: 50px;
    }

    .excel-table th[data-sort="loan_id"],
     {
        width: 100px;
        min-width: 100px;
        white-space:n;
        
        
    }
    
    
    .excel-table td:nth-child(3) {
        width: 100px;
        min-width: 100px;
        height:100px;
        
    }

    /* Status Colors */
    .status-approved {
        color: #28a745;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-closed {
        color: #dc3545;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .action-btn:focus {
        outline: 2px solid #007bff;
        outline-offset: 2px;
    }

    .action-btn i {
        font-size: 16px;
    }

    /* Buttons */
    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
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

    .export-btn.loading::after {
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
    }

    .theme-toggle:hover {
        background-color: #0056b3;
    }

    /* Alerts */
    .alert {
        border-radius: 6px;
        padding: 16px;
        font-size: 15px;
        margin: 20px 0;
    }

    .alert-info {
        background-color: #e7f3fe;
        color: #31708f;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
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

    [data-theme="dark"] .excel-table-container {
        background-color: #1e1e1e;
        border-color: #444;
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
    }

    [data-theme="dark"] .excel-table th,
    [data-theme="dark"] .excel-table td {
        border-color: #444;
        background-color: #1e1e1e;
        color: #e0e0e0;
    }

    [data-theme="dark"] .excel-table th {
        background-color: #2a2a2a;
        color: #ffffff;
    }

    [data-theme="dark"] .excel-table tr:hover td {
        background-color: #2c2c2c;
    }

    [data-theme="dark"] .action-btn {
        background-color: #2a2a2a;
        border-color: #444;
        color: #e0e0e0;
    }

    [data-theme="dark"] .action-btn:hover {
        background-color: #3a3a3a;
        box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
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

    [data-theme="dark"] .alert-info {
        background-color: #2a3d4f;
        color: #80bdff;
    }

    [data-theme="dark"] .alert-danger {
        background-color: #4a2529;
        color: #f5b7b1;
    }

    [data-theme="dark"] .excel-table .branch-name {
        color: #80ccff; /* Lighter blue for dark mode */
    }

    [data-theme="dark"] .excel-table .user-name {
        color: #ffcc80; /* Lighter orange for dark mode */
    }

    [data-theme="dark"] .excel-table .email {
        color: #ff6666 !important; /* Lighter red for dark mode */
    }

    [data-theme="dark"] .bg-branch {
        background-color: #1b2c3a !important;
    }

    [data-theme="dark"] .bg-loan {
        background-color: #3d341e !important;
    }

    [data-theme="dark"] .bg-interest {
        background-color: #402929 !important;
    }

    [data-theme="dark"] .bg-disbursed {
        background-color: #233a23 !important;
    }

    [data-theme="dark"] .bg-unpaid {
        background-color: #3a2c3a !important;
    }

    [data-theme="dark"] .bg-penalty {
        background-color: #4a2529 !important;
    }

    [data-theme="dark"] footer {
        color: #adb5bd;
        border-top-color: #444;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .excel-table th,
        .excel-table td {
            font-size: 14px;
            padding: 12px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
        }
    }

    @media (max-width: 992px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .main-content {
            padding: 15px;
        }
    }

    @media (max-width: 768px) {
        .excel-table th,
        .excel-table td {
            font-size: 13px;
            padding: 10px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 13px;
        }
    }

    @media (max-width: 576px) {
        .excel-table th,
        .excel-table td {
            font-size: 12px;
            padding: 8px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
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

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .pagination .page-link {
        border-radius: 4px;
        margin: 0 3px;
        color: #007bff;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    [data-theme="dark"] .pagination .page-link {
        color: #80bdff;
        background-color: #2a2a2a;
        border-color: #444;
    }

    [data-theme="dark"] .pagination .page-item.active .page-link {
        background-color: #80bdff;
        color: #1e1e1e;
    }
</style>
@endsection

@section('content')
    @if(!auth()->check())
        <div class="alert alert-danger text-center">You must be logged in to view this page.</div>
    @else
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4" style="transition: margin-left 0.3s;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary sidebar-toggle me-3" id="sidebarToggle" data-bs-toggle="tooltip" data-bs-title="Toggle Sidebar"><i class="fas fa-bars"></i></button>
                    <h2 class="h3 mb-0 fw-bold">View Leads</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search..." id="searchInput" value="{{ request()->query('search', '') }}">
                    <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" data-bs-title="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
                </div>
            </div>

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

            @if($leads->isEmpty())
                <div class="alert alert-info text-center">No leads found.</div>
            @else
                <div class="excel-table-container">
                    <table class="excel-table" role="grid" aria-describedby="viewLeadsTable">
                        <thead>
                            <tr>
                                <th scope="col" data-sort="user_id">User ID <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" class="bg-branch" data-sort="branch_name">Branch Name (Branch ID) <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="employee_name">Employee Name (Employee ID) <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="full_name">Full Name <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="email">Email ID <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="mobile_no">Mobile Number <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="kyc_status">KYC Status <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" class="bg-loan" data-sort="loan_approved_amount">Loan Approved Amount <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="branch_recommendation">Branch Recommendation <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col" data-sort="created_at">Created At <i class="fas fa-sort sort-icon"></i></th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leadsTableBody">
                            @foreach($leads as $index => $lead)
                                <tr>
                                    <td>{{ $lead->lead_id }}</td>
                                    <td class="branch-name bg-branch">{{ $lead->branch_name ? $lead->branch_name . ' (' . $lead->branch_id . ')' : 'N/A' }}</td>
                                    <td class="employee-name">{{ $lead->employee_name ? $lead->employee_name . ' (' . $lead->employee_id . ')' : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('employee.viewLeadDetails', ['id' => $lead->lead_id]) }}" title="View Lead Details">
                                            {{ trim(($lead->first_name ?? '') . ' ' . ($lead->middle_name ?? '') . ' ' . ($lead->last_name ?? '')) ?: 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="email">{{ $lead->email ?? 'N/A' }}</td>
                                    <td>{{ $lead->mobile_no ?? 'N/A' }}</td>
                                    <td class="{{ $lead->kyc_status === 'Approved' ? 'status-approved' : 'status-closed' }}">
                                        {{ $lead->kyc_status ?? 'Pending' }}
                                    </td>
                                    <td class="bg-loan">{{ $lead->kyc_status === 'Approved' && $lead->loan_approved_amount ? '₹' . number_format($lead->loan_approved_amount, 2, '.', ',') : 'N/A' }}</td>
                                    <td>{{ $lead->branch_recommendation ?? 'N/A' }}</td>
                                    <td>{{ $lead->created_at ? \Carbon\Carbon::parse($lead->created_at)->format('d/m/y H:i') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('employee.viewLeadDetails', ['id' => $lead->lead_id]) }}" class="action-btn me-2" title="View Lead">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employee.deleteLead', ['id' => $lead->lead_id]) }}"
                                           class="action-btn me-2" title="Delete Lead"
                                           onclick="return confirm('Are you sure you want to delete this lead?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        @if($lead->kyc_status === 'Approved' && $lead->status_of_enach === 'Approved')
                                            <button class="btn btn-sm btn-primary view-btn" data-lead-id="{{ $lead->lead_id }}" data-bs-toggle="tooltip" title="View Disbursement Form">
                                                <i class="fas fa-file-invoice"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @if($lead->kyc_status === 'Approved' && $lead->status_of_enach === 'Approved')
                                    <tr class="disbursement-form" id="form-{{ $lead->lead_id }}">
                                        <td colspan="11">
                                            <form class="disbursement-form-inner" method="POST" action="{{ route('emi.calculator') }}">
                                                @csrf
                                                <input type="hidden" name="lead_id" value="{{ $lead->lead_id }}">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="disbursed_amount_{{ $lead->lead_id }}" class="form-label">Disbursed Amount</label>
                                                        <input type="number" class="form-control" id="disbursed_amount_{{ $lead->lead_id }}"
                                                               name="disbursed_amount" step="0.01" min="0"
                                                               value="{{ $lead->loan_approved_amount ?? 0 }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="disbursement_date_{{ $lead->lead_id }}" class="form-label">Disbursement Date</label>
                                                        <input type="datetime-local" class="form-control" id="disbursement_date_{{ $lead->lead_id }}"
                                                               name="disbursement_date" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $leads instanceof \Illuminate\Pagination\LengthAwarePaginator ? $leads->appends(['search' => request()->query('search')])->links('pagination::bootstrap-5') : '' }}
                </div>
            @endif

            <footer class="pt-3 mt-4 text-muted border-top text-center small">
                © 2025 Loan Management System. All rights reserved.
            </footer>
        </main>
    @endif
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                });
            }, 5000);

            // Handle View button clicks for disbursement form
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const leadId = this.getAttribute('data-lead-id');
                    const formRow = document.getElementById(`form-${leadId}`);
                    const allFormRows = document.querySelectorAll('.disbursement-form');

                    // Hide all other forms
                    allFormRows.forEach(row => {
                        if (row.id !== `form-${leadId}`) {
                            row.style.display = 'none';
                        }
                    });

                    // Toggle the selected form
                    formRow.style.display = formRow.style.display === 'none' || formRow.style.display === '' ? 'table-row' : 'none';
                });
            });

            // Search Form Submission
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        window.location.href = `?search=${encodeURIComponent(e.target.value.trim())}&sort_field=${sortField}&sort_order=${sortOrder}`;
                    }, 300);
                });
            }

            // Sorting functionality
            let sortField = '{{ request()->query('sort_field', 'lead_id') }}';
            let sortOrder = '{{ request()->query('sort_order', 'asc') }}';
            document.querySelectorAll('th[data-sort]').forEach(th => {
                th.addEventListener('click', () => {
                    const field = th.getAttribute('data-sort');
                    if (sortField === field) {
                        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortField = field;
                        sortOrder = 'asc';
                    }
                    const search = document.getElementById('searchInput') ? document.getElementById('searchInput').value : '';
                    window.location.href = `?search=${encodeURIComponent(search)}&sort_field=${sortField}&sort_order=${sortOrder}`;
                });
            });

            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Theme toggle
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    document.documentElement.setAttribute('data-theme', currentTheme === 'dark' ? 'light' : 'dark');
                    themeToggle.querySelector('i').classList.toggle('fa-moon');
                    themeToggle.querySelector('i').classList.toggle('fa-sun');
                });
            }

            // Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    const sidebar = document.getElementById('sidebarMenu');
                    sidebar.classList.toggle('show');
                    const mainContent = document.querySelector('main');
                    if (mainContent) {
                        mainContent.style.marginLeft = sidebar.classList.contains('show') ? '240px' : '0';
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
                    document.querySelectorAll('.dropdown-toggle').forEach(t => {
                        t.classList.remove('show');
                        t.setAttribute('aria-expanded', 'false');
                    });
                    if (!isShown) {
                        dropdown.classList.add('show');
                        toggle.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                const dropdowns = document.querySelectorAll('.dropdown-menu');
                const toggles = document.querySelectorAll('.dropdown-toggle');
                if (!e.target.closest('.dropdown-container')) {
                    dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
                    toggles.forEach(toggle => {
                        toggle.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                    });
                }
            });

            // Highlight active sidebar link
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '{{ route('employee.ViewCreateLead') }}') {
                    link.classList.add('active');
                    const parentDropdown = link.closest('.dropdown-container');
                    if (parentDropdown) {
                        const dropdownMenu = parentDropdown.querySelector('.dropdown-menu');
                        const dropdownToggle = parentDropdown.querySelector('.dropdown-toggle');
                        if (dropdownMenu && dropdownToggle) {
                            dropdownMenu.classList.add('show');
                            dropdownToggle.classList.add('show');
                            dropdownToggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            });
        });
    </script>
@endsection
