<nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block sidebar py-4" aria-label="Sidebar Navigation">
    <div class="text-center mb-4">
        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Super Admin Profile" class="sidebar-profile-img mb-2">
        <h5 class="mb-1">Super Admin</h5>
        <span class="sidebar-badge-success">Online</span>
    </div>
    <ul class="sidebar-nav-list">
        <li class="sidebar-nav-item">
            <a class="sidebar-nav-link active" href="{{ route('superadmin.dashboard') }}" data-toggle="tooltip" title="Dashboard Overview" aria-current="page">
                <i class="fas fa-tachometer-alt" aria-hidden="true"></i> Dashboard
            </a>
        </li>
        <li class="sidebar-dropdown-container">
            <button class="sidebar-nav-link sidebar-dropdown-toggle" id="masterUserDropdown" aria-expanded="false" aria-controls="masterUserMenu">
                <i class="fas fa-users" aria-hidden="true"></i> Master User
            </button>
            <ul class="sidebar-dropdown-menu" id="masterUserMenu" aria-labelledby="masterUserDropdown">
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.manage_user') }}" data-toggle="tooltip" title="Manage All Users">
                        <i class="fas fa-user-shield" aria-hidden="true"></i> All User
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.adminUserView') }}" data-toggle="tooltip" title="Manage Admin">
                        <i class="fas fa-user-cog" aria-hidden="true"></i> Admin
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.SubadminUserView') }}" data-toggle="tooltip" title="Manage Sub Admin">
                        <i class="fas fa-user-cog" aria-hidden="true"></i> Sub Admin
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.branchadminUserView') }}" data-toggle="tooltip" title="Manage Branch">
                        <i class="fas fa-building" aria-hidden="true"></i> Branch
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.subbranchadminUserView') }}" data-toggle="tooltip" title="Manage Sub Branch">
                        <i class="fas fa-building" aria-hidden="true"></i> Sub Branch
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.teammanagerAdminUserView') }}" data-toggle="tooltip" title="Manage Team Manager">
                        <i class="fas fa-user-tie" aria-hidden="true"></i> Team Manager (Telecaller Team)
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.telecallerAdminUserView') }}" data-toggle="tooltip" title="Manage Telecaller">
                        <i class="fas fa-headset" aria-hidden="true"></i> Telecaller
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.AccountantAdminUserView') }}" data-toggle="tooltip" title="Manage Accountant">
                        <i class="fas fa-calculator" aria-hidden="true"></i> Accountant
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.EmployeeAdminUserView') }}" data-toggle="tooltip" title="Manage Employee">
                        <i class="fas fa-user" aria-hidden="true"></i> Employee
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.CustomerAdminUserView') }}" data-toggle="tooltip" title="Manage Customer">
                        <i class="fas fa-user-friends" aria-hidden="true"></i> Customer
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-dropdown-container">
            <button class="sidebar-nav-link sidebar-dropdown-toggle" id="permissionsDropdown" aria-expanded="false" aria-controls="permissionsMenu">
                <i class="fas fa-users" aria-hidden="true"></i> Manage Permissions
            </button>
            <ul class="sidebar-dropdown-menu" id="permissionsMenu" aria-labelledby="permissionsDropdown">
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('superadmin.manage_user') }}" data-toggle="tooltip" title="Manage All Users">
                        <i class="fas fa-user-shield" aria-hidden="true"></i> All User
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForAdmin') }}" data-toggle="tooltip" title="Manage Admin">
                        <i class="fas fa-user-cog" aria-hidden="true"></i> Admin
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForSubAdmin') }}" data-toggle="tooltip" title="Manage Sub Admin">
                        <i class="fas fa-user-cog" aria-hidden="true"></i> Sub Admin
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForBranch') }}" data-toggle="tooltip" title="Manage Branch">
                        <i class="fas fa-building" aria-hidden="true"></i> Branch
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForSubBranch') }}" data-toggle="tooltip" title="Manage Sub Branch">
                        <i class="fas fa-building" aria-hidden="true"></i> Sub Branch
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForResion') }}" data-toggle="tooltip" title="Manage Region Head">
                        <i class="fas fa-building" aria-hidden="true"></i> Region Head
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForTeamManager') }}" data-toggle="tooltip" title="Manage Team Manager">
                        <i class="fas fa-user-tie" aria-hidden="true"></i> Team Manager (Telecaller Team)
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForTelecaller') }}" data-toggle="tooltip" title="Manage Telecaller">
                        <i class="fas fa-headset" aria-hidden="true"></i> Telecaller
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForCreditManager') }}" data-toggle="tooltip" title="Manage Credit Manager">
                        <i class="fas fa-building" aria-hidden="true"></i> Credit Manager
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForDisbursementManager') }}" data-toggle="tooltip" title="Manage Disbursement Manager">
                        <i class="fas fa-building" aria-hidden="true"></i> Disbursement Manager
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForAccountant') }}" data-toggle="tooltip" title="Manage Accountant">
                        <i class="fas fa-calculator" aria-hidden="true"></i> Accountant
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForEmployee') }}" data-toggle="tooltip" title="Manage Employee">
                        <i class="fas fa-user" aria-hidden="true"></i> Employee
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Permission.superadminPermissionForCustomer') }}" data-toggle="tooltip" title="Manage Customer">
                        <i class="fas fa-user-friends" aria-hidden="true"></i> Customer
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-dropdown-container">
            <button class="sidebar-nav-link sidebar-dropdown-toggle" id="leadsDropdown" aria-expanded="false" aria-controls="leadsMenu">
                <i class="fas fa-money-check-alt" aria-hidden="true"></i> Lead Management
            </button>
            <ul class="sidebar-dropdown-menu" id="leadsMenu" aria-labelledby="leadsDropdown">
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('employee.ViewCreateLead') }}" data-toggle="tooltip" title="View All Leads">
                        <i class="fas fa-list" aria-hidden="true"></i> All Leads
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('employee.employeeCreateLead') }}" data-toggle="tooltip" title="Create Lead">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Create Lead
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('admin.adminApprovedKycUserView') }}" data-toggle="tooltip" title="KYC Approved Users">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Approved Leads
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('admin.pendingLead') }}" data-toggle="tooltip" title="View Pending Leads">
                        <i class="fas fa-clock" aria-hidden="true"></i> Pending Leads
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('admin.rejectedLead') }}" data-toggle="tooltip" title="View Rejected Leads">
                        <i class="fas fa-times-circle" aria-hidden="true"></i> Rejected Leads
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('admin.adminApprovedEnachUserView') }}" data-toggle="tooltip" title="Enach Leads">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Enach Leads
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-dropdown-container">
            <button class="sidebar-nav-link sidebar-dropdown-toggle" id="reportsDropdown" aria-expanded="false" aria-controls="reportsMenu">
                <i class="fas fa-chart-bar" aria-hidden="true"></i> All Reports
            </button>
            <ul class="sidebar-dropdown-menu" id="reportsMenu" aria-labelledby="reportsDropdown">
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('disbandment') }}" data-toggle="tooltip" title="View Disbursement Report">
                        <i class="fas fa-list" aria-hidden="true"></i> Disbursement Report
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('ongoing') }}" data-toggle="tooltip" title="View Ongoing Loans">
                        <i class="fas fa-hourglass-half" aria-hidden="true"></i> Ongoing Loan
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('paid_emis') }}" data-toggle="tooltip" title="View Paid EMIs">
                        <i class="fas fa-hourglass-half" aria-hidden="true"></i> All Paid EMI
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('closed') }}" data-toggle="tooltip" title="View Closed Loans">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Closed Loan
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('overdue_emis') }}" data-toggle="tooltip" title="View Overdue EMIs">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i> Overdue EMI
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('overdue_emis_npa') }}" data-toggle="tooltip" title="View Overdue EMI NPA">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i> Overdue EMI NPA
                    </a>
                </li>
                <li>
                    <a class="sidebar-dropdown-item" href="{{ route('outstanding_emi') }}" data-toggle="tooltip" title="View Outstanding Balance">
                        <i class="fas fa-balance-scale" aria-hidden="true"></i> Outstanding Balance
                    </a>
                </li>
                
                
                  <li>
                    <a class="sidebar-dropdown-item" href="{{ route('HalfPayment_emi') }}" data-toggle="tooltip" title="View Outstanding Balance">
                        <i class="fas fa-balance-scale" aria-hidden="true"></i> Half Payment Reports
                    </a>
                </li>
                
                  <li>
                    <a class="sidebar-dropdown-item" href="{{ route('Forclosure_emi') }}" data-toggle="tooltip" title="View Outstanding Balance">
                        <i class="fas fa-balance-scale" aria-hidden="true"></i> For Clousure Reports
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-nav-item">
            <a class="sidebar-nav-link" href="#accountingSection" data-toggle="tooltip" title="Accounting Overview">
                <i class="fas fa-book" aria-hidden="true"></i> Accounting
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a class="sidebar-nav-link" href="#templatesSection" data-toggle="tooltip" title="Manage Document Templates">
                <i class="fas fa-file-alt" aria-hidden="true"></i> Document Templates
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a class="sidebar-nav-link" href="#settingsSection" data-toggle="tooltip" title="System Settings">
                <i class="fas fa-cogs" aria-hidden="true"></i> System Settings
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a class="sidebar-nav-link sidebar-notification-badge" href="#notificationsSection" data-toggle="tooltip" title=".idx
                <i class="fas fa-bell" aria-hidden="true"></i> Notifications <span class="badge badge-pill badge-danger">3</span>
            </a>
        </li>
        <li class="sidebar-nav-item mt-4">
            <a class="sidebar-nav-link text-danger" href="#logout" data-toggle="tooltip" title="Sign Out">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
 --sidebar-bg: #390c52;
    --sidebar-text: #f1f5f9;
    --sidebar-muted: #d1d5db;
    --sidebar-hover: #ab4b68; /* Slightly lighter shade for hover */
 
    --sidebar-success: #10b981;
    --sidebar-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --sidebar-transition: all 0.3s ease;
    --sidebar-dropdown-bg: #ab4b68; /* Defined but unused, kept for potential future use */
}

