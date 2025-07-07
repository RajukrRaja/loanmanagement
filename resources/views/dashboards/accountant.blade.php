<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accountant Dashboard</title>
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
                <img src="https://ui-avatars.com/api/?name=Accountant" alt="Profile" class="profile-img mb-2">
                <h5>Accountant</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#vouchersTable"><i class="fas fa-receipt me-2"></i> Vouchers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#ledgersTable"><i class="fas fa-book me-2"></i> Ledgers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cashbookTable"><i class="fas fa-money-bill me-2"></i> Cash Book</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bankbookTable"><i class="fas fa-bank me-2"></i> Bank Book</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#trialbalanceTable"><i class="fas fa-balance-scale me-2"></i> Trial Balance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#plTable"><i class="fas fa-chart-pie me-2"></i> Profit & Loss</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reconciliationTable"><i class="fas fa-sync-alt me-2"></i> EMI Reconciliation</a>
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
                    <h2 class="h3 mb-0 fw-bold" style="letter-spacing:0.01em;">Accountant Dashboard</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control search-bar" placeholder="Search...">
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="row mb-4 g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-receipt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Vouchers Today</h6>
                                    <h4 class="mb-0 fw-bold">25</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 5 new entries
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-money-bill fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Cash Balance</h6>
                                    <h4 class="mb-0 fw-bold">$10,000</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> $500 today
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-bank fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Bank Balance</h6>
                                    <h4 class="mb-0 fw-bold">$50,000</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> $2,000 today
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-sync-alt fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Reconciled EMIs</h6>
                                    <h4 class="mb-0 fw-bold">30</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 10 today
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addVoucherModal"><i class="fas fa-receipt me-2"></i> Add Voucher</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addLedgerModal"><i class="fas fa-book me-2"></i> Add Ledger Entry</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#trialbalanceTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-balance-scale me-2"></i> View Trial Balance</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#reconciliationTable" class="btn btn-outline-info w-100 py-3 shadow-sm"><i class="fas fa-sync-alt me-2"></i> View Reconciliation</a>
                </div>
            </div>
            <!-- Modals -->
            <!-- Add Voucher Modal -->
            <div class="modal fade" id="addVoucherModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Add Voucher Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addVoucherForm">
                                <div class="mb-3">
                                    <label>Voucher Type</label>
                                    <select name="voucherType" class="form-control" required>
                                        <option value="Payment">Payment</option>
                                        <option value="Receipt">Receipt</option>
                                        <option value="Journal">Journal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Ledger Account</label>
                                    <input type="text" name="ledgerAccount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Ledger Modal -->
            <div class="modal fade" id="addLedgerModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Add Ledger Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addLedgerForm">
                                <div class="mb-3">
                                    <label>Ledger Account</label>
                                    <input type="text" name="ledgerAccount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Debit Amount</label>
                                    <input type="number" name="debit" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Credit Amount</label>
                                    <input type="number" name="credit" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Vouchers Table -->
            <div class="card mb-4 shadow-sm" id="vouchersTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Voucher Entries</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Voucher ID</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Ledger Account</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#VC1001</td>
                                    <td>Payment</td>
                                    <td>2025-06-02</td>
                                    <td>$500</td>
                                    <td>Office Expenses</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#VC1002</td>
                                    <td>Receipt</td>
                                    <td>2025-06-02</td>
                                    <td>$1,000</td>
                                    <td>EMI Collection</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Ledgers Table -->
            <div class="card mb-4 shadow-sm" id="ledgersTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ledger Entries</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Ledger ID</th>
                                    <th>Account</th>
                                    <th>Date</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LD1001</td>
                                    <td>Office Expenses</td>
                                    <td>2025-06-02</td>
                                    <td>$500</td>
                                    <td>-</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LD1002</td>
                                    <td>EMI Collection</td>
                                    <td>2025-06-02</td>
                                    <td>-</td>
                                    <td>$1,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Cash Book Table -->
            <div class="card mb-4 shadow-sm" id="cashbookTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Cash Book</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Receipt</th>
                                    <th>Payment</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-06-02</td>
                                    <td>EMI Collection</td>
                                    <td>$1,000</td>
                                    <td>-</td>
                                    <td>$10,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2025-06-02</td>
                                    <td>Office Expenses</td>
                                    <td>-</td>
                                    <td>$500</td>
                                    <td>$9,500</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Bank Book Table -->
            <div class="card mb-4 shadow-sm" id="bankbookTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bank Book</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Deposit</th>
                                    <th>Withdrawal</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-06-02</td>
                                    <td>EMI Deposit</td>
                                    <td>$2,000</td>
                                    <td>-</td>
                                    <td>$50,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2025-06-02</td>
                                    <td>Salary Payment</td>
                                    <td>-</td>
                                    <td>$1,000</td>
                                    <td>$49,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Trial Balance Table -->
            <div class="card mb-4 shadow-sm" id="trialbalanceTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trial Balance</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Office Expenses</td>
                                    <td>$500</td>
                                    <td>-</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>EMI Collection</td>
                                    <td>-</td>
                                    <td>$1,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Profit & Loss Table -->
            <div class="card mb-4 shadow-sm" id="plTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Profit & Loss Statement</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Revenue (EMI Collections)</td>
                                    <td>$5,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Expenses (Office)</td>
                                    <td>$2,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Net Profit</td>
                                    <td>$3,000</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- EMI Reconciliation Table -->
            <div class="card mb-4 shadow-sm" id="reconciliationTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">EMI Payment vs Deposit Reconciliation</h5>
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
                                    <th>Payment Date</th>
                                    <th>Deposit Amount</th>
                                    <th>Deposit Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LN1001</td>
                                    <td>Jane Doe</td>
                                    <td>$500</td>
                                    <td>2025-06-02</td>
                                    <td>$500</td>
                                    <td>2025-06-02</td>
                                    <td><span class="badge bg-success">Reconciled</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LN1002</td>
                                    <td>Mike Brown</td>
                                    <td>$400</td>
                                    <td>2025-06-02</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
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
                        <li class="list-group-item">Finance meeting on <strong>2025-06-03</strong> at 11:00 AM.</li>
                        <li class="list-group-item">Complete EMI reconciliation by <strong>2025-06-05</strong>. <a href="#">Read more</a></li>
                        <li class="list-group-item">New ledger entry guidelines issued.</li>
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

    // Fetch data for tables
    async function fetchTableData(endpoint, tableId) {
        try {
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });
            if (response.ok) {
                const data = await response.json();
                console.log(`Fetched data for ${tableId}:`, data);
                // Placeholder: Update table rows dynamically with fetched data
            } else {
                alert(`Error fetching data for ${tableId}`);
            }
        } catch (error) {
            alert('Network error');
        }
    }

    // Add Voucher Form Submission
    document.getElementById('addVoucherForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const voucherData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/vouchers', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(voucherData)
            });
            if (response.ok) {
                alert('Voucher added successfully');
                bootstrap.Modal.getInstance(document.getElementById('addVoucherModal')).hide();
                e.target.reset();
                fetchTableData('/api/vouchers', 'vouchersTable');
            } else {
                alert('Error adding voucher');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // Add Ledger Form Submission
    document.getElementById('addLedgerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const ledgerData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/ledgers', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(ledgerData)
            });
            if (response.ok) {
                alert('Ledger entry added successfully');
                bootstrap.Modal.getInstance(document.getElementById('addLedgerModal')).hide();
                e.target.reset();
                fetchTableData('/api/ledgers', 'ledgersTable');
            } else {
                alert('Error adding ledger entry');
            }
        } catch (error) {
            alert('Network error');
        }
    });

  
</script>
</body>
</html>