<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Branch Login Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e3f0ff 0%, #fafcff 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #23272b 70%, #0d1117 100%);
            color: #fff;
            box-shadow: 2px 0 16px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #bfc9d1;
            font-weight: 500;
            border-radius: 0.5rem;
            margin-bottom: 0.3rem;
            padding: 0.75rem 1.2rem;
            letter-spacing: 0.02em;
            position: relative;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, #0d6efd 60%, #2563eb 100%);
            box-shadow: 0 2px 12px rgba(13,110,253,0.08);
        }
        .sidebar .nav-link i {
            width: 22px;
            text-align: center;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 16px rgba(13,110,253,0.12);
            margin-bottom: 0.5rem;
            background: #fff;
        }
        .sidebar h5 {
            font-weight: 700;
            margin-bottom: 0.2rem;
        }
        .badge.bg-success {
            background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%) !important;
        }
        .card-stats {
            border-left: 6px solid #0d6efd;
            border-radius: 0.75rem;
            background: linear-gradient(120deg, #fff 70%, #e3f0ff 100%);
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 12px rgba(13,110,253,0.04);
            overflow: hidden;
            position: relative;
        }
        .card-stats:hover {
            box-shadow: 0 8px 32px rgba(13,110,253,0.12);
            transform: translateY(-3px) scale(1.02);
        }
        .card-stats .card-body {
            position: relative;
            z-index: 2;
        }
        .card-stats::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 80px;
            height: 80px;
            background: rgba(13,110,253,0.07);
            border-radius: 50%;
            z-index: 1;
        }
        .table thead {
            background: linear-gradient(90deg, #0d6efd 60%, #2563eb 100%);
            color: #fff;
        }
        .card-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .search-bar {
            max-width: 320px;
            border-radius: 2rem;
            border: 1px solid #dbeafe;
            box-shadow: 0 2px 8px rgba(13,110,253,0.04);
            padding-left: 1.2rem;
            transition: border 0.2s;
        }
        .search-bar:focus {
            border: 1.5px solid #0d6efd;
            outline: none;
            box-shadow: 0 2px 16px rgba(13,110,253,0.08);
        }
        .btn-primary, .btn-outline-primary {
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: linear-gradient(90deg, #0d6efd 60%, #2563eb 100%);
            color: #fff;
            border-color: #0d6efd;
        }
        .btn-outline-success:hover, .btn-outline-success:focus {
            background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%);
            color: #fff;
            border-color: #22c55e;
        }
        .btn-outline-warning:hover, .btn-outline-warning:focus {
            background: linear-gradient(90deg, #facc15 60%, #eab308 100%);
            color: #fff;
            border-color: #facc15;
        }
        .btn-outline-info:hover, .btn-outline-info:focus {
            background: linear-gradient(90deg, #38bdf8 60%, #0ea5e9 100%);
            color: #fff;
            border-color: #38bdf8;
        }
        .list-group-item {
            background: transparent;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            font-size: 1rem;
            padding: 0.9rem 1.2rem;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background: #f1f5f9;
            transition: background 0.2s;
        }
        .btn-sm {
            border-radius: 1.5rem;
        }
        .card {
            border-radius: 1rem;
            border: none;
        }
        .shadow-sm {
            box-shadow: 0 2px 12px rgba(13,110,253,0.06) !important;
        }
        .sidebar-toggle {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-toggle:focus {
            outline: 2px solid #0d6efd;
        }
        ::-webkit-scrollbar {
            width: 8px;
            background: #e3f0ff;
        }
        ::-webkit-scrollbar-thumb {
            background: #b6c7e3;
            border-radius: 4px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                min-height: auto;
                position: fixed;
                z-index: 1040;
                left: -240px;
                width: 240px;
                top: 0;
                bottom: 0;
                transition: left 0.3s;
            }
            .sidebar.show {
                left: 0;
            }
            .sidebar-toggle {
                display: inline-flex;
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
            .card-stats .card-body h4 {
                font-size: 1.1rem;
            }
            .card-stats .card-body h6 {
                font-size: 0.9rem;
            }
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
                <img src="https://ui-avatars.com/api/?name=Branch+Login" alt="Profile" class="profile-img mb-2">
                <h5>Branch Login</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#employeesTable"><i class="fas fa-users me-2"></i> Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="fas fa-money-check-alt me-2"></i> Loans</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newLoanModal">New Loan Entry</a></li>
                        <li><a class="dropdown-item" href="#emiTable">EMI Tracking</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reportsTable"><i class="fas fa-chart-line me-2"></i> Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#walletTable"><i class="fas fa-wallet me-2"></i> Wallet & Cash</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-bell me-2"></i> Notifications</a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <!-- Main Content -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4" style="transition: margin-left 0.3s;">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary sidebar-toggle me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2 class="h3 mb-0 fw-bold" style="letter-spacing:0.01em;">Branch Dashboard (Downtown)</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search...">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newLoanModal"><i class="fas fa-plus"></i> New Loan</button>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="row mb-4 g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Branch Employees</h6>
                                    <h4 class="mb-0 fw-bold">45</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 1 new hire
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Active Loans</h6>
                                    <h4 class="mb-0 fw-bold">80</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-down text-danger"></i> 2 less than last week
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-hand-holding-usd fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">EMI Collected</h6>
                                    <h4 class="mb-0 fw-bold">$8,500</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 5% this month
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-wallet fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Wallet Balance</h6>
                                    <h4 class="mb-0 fw-bold">$12,000</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> $2,000 added
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newLoanModal"><i class="fas fa-file-invoice-dollar me-2"></i>New Loan</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-success w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#recordEMIModal"><i class="fas fa-hand-holding-usd me-2"></i>Record EMI</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-info w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#generateReportModal"><i class="fas fa-file-alt me-2"></i>Generate Report</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-warning w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#walletRequestModal"><i class="fas fa-wallet me-2"></i>Wallet Request</a>
                </div>
            </div>
            <!-- Modals -->
            <!-- New Loan Modal -->
            <div class="modal fade" id="newLoanModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>New Loan Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="newLoanForm">
                                <div class="mb-3">
                                    <label>Borrower Name</label>
                                    <input type="text" name="borrower" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Loan Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Interest Rate (%)</label>
                                    <input type="number" step="0.01" name="interest" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Tenure (Months)</label>
                                    <input type="number" name="tenure" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Record EMI Modal -->
            <div class="modal fade" id="recordEMIModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Record EMI Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="recordEMIForm">
                                <div class="mb-3">
                                    <label>Loan ID</label>
                                    <input type="text" name="loanId" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>EMI Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Payment Date</label>
                                    <input type="date" name="paymentDate" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Record</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Generate Report Modal -->
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
                                    <label>Report Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="loan_summary">Loan Summary</option>
                                        <option value="emi_collection">EMI Collection</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Date Range</label>
                                    <input type="text" name="dateRange" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Wallet Request Modal -->
            <div class="modal fade" id="walletRequestModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Request Wallet Funds</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="walletRequestForm">
                                <div class="mb-3">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Purpose</label>
                                    <input type="text" name="purpose" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Employees Table -->
            <div class="card mb-4 shadow-sm" id="employeesTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Branch Employees</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Smith</td>
                                    <td>john@example.com</td>
                                    <td>Loan Officer</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- EMI Tracking Table -->
            <div class="card mb-4 shadow-sm" id="emiTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">EMI Tracking</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Borrower</th>
                                    <th>EMI Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LN1001</td>
                                    <td>Jane Doe</td>
                                    <td>$500</td>
                                    <td>2025-06-15</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary record-emi" data-loan-id="LN1001" data-bs-toggle="modal" data-bs-target="#recordEMIModal"><i class="fas fa-hand-holding-usd"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Wallet & Cash Approval Table -->
            <div class="card mb-4 shadow-sm" id="walletTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Wallet & Cash Requests</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Amount</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#WR1001</td>
                                    <td>$2,000</td>
                                    <td>Loan Disbursement</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>2025-06-01</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Reports Table -->
            <div class="card mb-4 shadow-sm" id="reportsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Branch Reports</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Type</th>
                                    <th>Date Range</th>
                                    <th>Generated On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#RP1001</td>
                                    <td>Loan Summary</td>
                                    <td>2025-05-01 to 2025-06-01</td>
                                    <td>2025-06-02</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Announcements -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Branch meeting on <strong>2025-06-05</strong> at 10:00 AM.</li>
                        <li class="list-group-item">EMI collection target updated. <a href="#">Read more</a></li>
                        <li class="list-group-item">New wallet request process implemented.</li>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebarMenu').classList.toggle('show');
    });

    // New Loan Form Submission
    document.getElementById('newLoanForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const loanData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/loans', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(loanData)
            });
            if (response.ok) {
                alert('Loan entered successfully');
                bootstrap.Modal.getInstance(document.getElementById('newLoanModal')).hide();
                // Refresh EMI tracking table
            } else {
                alert('Error entering loan');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // Record EMI Form Submission
    document.querySelectorAll('.record-emi').forEach(button => {
        button.addEventListener('click', function() {
            const loanId = this.getAttribute('data-loan-id');
            const form = document.getElementById('recordEMIForm');
            form.querySelector('[name="loanId"]').value = loanId;
        });
    });
    document.getElementById('recordEMIForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const emiData = Object.fromEntries(formData);
        try {
            const response = await fetch(`/api/loans/${emiData.loanId}/emi`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(emiData)
            });
            if (response.ok) {
                alert('EMI recorded successfully');
                bootstrap.Modal.getInstance(document.getElementById('recordEMIModal')).hide();
                // Refresh EMI tracking table
            } else {
                alert('Error recording EMI');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // Generate Report
    document.getElementById('reportForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const reportData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/reports', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(reportData)
            });
            if (response.ok) {
                alert('Report generated successfully');
                bootstrap.Modal.getInstance(document.getElementById('generateReportModal')).hide();
                // Refresh reports table
            } else {
                alert('Error generating report');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // Wallet Request Submission
    document.getElementById('walletRequestForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const walletData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/wallet/request', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(walletData)
            });
            if (response.ok) {
                alert('Wallet request submitted successfully');
                bootstrap.Modal.getInstance(document.getElementById('walletRequestModal')).hide();
                // Refresh wallet table
            } else {
                alert('Error submitting request');
            }
        } catch (error) {
            alert('Network error');
        }
    });
</script>
</body>
</html>