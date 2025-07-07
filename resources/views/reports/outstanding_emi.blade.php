
@extends('superadmin.superadmin')

@section('title', 'Outstanding EMI Report')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
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

    .card-stats.leads .icon {
        background: var(--info);
    }

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
    .excel-table .user-name {
        color: #0066cc; /* Sky blue text color */
    }

    .excel-table .loan-type {
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
        white-space: nowrap;
        padding: 14px 18px;
        border-bottom: 2px solid #0b3d91; /* Darker blue border */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        letter-spacing: 0.5px;
    }
.excel-table td.employee-name {
    color: #c2185b; /* Dark Pink (Rose shade) */
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
     /* Column-specific text colors */
.excel-table .branch-name {
    color: orange; /* Or use a hex code like #ff9800 */
}


/* Column-specific text colors (only for table body cells) */
.excel-table td.branch-name {
    color: orange; /* Branch Name */
}

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
    }

    /* Dark Mode for Table */
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
        background: linear-gradient(to right, #2a2a2a, #3a3a3a);
        color: #ffffff;
    }

    [data-theme="dark"] .excel-table tr:hover td {
        background-color: #2c2c2c;
    }

    [data-theme="dark"] .user-name {
        color: #80ccff; /* Lighter blue for dark mode */
    }

    [data-theme="dark"] .loan-type {
        color: #ffcc80; /* Lighter orange for dark земного mode */
    }

    [data-theme="dark"] .bg-loan {
        background-color: #2a2a2a !important;
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

    /* Responsive Design */
    @media (max-width: 991.98px) {
        .main-content {
            margin-left: 0 !important;
        }
    }

    @media (min-width: 992px) {
        .sidebar-toggle {
            display: none;
        }
    }

    @media (max-width: 767.98px) {
        .excel-table th:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(6)),
        .excel-table td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(6)) {
            display: none;
        }

        .search-bar {
            max-width: 100%;
        }

        .excel-table th,
        .excel-table td {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
    }

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
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle" data-bs-toggle="tooltip" data-bs-title="Toggle Sidebar" aria-label="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="h3 mb-0 fw-bold">Outstanding Balance Reports</h2>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control search-bar" id="searchInput" placeholder="Search reports..." value="{{ request()->query('search', '') }}" aria-label="Search reports">
                <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" data-bs-title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>

        <!-- Report Table -->
        @if ($reports->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                No outstanding EMIs found.
            </div>
        @else
          <div class="excel-table-container mb-4">
    <table class="excel-table table table-bordered" role="grid" aria-describedby="outstandingEmiReportTable">
        <thead class="table-light">
            <tr>
                <th>Sr. No</th>
                <th>Branch</th>
                <th>Employee</th>
         
                <th>User Name</th>
                <th>Mobile Number</th>
                <th>Loan Type</th>
                <th>Loan Amount</th>
                <th>EMI (₹)</th>
                <th>Tenure</th>
                <th>Remaining Amount</th>
                <th>Interest Paid</th>
                <th>Remaining Interest</th>
                <th>Remaining Penalty</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
                <tr>
                    <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>

                    <td class="branch-name">
                        {{ $report->branch_name ?? 'N/A' }} ({{ $report->branch_id ?? 'N/A' }})
                    </td>

                    <td class="employee-name">
                        {{ $report->employee_name ?? 'N/A' }} ({{ $report->employee_id ?? 'N/A' }})
                    </td>

                  

                    <td class="employee-name">
                        <a href="{{ route('employee.viewLeadDetails', ['id' => $report->lead_id ?? '']) }}">
                            {{ trim(($report->first_name ?? '') . ' ' . ($report->middle_name ?? '') . ' ' . ($report->last_name ?? '')) . ' (' . ($report->loan_id ?? 'N/A') . ')' }}
                        </a>
                    </td>

                    <td>{{ $report->mobile_no ?? 'N/A' }}</td>
                    <td>{{ $report->loan_type ?? 'N/A' }}</td>

                    <td>{{ is_numeric($report->approved_loan_amount) ? '₹' . number_format($report->approved_loan_amount) : 'N/A' }}</td>
                    <td>{{ is_numeric($report->emi) ? '₹' . number_format($report->emi) : 'N/A' }}</td>
                    <td>{{ $report->tenure_months ?? 'N/A' }}</td>

                    <td>{{ is_numeric($report->amount_unpaid) ? '₹' . number_format($report->amount_unpaid) : '₹0' }}</td>

                    <td>
                        {{ is_numeric($report->total_interest) && is_numeric($report->unpaid_interest)
                            ? '₹' . number_format($report->total_interest - $report->unpaid_interest)
                            : '₹0' }}
                    </td>

                    <td>{{ is_numeric($report->unpaid_interest) ? '₹' . number_format($report->unpaid_interest) : '₹0' }}</td>

                    <td>{{ is_numeric($report->remaining_penalty) ? '₹' . number_format($report->remaining_penalty) : '₹0' }}</td>

                    <td>
                        <a href="{{ route('emi.result', ['lead_id' => $report->lead_id]) }}" class="action-btn" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $reports instanceof \Illuminate\Pagination\LengthAwarePaginator ? $reports->appends(['search' => request()->query('search')])->links('pagination::bootstrap-5') : '' }}
            </div>
        @endif

        <!-- Footer -->
        <footer class="pt-3 mt-4 text-muted border-top text-center small">
            © 2025 Loan Management System
        </footer>
    </main>
@endif
@endsection




@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize theme based on localStorage or system preference
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', savedTheme);
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.querySelector('i').classList.add(savedTheme === 'dark' ? 'fa-sun' : 'fa-moon');
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);

        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Theme toggle
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                themeToggle.querySelector('i').classList.toggle('fa-moon');
                themeToggle.querySelector('i').classList.toggle('fa-sun');
            });
        }

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                const mainContent = document.querySelector('.main-content');
                if (mainContent) {
                    mainContent.style.marginLeft = sidebar.classList.contains('show') ? '240px' : '0';
                }
            });
        }

        // Search Form Submission with Debounce
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchInput.classList.add('loading');
                searchTimeout = setTimeout(() => {
                    window.location.href = `?search=${encodeURIComponent(e.target.value.trim())}`;
                    searchInput.classList.remove('loading');
                }, 300);
            });
        }

        // Highlight active sidebar link
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === window.location.pathname) {
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

        // Handle dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdownMenu = toggle.nextElementSibling;
                if (dropdownMenu) {
                    const isShown = dropdownMenu.classList.contains('show');
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
                    document.querySelectorAll('.dropdown-toggle').forEach(t => {
                        t.classList.remove('show');
                        t.setAttribute('aria-expanded', 'false');
                    });
                    if (!isShown) {
                        dropdownMenu.classList.add('show');
                        toggle.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
                document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                    toggle.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }
        });
    });
</script>
@endsection
