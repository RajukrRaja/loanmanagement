@extends('superadmin.superadmin')

@section('title', 'Approved ENACH Approvals')

@section('sidebar')
    @include('superadmin.superadminSidebar')
@endsection

@section('styles')
<style>
:root {
    --primary-color: #005bb5;
    --success-color: #2e7d32;
    --danger-color: #d32f2f;
    --text-color: #212121;
    --bg-color: #fafafa;
    --card-bg: #ffffff;
    --border-color: #e0e0e0;
    --form-border: #d1d5db;
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
    --primary-color: #3b82f6;
    --success-color: #4caf50;
    --danger-color: #ef5350;
    --text-color: #e0e0e0;
    --bg-color: #1f2937;
    --card-bg: #374151;
    --border-color: #4b5563;
    --form-border: #4b5563;
    --bg-light: #1e293b;
    --bg-dark: #111827;
    --text-dark: #f1f5f9;
    --text-muted: #9ca3af;
}
body {
    background: var(--bg-color);
    color: var(--text-color);
    font-size: 16px;
    line-height: 1.6;
    margin: 0;
}
.main-content {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
}
.form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}
.error {
    color: var(--danger-color);
    font-size: 0.85rem;
    margin-top: 0.25rem;
    font-weight: 500;
}
.excel-table-container {
    overflow-x: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin: 30px 0;
    max-width: 100%;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
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
}
.excel-table .branch-name {
    color: #0066cc;
}
.excel-table .employee-name {
    color: #cc6600;
}
.excel-table td.bg-branch,
.excel-table td.bg-loan,
.excel-table td.bg-interest,
.excel-table td.bg-disbursed,
.excel-table td.bg-email {
    background-color: transparent !important;
}
[data-theme="dark"] .excel-table td.bg-branch,
[data-theme="dark"] .excel-table td.bg-loan,
[data-theme="dark"] .excel-table td.bg-interest,
[data-theme="dark"] .excel-table td.bg-disbursed,
[data-theme="dark"] .excel-table td.bg-email {
    background-color: transparent !important;
}
.excel-table th {
    background-color: #6a1b9a;
    color: #ffffff;
    font-weight: 700;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 18px;
    white-space: nowrap;
    padding: 14px 18px;
    border-bottom: 2px solid #0b3d91;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: 0.5px;
}
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
}
.action-btn i {
    font-size: 16px;
}
.disbursement-form {
    display: none;
    background: var(--bg-light);
    padding: 1rem;
    border-top: 1px solid #e0e0e0;
    animation: slideDown 0.3s ease;
    margin-left:2cm;
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.disbursement-form-inner {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    align-items: flex-end;
    width: 60%;
    
}
.disbursement-form-inner > div {
    display: flex;
    flex-direction: column;
}
.disbursement-form-inner .form-actions {
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
   
}
.form-control, .form-select {
    padding: 0.5rem;
    font-size: 0.9rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: #ffffff;
    color: #212121;
    height: 38px;
    width: 100%;
    margin: 0;
}
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.form-control:invalid, .form-select:invalid {
    border-color: #d32f2f;
}
.btn-success.btn-sm {
    width: auto;
}
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 1.5rem;
    border: none;
    cursor: pointer;
    height: 38px;
    line-height: 1.5;
    margin-left:1cm;
}
.btn-primary {
    background: #2563eb;
    color: #ffffff;
}
.btn-success {
    background: #22c55e;
    color: #ffffff;
}
.search-bar {
    max-width: 320px;
    font-size: 0.9rem;
    border-radius: 2rem;
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.search-bar:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
.alert {
    border-radius: 0.5rem;
    padding: 1rem;
    font-size: 0.9rem;
    border: 1px solid #e0e0e0;
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
    background: #1e293b;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 3px;
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
    background: linear-gradient(to right, #2a2a2a, #3a3a3a);
    color: #ffffff;
}
[data-theme="dark"] .branch-name {
    color: #80ccff;
}
[data-theme="dark"] .employee-name {
    color: #ffcc80;
}
[data-theme="dark"] .email {
    color: #ff6666 !important;
}
[data-theme="dark"] .bg-branch {
    background-color: #1b2c3a !important;
}
[data-theme="dark"] .bg-loan {
    background-color: #3d341e !important;
}
[data-theme="dark"] .action-btn {
    background-color: #2a2a2a;
    border-color: #444;
    color: #e0e0e0;
}
/* Responsive Design */
@media (max-width: 1200px) {
    .disbursement-form-inner {
        grid-template-columns: repeat(2, 1fr);
    }
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
    .main-content {
        padding: 1.5rem;
    }
    .excel-table-container {
        border-radius: 0.5rem;
        overflow-x: auto;
    }
    .excel-table {
        min-width: 1000px;
    }
}
@media (min-width: 992px) {
    .sidebar-toggle {
        display: none;
    }
}
@media (max-width: 767.98px) {
    .disbursement-form-inner {
        grid-template-columns: 1fr;
    }
    .disbursement-form-inner .form-actions {
        justify-content: flex-start;
    }
    .excel-table th:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(7)):not(:nth-child(11)):not(:nth-child(12)),
    .excel-table td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(7)):not(:nth-child(11)):not(:nth-child(12)) {
        display: none;
    }
}
@media (max-width: 576px) {
    .main-content {
        padding: 1rem;
    }
    .search-bar {
        max-width: 100%;
    }
    .d-flex.align-items-center.gap-2 {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
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
    <div class="alert alert-danger text-center" role="alert">You must be logged in to view this page.</div>
@else
    <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4 main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-primary sidebar-toggle" id="sidebarToggle" data-bs-toggle="tooltip" title="Toggle Sidebar" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
                <h2 class="h3 mb-0 fw-bold">Approved ENACH Approvals</h2>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control search-bar" id="searchInput" placeholder="Search approvals..." value="{{ request()->query('search', '') }}" aria-label="Search approvals">
                <button class="btn theme-toggle" id="themeToggle" data-bs-toggle="tooltip" title="Toggle Dark Mode" aria-label="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($enachApprovals->isEmpty())
            <div class="alert alert-info text-center" role="alert">No approved ENACH records found.</div>
        @else
            <div class="excel-table-container">
                <table class="excel-table" role="grid" aria-describedby="enachApprovalsTable">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Lead ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col" class="bg-branch">Branch Name</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col" class="bg-loan">Loan Approved Amount</th>
                            <th scope="col">Branch Recommendation</th>
                            <th scope="col">ENACH Status</th>
                            <th scope="col">Approved At</th>
                            <th scope="col">Loan Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enachApprovals as $index => $approval)
                            <tr>
                                <td>{{ $enachApprovals->firstItem() + $index }}</td>
                                <td>{{ $approval->lead_id ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('employee.viewLeadDetails', ['id' => $approval->lead_id]) }}">
                                        {{ $approval->full_name ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="email">{{ $approval->email ?? 'N/A' }}</td>
                                <td class="branch-name bg-branch">{{ $approval->branch_name ?? 'N/A' }}</td>
                                <td class="employee-name">{{ $approval->employee_name ?? 'N/A' }}</td>
                                <td class="bg-loan">{{ $approval->loan_approved_amount ? '₹' . number_format($approval->loan_approved_amount, 2, '.', ',') : 'N/A' }}</td>
                                <td>{{ $approval->branch_recommendation ?? 'N/A' }}</td>
                                <td class="status-approved">{{ $approval->status_of_enach ?? 'Approved' }}</td>
                                <td>{{ $approval->updated_at ? \Carbon\Carbon::parse($approval->updated_at)->format('d/m/y H:i') : 'N/A' }}</td>
                                <td class="status-closed">{{ $approval->loan_status ?? 'N/A' }}</td>
                                <td>
                                    <a href="#" class="action-btn view-btn" data-lead-id="{{ $approval->lead_id }}" title="View Disbursement Form" aria-label="View Disbursement Form for Lead {{ $approval->lead_id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr class="disbursement-form" id="form-{{ $approval->lead_id }}">
                                <td colspan="12">
                                    <form class="disbursement-form-inner" method="POST" action="{{ route('showEMI') }}" aria-labelledby="disbursementForm_{{ $approval->lead_id }}">
                                        @csrf
                                        <input type="hidden" name="lead_id" value="{{ $approval->lead_id }}">
                                        <div>
                                            <label for="disbursement_date_{{ $approval->lead_id }}" class="form-label">Disbursement Date</label>
                                            <input type="date" class="form-control" id="disbursement_date_{{ $approval->lead_id }}" name="disbursement_date" required aria-required="true">
                                            @error('disbursement_date')
                                                <span class="error" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="emi_date_{{ $approval->lead_id }}" class="form-label">EMI Date</label>
                                            <input type="date" class="form-control" id="emi_date_{{ $approval->lead_id }}" name="emi_date" required aria-required="true">
                                            @error('emi_date')
                                                <span class="error" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="loan_type_{{ $approval->lead_id }}" class="form-label">Loan Type</label>
                                            <select class="form-select" name="loan_type" id="loan_type_{{ $approval->lead_id }}" required aria-required="true">
                                                <option value="">Select Loan Type</option>
                                                <option value="Housing Loan">Housing Loan</option>
                                                <option value="Property Loan">Property Loan</option>
                                                <option value="Personal Loan">Personal Loan</option>
                                                <option value="Consumer Loan">Consumer Loan</option>
                                                <option value="Gold Loan">Gold Loan</option>
                                                <option value="Education Loan">Education Loan</option>
                                                <option value="Two Wheeler Loan">Two Wheeler Loan</option>
                                                <option value="Tractor Loan">Tractor Loan</option>
                                                <option value="Business Loan - Secured">Business Loan - Secured</option>
                                                <option value="Business Loan - General">Business Loan - General</option>
                                            </select>
                                            @error('loan_type')
                                                <span class="error" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="cash_mode_{{ $approval->lead_id }}" class="form-label">Payment Mode</label>
                                            <select class="form-select" name="payment_mode" id="cash_mode_{{ $approval->lead_id }}" required aria-required="true">
                                                <option value="">Select Payment Mode</option>
                                                <option value="Cash">Cash</option>
                                                <option value="NEFT">NEFT</option>
                                                <option value="RTGS">RTGS</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="UPI">UPI</option>
                                            </select>
                                            @error('payment_mode')
                                                <span class="error" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
<div class="form-actions">
    <button type="submit" class="btn btn-success btn-sm" aria-label="Disburse Loan" style="margin-left: 50px; width: 200px;">
        Disburse
    </button>
</div>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $enachApprovals instanceof \Illuminate\Pagination\LengthAwarePaginator ? $enachApprovals->appends(['search' => request()->query('search')])->links('pagination::bootstrap-5') : '' }}
            </div>
        @endif
        <footer class="pt-3 mt-4 text-muted border-top text-center small">
            © 2025 Loan Management System
        </footer>
    </main>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            });
        }, 5000);

        // Handle View button clicks for disbursement form
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const leadId = this.getAttribute('data-lead-id');
                const formRow = document.getElementById(`form-${leadId}`);
                const allFormRows = document.querySelectorAll('.disbursement-form');

                allFormRows.forEach(row => {
                    if (row.id !== `form-${leadId}`) {
                        row.style.display = 'none';
                        row.setAttribute('aria-hidden', 'true');
                    }
                });

                formRow.style.display = formRow.style.display === 'none' || formRow.style.display === '' ? 'table-row' : 'none';
                formRow.setAttribute('aria-hidden', formRow.style.display === 'none' ? 'true' : 'false');
            });
        });

        // Search Form Submission
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    window.location.href = `?search=${encodeURIComponent(e.target.value.trim())}`;
                }, 300);
            });
        }

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
                themeToggle.setAttribute('aria-label', currentTheme === 'dark' ? 'Switch to Dark Mode' : 'Switch to Light Mode');
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
                sidebarToggle.setAttribute('aria-expanded', sidebar.classList.contains('show') ? 'true' : 'false');
            });
        }

        // Highlight active sidebar link
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '{{ route('admin.adminApprovedEnachUserView') }}') {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
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