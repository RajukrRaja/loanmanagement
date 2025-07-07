
@extends('superadmin.superadmin')

@section('title', 'Closed Loan Report')

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
    
    .excel-table td.current-status.status-approved {
    color: green;
    font-weight: bold;
}

.excel-table td.current-status.status-closed {
    color: red;
    font-weight: bold;
}


/* Default style */
.excel-table td.current-status {
    font-weight: bold;
}

/* Approved – Green */
.excel-table td.current-status.approved-text {
    color: green;
}

/* Closed – Red */
.excel-table td.current-status.closed-text {
    color: red;
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
        font-size: 18px;
        font-weight:bold;
        vertical-align: middle;
        font-weight: 500;
        color: #616161;
        background-color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
        transition: background-color 0.2s ease;
        height:140px;
    }

   /* Column-specific text colors */
.excel-table .branch-name {
    color: orange; /* Or use a hex code like #ff9800 */
}




/* Column-specific text colors (only for table body cells) */
.excel-table td.status {
    color: orange; /* Branch Name */
}
.excel-table td.branch-name {
    color: orange; /* Branch Name */
}

.excel-table td.bg-loan {
    color: darkgreen; /* Loan Amount */
}

.excel-table td.bg-interest {
    color: darkblue; /* Interest Amount */
}


  
    /* Optional: Background for email column */
    .excel-table .bg-email {
        
    }

    /* Column-specific backgrounds */
    .bg-branch {


    }

 

    .bg-interest {
       
    }

    .bg-disbursed {
       
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
    
    
    .excel-table td.current-status.approved {
    color: green;
    font-weight: bold;
}

.excel-table td.current-status.rejected,
.excel-table td.current-status.pending {
    color: red;
    font-weight: bold;
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
    }

    .export-btn:hover {
        background-color: #218838;
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

    /* Dark mode overrides for colored text */
    [data-theme="dark"] .excel-table .branch-name {
        color: #80ccff; /* Lighter blue for dark mode */
    }

  
  

    [data-theme="dark"] .excel-table .email {
        color: #ff6666 !important; /* Lighter red for dark mode */
    }

    [data-theme="dark"] .excel-table .bg-email {
        background-color: #2a3d4f !important; /* Darker background for email column */
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
        <div class="alert alert-danger text-center" role="alert">
            You must be logged in to view this page.
        </div>
    @else
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4 main-content">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Closed Loan Report</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn export-btn" id="exportBtn" aria-label="Export to CSV">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn theme-toggle" id="themeToggle" aria-label="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>

            @if ($loans->isEmpty())
                <div class="alert alert-info text-center" role="alert">
                    No closed loans found.
                </div>
            @else
                <div class="excel-table-container">
                    <table class="excel-table" id="loanTable" role="grid" aria-describedby="closedLoansTable">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Loan ID</th>
                                <th class="bg-branch">Branch</th>
                                <th class="employee-name">Employee</th>
                                <th>User</th>
                                <th class="bg-email">Email</th>
                                <th class="bg-loan">Amount</th>
                                <th>Mobile</th>
                                <th class="bg-status-kyc">KYC Status</th>
                                <th class="bg-status">Current Status</th>
                                <th class="bg-close-date">Close Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $index => $loan)
                                <tr>
                                  <td>{{ $index + 1 }}</td>

                                    <td>{{ $loan->loan_id ?? 'N/A' }}</td>
                                    <td class="branch-name bg-branch">{{ $loan->branch_name ?? 'N/A' }}</td>
                                    <td class="employee-name">{{ $loan->employee_name ?? 'N/A' }}</td>
                                    <td class="user-name">
                                        <a href="{{ route('employee.viewLeadDetails', ['id' => $loan->lead_id]) }}"
                                           class="action-link"
                                           title="View Lead Details"
                                           aria-label="View lead details for {{ $loan->loan_id ?? 'N/A' }}">
                                            {{ trim(($loan->first_name ?? '') . ' ' . ($loan->last_name ?? '')) ?: 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="email bg-email">{{ $loan->email ?? 'N/A' }}</td>
                                    <td class="bg-loan">
                                        {{ $loan->approved_loan_amount ? '₹' . number_format(round($loan->approved_loan_amount), 0, '.', ',') : 'N/A' }}
                                    </td>
                                    <td>{{ $loan->mobile_no ?? 'N/A' }}</td>
                                    <td class="status-kyc bg-status-kyc">{{ $loan->kyc_status ?? 'N/A' }}</td>
                                    <td class="status-current bg-status">{{ $loan->current_status ?? 'N/A' }}</td>
                                    <td class="bg-close-date">
                                        {{ $loan->closed_on ? \Carbon\Carbon::parse($loan->closed_on)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('emi.result', ['lead_id' => $loan->lead_id]) }}"
                                           class="action-btn"
                                           title="View Details"
                                           aria-label="View loan details for {{ $loan->loan_id ?? 'N/A' }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination mt-3">
                    @if ($loans instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $loans->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            @endif

            <footer class="text-center mt-4">
                © 2025 Loan Management System
            </footer>
        </main>
    @endif
@endsection


@section('scripts')
    <script>
        // Theme Toggle Functionality
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

        // Sidebar Toggle Functionality
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

        // Table Sorting Functionality
        const table = document.getElementById('loanTable');
        if (table) {
            const headers = table.querySelectorAll('th[data-sort]');
            let currentSortKey = null;
            let isAscending = true;

            headers.forEach(header => {
                header.addEventListener('click', () => {
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

                    if (key === 'index') {
                        aValue = parseInt(a.cells[0].textContent) || 0;
                        bValue = parseInt(b.cells[0].textContent) || 0;
                    } else if (key === 'amount') {
                        aValue = parseFloat(a.cells[6].textContent.replace(/[^0-9.-]+/g, '')) || 0;
                        bValue = parseFloat(b.cells[6].textContent.replace(/[^0-9.-]+/g, '')) || 0;
                    } else if (key === 'close_date') {
                        aValue = a.cells[10].textContent === 'N/A' ? new Date(0) : new Date(a.cells[10].textContent);
                        bValue = b.cells[10].textContent === 'N/A' ? new Date(0) : new Date(b.cells[10].textContent);
                    } else if (key === 'user_name') {
                        const aLink = a.cells[columnIndex].querySelector('a');
                        const bLink = b.cells[columnIndex].querySelector('a');
                        aValue = aLink ? aLink.textContent.toLowerCase() : a.cells[columnIndex].textContent.toLowerCase();
                        bValue = bLink ? bLink.textContent.toLowerCase() : b.cells[columnIndex].textContent.toLowerCase();
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
                const headers = [
                    'index', 'loan_id', 'branch_name', 'employee_name', 'user_name',
                    'email', 'amount', 'mobile', 'status', 'current_status', 'close_date'
                ];
                return headers.indexOf(key);
            }
        }

        // Export to CSV Functionality
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                exportBtn.classList.add('loading');
                setTimeout(() => {
                    const table = document.getElementById('loanTable');
                    const rows = table.querySelectorAll('tr');
                    let csvContent = 'data:text/csv;charset=utf-8,';
                    const headers = Array.from(table.querySelectorAll('th'))
                        .slice(0, -1) // Exclude action column
                        .map(th => `"${th.textContent.replace(/"/g, '""')}"`)
                        .join(',');
                    csvContent += headers + '\r\n';

                    rows.forEach(row => {
                        const rowData = Array.from(row.cells)
                            .slice(0, -1) // Exclude action column
                            .map(cell => {
                                const link = cell.querySelector('a');
                                const text = link ? link.textContent : cell.textContent;
                                return `"${text.replace(/"/g, '""').replace(/[\n\r]+/g, '')}"`;
                            })
                            .join(',');
                        csvContent += rowData + '\r\n';
                    });

                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement('a');
                    link.setAttribute('href', encodedUri);
                    link.setAttribute('download', `closed_loan_report_${new Date().toISOString().split('T')[0]}.csv`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    exportBtn.classList.remove('loading');
                }, 500); // Simulate processing delay
            });
        }

        // Keyboard Accessibility for Buttons
        document.querySelectorAll('.action-btn, .export-btn, .theme-toggle, .sidebar-toggle').forEach(button => {
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });

        // Smooth Scroll for Pagination
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const href = link.getAttribute('href');
                if (href && href !== '#') {
                    window.location.href = href;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    </script>
@endsection