.sidebar {
    min-height: 100vh;
    background-color: var(--sidebar-bg);
    color: var(--sidebar-text);
    box-shadow: 4px 0 20px rgba(149, 53, 83, 0.4);
    transition: var(--sidebar-transition);
    position: sticky;
    top: 0;
    font-family: 'Inter', sans-serif;
    z-index: 1030;
}

.sidebar-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav-item {
    margin: 0.2rem 0;
}

.sidebar-nav-link {
    color: var(--sidebar-text);
    font-weight: 500;
    border-radius: 0.5rem;
    margin: 0.3rem 0.5rem;
    padding: 0.8rem 1rem;
    transition: var(--sidebar-transition);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
}

.sidebar-nav-link:hover,
.sidebar-nav-link.active {
   
    color: var(--sidebar-text);
    transform: translateX(4px);
}

.sidebar-nav-link i {
    width: 20px;
    text-align: center;
}

.sidebar-dropdown-container {
    position: relative;
}

.sidebar-dropdown-toggle {
    color: var(--sidebar-text);
    font-weight: 500;
    border-radius: 0.5rem;
    margin: 0.3rem 0.5rem;
    padding: 0.8rem 1rem;
    transition: var(--sidebar-transition);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: none;
    width: 100%;
    text-align: left;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.sidebar-dropdown-toggle:hover,
.sidebar-dropdown-toggle.active {
    background-color: var(--sidebar-hover);
    transform: translateX(4px);
}

.sidebar-dropdown-toggle::after {
    margin-left: auto;
    transition: transform 0.3s ease;
    font: normal normal normal 14px/1 FontAwesome;
    content: "\f078";
}

.sidebar-dropdown-toggle.active::after {
    transform: rotate(180deg);
}

.sidebar-dropdown-menu {
    display: none;
    background: #503678; /* Match sidebar background */
    border: none;
    border-radius: 0; /* Remove card-like rounded corners */
    min-width: 100%;
    margin: 0; /* Remove margins to align with sidebar */
    padding: 0.5rem 0; /* Minimal padding for spacing */
    animation: sidebar-slideIn 0.3s ease-out;
    position: static;
    max-height: 60vh;
    overflow-y: auto;
    list-style: none;
    scroll-behavior: smooth;
}

.sidebar-dropdown-menu.active {
    display: block;
}

.sidebar-dropdown-menu::-webkit-scrollbar {
    width: 6px;
}

.sidebar-dropdown-menu::-webkit-scrollbar-thumb {
    background: var(--sidebar-muted);
    border-radius: 3px;
}

.sidebar-dropdown-item {
    color: var(--sidebar-text);
    padding: 0.6rem 1.5rem 0.6rem 2rem; /* Increased left padding for indentation */
    font-weight: 500;
    font-size: 0.9rem;
    transition: var(--sidebar-transition);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-radius: 0.25rem;
    margin: 0.2rem 0.5rem;
    text-decoration: none;
}

.sidebar-dropdown-item:hover {
    background: var(--sidebar-active);
    color: var(--sidebar-text);
    transform: translateX(4px);
}

.sidebar-dropdown-item i {
    width: 20px;
    text-align: center;
}

.sidebar-profile-img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    border: 3px solid #ab4b68;
    box-shadow: var(--sidebar-shadow-sm);
    transition: var(--sidebar-transition);
}

