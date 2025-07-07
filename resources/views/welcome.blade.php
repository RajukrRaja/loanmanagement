<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Management Software - Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .role-card { min-height: 350px; }
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Loan Management Software</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome to Loan Management Software</h1>
        <p class="lead">Secure, flexible, and role-based access for your loan management needs.</p>
    </div>
</header>

<section class="container my-5">
    <h2 class="mb-4 text-center">Login Roles & Access Matrix</h2>
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-dark text-white">Super Admin</div>
                <div class="card-body">
                    <ul>
                        <li>Full system access & settings</li>
                        <li>Loan disbursement & adjustments</li>
                        <li>Credit bureau exports</li>
                        <li>All document templates & agreements</li>
                        <li>Full accounting</li>
                        <li>User management (Create/Edit/Delete)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-primary text-white">Admin</div>
                <div class="card-body">
                    <ul>
                        <li>Loan management (Create/Edit/Disburse/Close)</li>
                        <li>EMI collection approval</li>
                        <li>Lead assignment & reports</li>
                        <li>Accounting access</li>
                        <li>Employee monitoring</li>
                        <li>KYC & eNACH handling</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-success text-white">Sub Admin</div>
                <div class="card-body">
                    <ul>
                        <li>Branch-wise control</li>
                        <li>Employee creation (branch)</li>
                        <li>EMI collection view</li>
                        <li>Loan approval processing</li>
                        <li>Reports & lead allocation</li>
                        <li>Credit bureau export (allowed)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-info text-white">Branch Login</div>
                <div class="card-body">
                    <ul>
                        <li>Branch employees view</li>
                        <li>Loan data entry & collection tracking</li>
                        <li>Branch-level loan & EMI reports</li>
                        <li>Wallet request & cash approval</li>
                        <li>Branch dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-secondary text-white">Sub Branch Admin</div>
                <div class="card-body">
                    <ul>
                        <li>Assigned branch-wise access</li>
                        <li>Employee handling (sub-branch)</li>
                        <li>Reports & approvals (branch)</li>
                        <li>Lead & collection tracking (sub-branch)</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-warning text-dark">Region Head</div>
                <div class="card-body">
                    <ul>
                        <li>Access to assigned branches</li>
                        <li>Branch & employee reporting</li>
                        <li>Collection & performance summary</li>
                        <li>No data edit access</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-danger text-white">Team Manager (Telecaller Team)</div>
                <div class="card-body">
                    <ul>
                        <li>View all leads assigned to team</li>
                        <li>Track team follow-ups</li>
                        <li>Daily call performance & lead outcome</li>
                        <li>Status update only, no edit</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-light text-dark">Telecaller</div>
                <div class="card-body">
                    <ul>
                        <li>View assigned leads</li>
                        <li>Add follow-up notes</li>
                        <li>EMI reminder & collection call updates</li>
                        <li>KYC approve/reject request to Admin</li>
                        <li>Daily task summary</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-dark text-white">Accountant</div>
                <div class="card-body">
                    <ul>
                        <li>Voucher & ledger entries</li>
                        <li>Cash Book, Bank Book, Trial Balance, P&L</li>
                        <li>EMI payment vs deposit reconciliation</li>
                        <li>No loan data edit access</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-primary text-white">Employee</div>
                <div class="card-body">
                    <ul>
                        <li>Create leads (manual/bulk)</li>
                        <li>Upload documents</li>
                        <li>Add loan approval suggestion</li>
                        <li>Track own leads & status</li>
                        <li>Wallet request for deposit</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card role-card h-100">
                <div class="card-header bg-success text-white">Customer</div>
                <div class="card-body">
                    <ul>
                        <li>Loan apply & eKYC (Selfie only)</li>
                        <li>View loan status & EMI calendar</li>
                        <li>EMI payment & foreclosure request</li>
                        <li>eSign documents & pre-approved offers</li>
                        <li>Request info update</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="text-center py-4 bg-light mt-5">
    <div class="container">
        <small>© {{ date('Y') }} Loan Management Software. All rights reserved.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>