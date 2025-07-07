<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Manager Dashboard (Telecaller Team)</title>
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
                <img src="https://ui-avatars.com/api/?name=Team+Manager" alt="Profile" class="profile-img mb-2">
                <h5>Team Manager</h5>
                <span class="badge bg-success">Online</span>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#leadsTable"><i class="fas fa-user-plus me-2"></i> Team Leads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#followupsTable"><i class="fas fa-phone me-2"></i> Follow-ups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#performanceTable"><i class="fas fa-chart-line me-2"></i> Call Performance</a>
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
                    <h2 class="h3 mb-0 fw-bold" style="letter-spacing:0.01em;">Telecaller Team Dashboard</h2>
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
                                    <i class="fas fa-user-plus fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Assigned Leads</h6>
                                    <h4 class="mb-0 fw-bold">50</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 5 new leads
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-phone fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Daily Calls</h6>
                                    <h4 class="mb-0 fw-bold">120</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 10% today
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Leads Converted</h6>
                                    <h4 class="mb-0 fw-bold">8</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 2 today
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-stats shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-users fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Team Members</h6>
                                    <h4 class="mb-0 fw-bold">10</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fas fa-arrow-up text-success"></i> 1 new member
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4 g-3">
                <div class="col-md-3 col-6">
                    <a href="#leadsTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-user-plus me-2"></i> View Leads</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#followupsTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-phone me-2"></i> View Follow-ups</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#performanceTable" class="btn btn-outline-primary w-100 py-3 shadow-sm"><i class="fas fa-chart-line me-2"></i> View Performance</a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="#leadsTable" class="btn btn-outline-info w-100 py-3 shadow-sm"><i class="fas fa-sync-alt me-2"></i> Update Lead Status</a>
                </div>
            </div>
            <!-- Leads Table -->
            <div class="card mb-4 shadow-sm" id="leadsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assigned Team Leads</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Lead ID</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LD101</td>
                                    <td>Mary Johnson</td>
                                    <td>mary@example.com</td>
                                    <td>John Smith</td>
                                    <td>
                                        <select class="form-select status-update" data-lead-id="LD101">
                                            <option value="Pending" selected>Pending</option>
                                            <option value="Contacted">Contacted</option>
                                            <option value="Converted">Converted</option>
                                            <option value="Lost">Lost</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LD102</td>
                                    <td>James Wilson</td>
                                    <td>james@example.com</td>
                                    <td>Sarah Lee</td>
                                    <td>
                                        <select class="form-select status-update" data-lead-id="LD102">
                                            <option value="Pending">Pending</option>
                                            <option value="Contacted" selected>Contacted</option>
                                            <option value="Converted">Converted</option>
                                            <option value="Lost">Lost</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Follow-ups Table -->
            <div class="card mb-4 shadow-sm" id="followupsTable">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Team Follow-ups</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Lead ID</th>
                                    <th>Name</th>
                                    <th>Assigned To</th>
                                    <th>Last Follow-up</th>
                                    <th>Next Follow-up</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#LD101</td>
                                    <td>Mary Johnson</td>
                                    <td>John Smith</td>
                                    <td>2025-06-01</td>
                                    <td>2025-06-03</td>
                                    <td>Interested in loan</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#LD102</td>
                                    <td>James Wilson</td>
                                    <td>Sarah Lee</td>
                                    <td>2025-06-02</td>
                                    <td>2025-06-04</td>
                                    <td>Requested documents</td>
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
                    <h5 class="mb-0">Daily Call Performance</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Calls Made</th>
                                    <th>Leads Contacted</th>
                                    <th>Leads Converted</th>
                                    <th>Leads Lost</th>
                                    <th>Performance (%)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Smith</td>
                                    <td>25</td>
                                    <td>20</td>
                                    <td>3</td>
                                    <td>2</td>
                                    <td>75</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sarah Lee</td>
                                    <td>30</td>
                                    <td>22</td>
                                    <td>4</td>
                                    <td>1</td>
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
            <!-- Announcements -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Announcements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Team meeting on <strong>2025-06-03</strong> at 2:00 PM.</li>
                        <li class="list-group-item">New lead targets assigned. <a href="#">Read more</a></li>
                        <li class="list-group-item">Training session on <strong>2025-06-05</strong>.</li>
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

    // Update lead status
    document.querySelectorAll('.status-update').forEach(select => {
        select.addEventListener('change', async function() {
            const leadId = this.getAttribute('data-lead-id');
            const newStatus = this.value;
            try {
                const response = await fetch(`/api/leads/${leadId}/status`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                });
                if (response.ok) {
                    alert('Lead status updated successfully');
                } else {
                    alert('Error updating lead status');
                    // Revert select value if update fails
                    this.value = this.dataset.originalValue || 'Pending';
                }
            } catch (error) {
                alert('Network error');
                this.value = this.dataset.originalValue || 'Pending';
            }
        });
        // Store original value for rollback
        select.dataset.originalValue = select.value;
    });

</script>
</body>
</html>