.sidebar-profile-img:hover {
    transform: scale(1.05);
}

.sidebar h5 {
    font-weight: 600;
    letter-spacing: 0.02em;
    color: var(--sidebar-text);
}

.sidebar-badge-success {
    background: var(--sidebar-success);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    color: #fff;
    font-size: 0.85rem;
}

.sidebar-notification-badge .badge {
    margin-left: 0.5rem;
    font-size: 0.75rem;
}

.sidebar-tooltip {
    position: absolute;
    background: #1e293b;
    color: #fff;
    padding: 0.5rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.85rem;
    z-index: 1000;
    display: none;
    pointer-events: none;
    white-space: nowrap;
}

[data-toggle="tooltip"]:hover .sidebar-tooltip {
    display: block;
}

@keyframes sidebar-slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 991.98px) {
    .sidebar {
        position: fixed;
        left: -260px;
        width: 260px;
        z-index: 1040;
        transition: left 0.3s ease;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1039;
        display: none;
    }

    .sidebar.active ~ .sidebar-overlay {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Sidebar initialized');

    // Dropdown toggle functionality
    const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const dropdownMenu = toggle.nextElementSibling;
            const isActive = toggle.classList.contains('active');

            // Close all other dropdowns
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.classList.remove('active');
                    otherToggle.setAttribute('aria-expanded', 'false');
                    const otherMenu = otherToggle.nextElementSibling;
                    if (otherMenu) otherMenu.classList.remove('active');
                }
            });

            // Toggle current dropdown
            toggle.classList.toggle('active', !isActive);
            toggle.setAttribute('aria-expanded', !isActive);
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('active', !isActive);
            }
        });
    });

    // Tooltip functionality
    const tooltipElements = document.querySelectorAll('[data-toggle="tooltip"]');
    tooltipElements.forEach(element => {
        const title = element.getAttribute('title');
        if (title) {
            const tooltip = document.createElement('div');
            tooltip.className = 'sidebar-tooltip';
            tooltip.textContent = title;
            element.style.position = 'relative';
            element.appendChild(tooltip);

            element.addEventListener('mouseenter', () => {
                const rect = element.getBoundingClientRect();
                tooltip.style.left = `${rect.width + 10}px`;
                tooltip.style.top = `${rect.height / 2 - tooltip.offsetHeight / 2}px`;
                tooltip.style.display = 'block';
            });

            element.addEventListener('mouseleave', () => {
                tooltip.style.display = 'none';
            });
        }
    });

    // Mobile sidebar toggle
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('#sidebarToggle');
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active', sidebar.classList.contains('active'));
        });
    }

    // Close sidebar on overlay click
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Close sidebar on outside click (mobile)
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !toggleButton.contains(e.target) && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });

    // Keyboard navigation
    const navItems = document.querySelectorAll('.sidebar-nav-link, .sidebar-dropdown-toggle, .sidebar-dropdown-item');
    navItems.forEach((item, index) => {
        item.setAttribute('tabindex', '0');
        item.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                item.click();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = (index + 1) % navItems.length;
                navItems[nextIndex].focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = (index - 1 + navItems.length) % navItems.length;
                navItems[prevIndex].focus();
            }
        });
    });

    // Close dropdowns on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdownToggles.forEach(toggle => {
                toggle.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                const menu = toggle.nextElementSibling;
                if (menu) menu.classList.remove('active');
            });
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
});
</script>