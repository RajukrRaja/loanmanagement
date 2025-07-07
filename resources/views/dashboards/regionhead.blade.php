<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Region Head Dashboard</title>
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
                <img src="https://ui-avatars.com/api/?name=Region+Head" alt="Profile" class="profile-img mb-2">
                <h5>Region Head</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#branchesTable"><i class="fas fa-building me-2"></i> Branches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#employeesTable"><i class="fas fa-users me-2"></i> Employee Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#collectionsTable"><i class="fas fa-hand-holding-usd me-2"></i> Collections Summary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#performanceTable"><i class="fas fa-chart-line me-2"></i> Performance Summary</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reportsTable"><i class="fas fa-file-alt me-2"></i> Reports</a>
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
                    <h2 class="h3 mb-0 fw-bold" style="letter-spacing:0.01em;">Region Dashboard (North Region)</h2>
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
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Assigned Branches</h6>
                                    <h4 class="mb-0 fw-bold">5</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 1 new branch
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Employees</h6>
                                    <h4 class="mb-0 fw-bold">250</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 10 new hires
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
                                    <h4 class="mb-0 fw-bold">$45,000</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 4% this month
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-user-plus fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Active Leads</h6>
                                    <h4 class="mb-0 fw-bold">75</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 20 new leads
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#branchesTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-building me-2"></i>View Branches</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#employeesTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-users me-2"></i>View Employees</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#collectionsTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-hand-holding-usd me-2"></i>View Collections</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#performanceTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-chart-line me-2"></i>View Performance</a>
                </div>
            </div>
            <!-- Branches Table -->
            <div class="card mb-4 shadow-sm" id="branchesTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assigned Branches</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Branch ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Total Employees</th>
                                    <th>Active Loans</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BR001</td>
                                    <td>Downtown</td>
                                    <td>City Center</td>
                                    <td>50</td>
                                    <td>80</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BR002</td>
                                    <td>Uptown</td>
                                    <td>North Avenue</td>
                                    <td>45</td>
                                    <td>65</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Employees Table -->
            <div class="card mb-4 shadow-sm" id="employeesTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Employee Reports</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Branch</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Smith</td>
                                    <td>Downtown</td>
                                    <td>john@example.com</td>
                                    <td>Loan Officer</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sarah Lee</td>
                                    <td>Uptown</td>
                                    <td>sarah@example.com</td>
                                    <td>Clerk</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Collections Table -->
            <div class="card mb-4 shadow-sm" id="collectionsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Collections Summary</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Branch</th>
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
                                    <td>Downtown</td>
                                    <td>#LN1001</td>
                                    <td>Jane Doe</td>
                                    <td>$500</td>
                                    <td>2025-06-15</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Uptown</td>
                                    <td>#LN1002</td>
                                    <td>Mike Brown</td>
                                    <td>$400</td>
                                    <td>2025-06-20</td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Performance Table -->
            <div class="card mb-4 shadow-sm" id="performanceTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Performance Summary</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Active Loans</th>
                                    <th>EMI Collected ($)</th>
                                    <th>Leads Closed</th>
                                    <th>Performance (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Downtown</td>
                                    <td>80</td>
                                    <td>$9,000</td>
                                    <td>15</td>
                                    <td>85</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Uptown</td>
                                    <td>65</td>
                                    <td>$7,500</td>
                                    <td>12</td>
                                    <td>80</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Reports Table -->
            <div class="card mb-4 shadow-sm" id="reportsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Generated Reports</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Branch</th>
                                    <th>Type</th>
                                    <th>Date Range</th>
                                    <th>Generated On</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#RP1001</td>
                                    <td>Downtown</td>
                                    <td>Loan Summary</td>
                                    <td>2025-05-01 to 2025-06-01</td>
                                    <td>2025-06-02</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#RP1002</td>
                                    <td>Uptown</td>
                                    <td>EMI Collection</td>
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
                        <li class="list-group-item">Regional meeting scheduled for <strong>2025-06-05</strong> at 10:00 AM.</li>
                        <li class="list-group-item">New performance targets assigned for all branches. <a href="#">Read more</a></li>
                        <li class="list-group-item">Review meeting with branch managers on <strong>2025-06-12</strong>.</li>
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

    // Fetch data for tables (read-only)
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

</script>
</body>
</html>