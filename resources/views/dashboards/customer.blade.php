<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
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
        #selfieCanvas {
            border: 2px solid #0d6efd;
            border-radius: 0.5rem;
            max-width: 100%;
        }
        #selfiePreview {
            max-width: 100%;
            border-radius: 0.5rem;
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
                <img src="https://ui-avatars.com/api/?name=Customer" alt="Profile" class="profile-img mb-2">
                <h5>Customer</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#loansTable"><i class="fas fa-money-check-alt me-2"></i> My Loans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#applyLoanModal"><i class="fas fa-plus me-2"></i> Apply Loan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#ekycModal"><i class="fas fa-camera me-2"></i> eKYC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#emiTable"><i class="fas fa-calendar-alt me-2"></i> EMI Calendar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#documentsTable"><i class="fas fa-file-signature me-2"></i> eSign Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#offersTable"><i class="fas fa-gift me-2"></i> Pre-approved Offers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoUpdateModal"><i class="fas fa-user-edit me-2"></i> Update Info</a>
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
                    <h2 class="h3 mb-0 fw-bold" style="letter-spacing:0.01em;">Customer Dashboard</h2>
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
                                    <i class="fas fa-money-check-alt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Active Loans</h6>
                                    <h4 class="mb-0 fw-bold">2</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 1 new loan
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Upcoming EMIs</h6>
                                    <h4 class="mb-0 fw-bold">3</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> Due this month
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-gift fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Pre-approved Offers</h6>
                                    <h4 class="mb-0 fw-bold">1</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> New offer
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-file-signature fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Pending eSign</h6>
                                    <h4 class="mb-0 fw-bold">2</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> Action required
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#applyLoanModal"><i class="fas fa-plus me-2"></i> Apply Loan</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#ekycModal"><i class="fas fa-camera me-2"></i> Complete eKYC</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-primary w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#emiPaymentModal"><i class="fas fa-money-bill me-2"></i> Pay EMI</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#" class="btn btn-outline-info w-100 py-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#foreclosureModal"><i class="fas fa-hand-holding-usd me-2"></i> Foreclosure</a>
                </div>
            </div>
            <!-- Modals -->
            <!-- Apply Loan Modal -->
            <div class="modal fade" id="applyLoanModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Apply for Loan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="applyLoanForm">
                                <div class="mb-3">
                                    <label>Loan Amount</label>
                                    <input type="number" name="loanAmount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Tenure (Months)</label>
                                    <input type="number" name="tenure" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Purpose</label>
                                    <input type="text" name="purpose" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- eKYC Modal -->
            <div class="modal fade" id="ekycModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Complete eKYC (Selfie)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <video id="selfieVideo" autoplay style="max-width: 100%; border-radius: 0.5rem;"></video>
                                <canvas id="selfieCanvas" style="display: none;"></canvas>
                                <img id="selfiePreview" style="display: none;">
                            </div>
                            <div class="text-center mb-3">
                                <button id="captureSelfie" class="btn btn-primary">Capture Selfie</button>
                                <button id="retakeSelfie" class="btn btn-outline-secondary" style="display: none;">Retake</button>
                            </div>
                            <form id="ekycForm">
                                <input type="hidden" name="selfieData" id="selfieData">
                                <button type="submit" class="btn btn-primary w-100" id="submitSelfie" style="display: none;">Submit eKYC</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- EMI Payment Modal -->
            <div class="modal fade" id="emiPaymentModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Make EMI Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="emiPaymentForm">
                                <div class="mb-3">
                                    <label>Loan ID</label>
                                    <input type="text" name="loanId" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Amount</label>
                                    <input type="number" name="amount" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Payment Method</label>
                                    <select name="paymentMethod" class="form-control" required>
                                        <option value="UPI">UPI</option>
                                        <option value="Net Banking">Net Banking</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Pay Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Foreclosure Request Modal -->
            <div class="modal fade" id="foreclosureModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Request Loan Foreclosure</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="foreclosureForm">
                                <div class="mb-3">
                                    <label>Loan ID</label>
                                    <input type="text" name="loanId" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Reason</label>
                                    <textarea name="reason" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Info Update Request Modal -->
            <div class="modal fade" id="infoUpdateModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Request Info Update</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="infoUpdateForm">
                                <div class="mb-3">
                                    <label>Field to Update</label>
                                    <select name="field" class="form-control" required>
                                        <option value="Name">Name</option>
                                        <option value="Email">Email</option>
                                        <option value="Phone">Phone</option>
                                        <option value="Address">Address</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>New Value</label>
                                    <input type="text" name="newValue" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Reason</label>
                                    <textarea name="reason" class="form-control" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loans Table -->
            <div class="card mb-4 shadow-sm" id="loansTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Loans</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Next EMI Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LN1001</td>
                                    <td>$10,000</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>2025-07-01</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="btn btn-sm btn-outline-primary pay-emi" data-loan-id="LN1001" data-bs-toggle="modal" data-bs-target="#emiPaymentModal"><i class="fas fa-money-bill"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LN1002</td>
                                    <td>$5,000</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- EMI Calendar Table -->
            <div class="card mb-4 shadow-sm" id="emiTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">EMI Calendar</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>EMI Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LN1001</td>
                                    <td>$500</td>
                                    <td>2025-07-01</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary pay-emi" data-loan-id="LN1001" data-bs-toggle="modal" data-bs-target="#emiPaymentModal"><i class="fas fa-money-bill"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LN1001</td>
                                    <td>$500</td>
                                    <td>2025-08-01</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary pay-emi" data-loan-id="LN1001" data-bs-toggle="modal" data-bs-target="#emiPaymentModal"><i class="fas fa-money-bill"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- eSign Documents Table -->
            <div class="card mb-4 shadow-sm" id="documentsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">eSign Documents</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Document ID</th>
                                    <th>Loan ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#DOC101</td>
                                    <td>#LN1001</td>
                                    <td>Loan Agreement</td>
                                    <td><span class="badge bg-warning text-dark">Pending eSign</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary esign-doc" data-doc-id="DOC101"><i class="fas fa-file-signature"></i> eSign</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#DOC102</td>
                                    <td>#LN1001</td>
                                    <td>Terms</td>
                                    <td><span class="badge bg-success">Signed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Pre-approved Offers Table -->
            <div class="card mb-4 shadow-sm" id="offersTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pre-approved Offers</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Offer ID</th>
                                    <th>Loan Amount</th>
                                    <th>Tenure</th>
                                    <th>Interest Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#OFF101</td>
                                    <td>$15,000</td>
                                    <td>36 months</td>
                                    <td>8%</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary accept-offer" data-offer-id="OFF101"><i class="fas fa-check"></i> Accept</a>
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
                        <li class="list-group-item">Complete eKYC by <strong>2025-06-05</strong> for faster loan processing.</li>
                        <li class="list-group-item">New pre-approved offer available. <a href="#offersTable">View now</a></li>
                        <li class="list-group-item">EMI payment due on <strong>2025-07-01</strong>.</li>
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

    // Apply Loan Form Submission
    document.getElementById('applyLoanForm').addEventListener('submit', async function(e) {
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
                alert('Loan application submitted successfully');
                bootstrap.Modal.getInstance(document.getElementById('applyLoanModal')).hide();
                e.target.reset();
                fetchTableData('/api/loans?userId=customer123', 'loansTable');
            } else {
                alert('Error submitting loan application');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // eKYC Selfie Capture
    const video = document.getElementById('selfieVideo');
    const canvas = document.getElementById('selfieCanvas');
    const preview = document.getElementById('selfiePreview');
    const captureButton = document.getElementById('captureSelfie');
    const retakeButton = document.getElementById('retakeSelfie');
    const submitButton = document.getElementById('submitSelfie');
    const selfieDataInput = document.getElementById('selfieData');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (error) {
            alert('Error accessing camera');
        }
    }

    captureButton.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const imageData = canvas.toDataURL('image/png');
        preview.src = imageData;
        selfieDataInput.value = imageData;
        video.style.display = 'none';
        preview.style.display = 'block';
        captureButton.style.display = 'none';
        retakeButton.style.display = 'inline-block';
        submitButton.style.display = 'block';
    });

    retakeButton.addEventListener('click', function() {
        video.style.display = 'block';
        preview.style.display = 'none';
        captureButton.style.display = 'inline-block';
        retakeButton.style.display = 'none';
        submitButton.style.display = 'none';
        selfieDataInput.value = '';
    });

    document.getElementById('ekycModal').addEventListener('show.bs.modal', startCamera);
    document.getElementById('ekycModal').addEventListener('hidden.bs.modal', function() {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        video.style.display = 'block';
        preview.style.display = 'none';
        captureButton.style.display = 'inline-block';
        retakeButton.style.display = 'none';
        submitButton.style.display = 'none';
        selfieDataInput.value = '';
    });

    // eKYC Form Submission
    document.getElementById('ekycForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const ekycData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/ekyc', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(ekycData)
            });
            if (response.ok) {
                alert('eKYC submitted successfully');
                bootstrap.Modal.getInstance(document.getElementById('ekycModal')).hide();
                video.srcObject.getTracks().forEach(track => track.stop());
            } else {
                alert('Error submitting eKYC');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // EMI Payment Form Submission
    document.getElementById('emiPaymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const paymentData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/emi', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(paymentData)
            });
            if (response.ok) {
                alert('EMI payment successful');
                bootstrap.Modal.getInstance(document.getElementById('emiPaymentModal')).hide();
                e.target.reset();
                fetchTableData('/api/emi?userId=customer123', 'emiTable');
            } else {
                alert('Error processing EMI payment');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // Populate EMI Payment Modal
    document.querySelectorAll('.pay-emi').forEach(button => {
        button.addEventListener('click', function() {
            const loanId = this.getAttribute('data-loan-id');
            const form = document.getElementById('emiPaymentForm');
            form.querySelector('[name="loanId"]').value = loanId;
        });
    });

    // Foreclosure Request Form Submission
    document.getElementById('foreclosureForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const foreclosureData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/foreclosure', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(foreclosureData)
            });
            if (response.ok) {
                alert('Foreclosure request submitted successfully');
                bootstrap.Modal.getInstance(document.getElementById('foreclosureModal')).hide();
                e.target.reset();
            } else {
                alert('Error submitting foreclosure request');
            }
        } catch (error) {
            alert('Network error');
        }
    });

    // eSign Document
    document.querySelectorAll('.esign-doc').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const docId = this.getAttribute('data-doc-id');
            try {
                const response = await fetch(`/api/documents/${docId}/esign`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (response.ok) {
                    alert('Document eSigned successfully');
                    fetchTableData('/api/documents?userId=customer123', 'documentsTable');
                } else {
                    alert('Error eSigning document');
                }
            } catch (error) {
                alert('Network error');
            }
        });
    });

    // Accept Pre-approved Offer
    document.querySelectorAll('.accept-offer').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const offerId = this.getAttribute('data-offer-id');
            try {
                const response = await fetch(`/api/offers/${offerId}/accept`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (response.ok) {
                    alert('Offer accepted successfully');
                    fetchTableData('/api/offers?userId=customer123', 'offersTable');
                    fetchTableData('/api/loans?userId=customer123', 'loansTable');
                } else {
                    alert('Error accepting offer');
                }
            } catch (error) {
                alert('Network error');
            }
        });
    });

    // Info Update Request Form Submission
    document.getElementById('infoUpdateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const updateData = Object.fromEntries(formData);
        try {
            const response = await fetch('/api/info-update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updateData)
            });
            if (response.ok) {
                alert('Info update request submitted successfully');
                bootstrap.Modal.getInstance(document.getElementById('infoUpdateModal')).hide();
                e.target.reset();
            } else {
                alert('Error submitting info update request');
            }
        } catch (error) {
            alert('Network error');
        }
    });

   
</script>
</body>
</html>