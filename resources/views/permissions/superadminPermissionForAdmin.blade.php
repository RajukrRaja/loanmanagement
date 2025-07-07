<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Permission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --success: #10b981;
            --danger: #ef4444;
            --bg-light: #f9fafb;
            --bg-dark: #1f2937;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --shadow: 0 8px 32px rgba(0,0,0,0.1);
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.06);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [data-theme="dark"] {
            --bg-light: #1f2937;
            --bg-dark: #111827;
            --text-dark: #f3f4f6;
            --text-muted: #9ca3af;
        }
        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-dark);
            background: var(--bg-light);
            overflow-x: hidden;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-dark);
            color: #f3f4f6;
            box-shadow: var(--shadow);
            transition: width 0.3s ease;
            position: sticky;
            top: 0;
            width: 260px;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 8px;
            margin: 0.4rem;
            padding: 0.8rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 50%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
        }
        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
        }
        .profile-img {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .profile-img:hover {
            transform: scale(1.1);
        }
        .sidebar.collapsed .profile-info {
            display: none;
        }
        .sidebar h5 {
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .badge.bg-success {
            background: linear-gradient(90deg, var(--success) 50%, #059669 100%) !important;
            box-shadow: var(--shadow-sm);
        }
        .notification-badge {
            position: relative;
        }
        .notification-badge::after {
            content: '5';
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger);
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
        .dropdown-menu {
            background: var(--bg-dark);
            border: none;
            box-shadow: var(--shadow);
            border-radius: 8px;
            max-height: 60vh;
            overflow-y: auto;
        }
        .dropdown-menu::-webkit-scrollbar {
            width: 8px;
        }
        .dropdown-menu::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        .dropdown-item {
            color: #e5e7eb;
            padding: 0.7rem 1.5rem;
            transition: var(--transition);
        }
        .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
        }
        .custom-checkbox input[type="checkbox"] {
            width: 1.3rem;
            height: 1.3rem;
            border: 2px solid var(--text-muted);
            border-radius: 6px;
            transition: var(--transition);
            cursor: pointer;
        }
        .custom-checkbox input[type="checkbox"]:checked {
            background-color: var(--primary);
            border-color: var(--primary);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-size: 1.1rem;
        }
        .custom-checkbox label {
            cursor: pointer;
        }
        .loading {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .animate-slideIn {
            animation: slideIn 0.4s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -260px;
                width: 260px;
                z-index: 1050;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            main {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebarMenu" class="sidebar py-4">
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Admin" alt="Profile" class="profile-img mb-2">
                <div class="profile-info">
                    <h5>Admin</h5>
                    <span class="badge bg-success">Online</span>
                </div>
            </div>
            <button id="collapseSidebar" class="btn btn-link text-light mx-auto d-block mb-3">
                <i class="fas fa-compress-alt"></i>
            </button>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="admin/dashboard" data-bs-toggle="tooltip" title="Dashboard Overview">
                        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Manage Users">
                        <i class="fas fa-users"></i><span>Manage Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/employee/viewLeadDetails/${lead.lead_id}" data-bs-toggle="tooltip" title="Lead Management">
                        <i class="fas fa-user-plus"></i><span>Lead Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.adminApprovedKycUserView') }}" data-bs-toggle="tooltip" title="Approved KYC Users">
                        <i class="fas fa-chart-line"></i><span>Approved KYC Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.adminApprovedEnachUserView') }}" data-bs-toggle="tooltip" title="Approved eNACH Users">
                        <i class="fas fa-book"></i><span>Approved eNACH Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="Employee Monitoring">
                        <i class="fas fa-user-clock"></i><span>Employee Monitoring</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#todaySection" data-bs-toggle="tooltip" title="KYC & eNACH">
                        <i class="fas fa-id-card"></i><span>KYC & eNACH</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="permissionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" title="Manage Permissions">
                        <i class="fas fa-shield-alt"></i><span>Permissions</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="permissionsDropdown" id="permissionsDropdownMenu"></ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#settingsSection" data-bs-toggle="tooltip" title="System Settings">
                        <i class="fas fa-cogs"></i><span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link notification-badge" href="#" data-bs-toggle="tooltip" title="View Notifications">
                        <i class="fas fa-bell"></i><span>Notifications</span>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="#" data-bs-toggle="tooltip" title="Sign Out">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-4 p-md-5">
            <header class="mb-4 animate-slideIn">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button id="sidebarToggle" class="btn btn-link text-dark d-lg-none">
                            <i class="fas fa-bars fs-4"></i>
                        </button>
                        <div>
                            <h1 class="fs-3 fw-bold">Permission Management</h1>
                            <p class="text-muted">Control admin permissions with precision.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" id="permissionSearch" class="form-control form-control-sm" placeholder="Search permissions..." aria-label="Search permissions">
                        <button id="themeToggle" class="btn btn-link text-dark" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>
                </div>
            </header>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-slideIn" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-slideIn" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <?php
                $resourceGroups = [];
                foreach ($permissions as $permission) {
                    $resource = $resources->firstWhere('id', $permission->module_id);
                    $resourceName = $resource->name ?? 'Unmapped Resource (ID: ' . ($permission->module_id ?? 'N/A') . ')';
                    $resourceDescription = $resource->description ?? "Manage {$resourceName} related actions";
                    if ($resourceName === 'Unmapped Resource (ID: ' . ($permission->module_id ?? 'N/A') . ')') {
                        \Log::warning("No resource found for permission ID {$permission->id} with module_id {$permission->module_id}. Resources available: " . json_encode($resources->pluck('id')->toArray()));
                    }
                    $action = $permission->action ?? (
                        isset($permission->name) && str_contains($permission->name, '.')
                            ? explode('.', $permission->name)[1]
                            : 'Unknown'
                    );
                    $action = ucfirst(str_replace('_', ' ', $action));
                    if (!isset($resourceGroups[$resourceName])) {
                        $resourceGroups[$resourceName] = [
                            'permissions' => [],
                            'description' => $resourceDescription
                        ];
                    }
                    $resourceGroups[$resourceName]['permissions'][] = (object)[
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'action' => $action,
                        'description' => $permission->description,
                        'assigned' => in_array($permission->id, $rolePermissions ?? [])
                    ];
                }
            ?>

            <div class="row g-4">
                <!-- Left Section: Assign Permissions -->
                <div class="col-lg-6">
                    <div class="card animate-slideIn">
                        <div class="card-body">
                            <h2 class="card-title fs-5 fw-semibold mb-4">Assign Permissions</h2>
                            <form id="permissions-form" action="{{ route('Permission.updateAdminPermissions', ['role' => 'admin']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="w-25">Resource</th>
                                                <th class="w-50">Actions</th>
                                                <th class="w-25 text-center">Select All</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (empty($resourceGroups))
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No permissions available</td>
                                                </tr>
                                            @else
                                                @foreach ($resourceGroups as $resource => $group)
                                                    <tr>
                                                        <td class="align-middle text-capitalize" data-resource="{{ $resource }}">{{ $resource }}</td>
                                                        <td class="align-middle">
                                                            <div class="d-flex flex-wrap gap-3">
                                                                @foreach ($group['permissions'] as $permission)
                                                                    <div class="custom-checkbox" data-bs-toggle="tooltip" title="{{ $permission->description ?? $permission->action }}">
                                                                        <input
                                                                            type="checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            class="sub-action-checkbox"
                                                                            data-resource="{{ $resource }}"
                                                                            {{ $permission->assigned ? 'checked' : '' }}
                                                                            id="perm-{{ $permission->id }}"
                                                                            aria-label="Toggle {{ $permission->action }} for {{ $resource }}"
                                                                        >
                                                                        <label for="perm-{{ $permission->id }}" class="ms-2 text-capitalize">{{ $permission->action }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <input
                                                                type="checkbox"
                                                                class="select-all-checkbox"
                                                                data-resource="{{ $resource }}"
                                                                {{ count(array_filter(array_column($group['permissions'], 'assigned'))) === count($group['permissions']) ? 'checked' : '' }}
                                                                aria-label="Select all actions for {{ $resource }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" id="reset-permissions" class="btn btn-outline-danger">
                                        <i class="fas fa-undo me-2"></i> Reset
                                    </button>
                                    <button type="submit" id="save-permissions" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Permissions
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Section: View Assigned Permissions -->
                <div class="col-lg-6">
                    <div class="card animate-slideIn">
                        <div class="card-body">
                            <h2 class="card-title fs-5 fw-semibold mb-4">Assigned Permissions</h2>
                            <div class="table-responsive">
                                <table id="permissions-table" class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="w-33">Resource</th>
                                            <th class="w-33">Description</th>
                                            <th class="w-33">Assigned Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (empty($resourceGroups))
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No permissions assigned</td>
                                            </tr>
                                        @else
                                            @foreach ($resourceGroups as $resource => $group)
                                                <tr>
                                                    <td class="align-middle text-capitalize" data-resource="{{ $resource }}">{{ $resource }}</td>
                                                    <td class="align-middle">{{ $group['description'] }}</td>
                                                    <td class="align-middle">
                                                        @if (empty(array_filter(array_column($group['permissions'], 'assigned'))))
                                                            <span class="text-danger" aria-label="{{ $resource }} has no actions assigned">None</span>
                                                        @else
                                                            <div class="d-flex flex-wrap gap-3">
                                                                @foreach ($group['permissions'] as $permission)
                                                                    <span
                                                                        class="{{ $permission->assigned ? 'text-success' : 'text-danger' }}"
                                                                        aria-label="{{ $permission->action }} is {{ $permission->assigned ? 'enabled' : 'disabled' }}"
                                                                    >
                                                                        {{ $permission->action }} {{ $permission->assigned ? '✓' : '✗' }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const TEMP_STORAGE_KEY = 'admin_temp_permissions';

            // Initialize Tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

            // Get valid permission IDs
            const validPermissionIds = $('input.sub-action-checkbox').map(function() {
                return parseInt($(this).val());
            }).get();

            // Load permissions
            function loadPermissions() {
                const tempPermissions = localStorage.getItem(TEMP_STORAGE_KEY);
                let permissions = [];
                if (tempPermissions) {
                    try {
                        const parsed = JSON.parse(tempPermissions);
                        if (Array.isArray(parsed)) {
                            permissions = parsed.filter(id => validPermissionIds.includes(id));
                        }
                    } catch (e) {
                        console.warn('Invalid localStorage data:', e);
                    }
                }
                if (permissions.length > 0) {
                    $('input.sub-action-checkbox').each(function() {
                        const permissionId = parseInt($(this).val());
                        $(this).prop('checked', permissions.includes(permissionId));
                    });
                } else {
                    permissions = getSelectedPermissions();
                }
                updateSelectAllCheckboxes();
                updatePermissionsTable(permissions);
                updateSidebarPermissions(permissions);
            }

            // Get selected permissions
            function getSelectedPermissions() {
                return $('input.sub-action-checkbox:checked').map(function() {
                    return parseInt($(this).val());
                }).get();
            }

            // Update "Select All" checkboxes
            function updateSelectAllCheckboxes() {
                $('.select-all-checkbox').each(function() {
                    const resource = $(this).data('resource');
                    const allChecked = $(`input.sub-action-checkbox[data-resource="${resource}"]`).length ===
                                      $(`input.sub-action-checkbox[data-resource="${resource}"]:checked`).length;
                    $(this).prop('checked', allChecked);
                });
            }

            // Update permissions table
            function updatePermissionsTable(permissions) {
                $('#permissions-table tbody tr').each(function() {
                    const resource = $(this).find('td:first').attr('data-resource');
                    const statusCell = $(this).find('td:last');
                    const assignedActions = [];
                    $(`input.sub-action-checkbox[data-resource="${resource}"]`).each(function() {
                        const permissionId = parseInt($(this).val());
                        const action = $(this).next('label').text();
                        const isAssigned = permissions.includes(permissionId);
                        assignedActions.push(
                            `<span class="${isAssigned ? 'text-success' : 'text-danger'}" aria-label="${action} is ${isAssigned ? 'enabled' : 'disabled'}">${action} ${isAssigned ? '✓' : '✗'}</span>`
                        );
                    });
                    if (assignedActions.length > 0) {
                        statusCell.html(`<div class="d-flex flex-wrap gap-3">${assignedActions.join('')}</div>`);
                    } else {
                        statusCell.html(`<span class="text-danger" aria-label="No actions enabled for this resource">None</span>`);
                    }
                });
            }

            // Update sidebar permissions dropdown
            function updateSidebarPermissions(permissions) {
                const $dropdownMenu = $('#permissionsDropdownMenu');
                $dropdownMenu.empty();
                const resources = {};
                $('input.sub-action-checkbox').each(function() {
                    const resource = $(this).data('resource');
                    const permissionId = parseInt($(this).val());
                    const action = $(this).next('label').text();
                    if (!resources[resource]) {
                        resources[resource] = [];
                    }
                    if (permissions.includes(permissionId)) {
                        resources[resource].push(action);
                    }
                });
                for (const [resource, actions] of Object.entries(resources)) {
                    if (actions.length > 0) {
                        const $resourceItem = $(`
                            <li>
                                <a class="dropdown-item text-capitalize" href="#">
                                    <i class="fas fa-check-circle me-2"></i> ${resource}: ${actions.join(', ')}
                                </a>
                            </li>
                        `);
                        $dropdownMenu.append($resourceItem);
                    }
                }
                if ($dropdownMenu.children().length === 0) {
                    $dropdownMenu.append('<li><a class="dropdown-item text-muted" href="#">No permissions assigned</a></li>');
                }
            }

            // Show notification
            function showNotification(message, type = 'success') {
                const $notification = $(`
                    <div class="alert alert-${type} alert-dismissible fade show animate-slideIn position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1060;">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('body').append($notification);
                setTimeout(() => $notification.alert('close'), 4000);
            }

            // Store initial permissions
            $('input.sub-action-checkbox').each(function() {
                $(this).data('initial-assigned', $(this).prop('checked') ? '1' : '0');
            });

            // Load permissions
            loadPermissions();

            // Handle "Select All" checkbox
            $('.select-all-checkbox').on('change', function() {
                const resource = $(this).data('resource');
                const isChecked = $(this).is(':checked');
                $(`input.sub-action-checkbox[data-resource="${resource}"]`).prop('checked', isChecked);
                updateSelectAllCheckboxes();
                updatePermissionsTable(getSelectedPermissions());
            });

            // Update UI on checkbox change
            $('.sub-action-checkbox').on('change', function() {
                updateSelectAllCheckboxes();
                updatePermissionsTable(getSelectedPermissions());
            });

            // Handle form submission
            $('#permissions-form').on('submit', function(e) {
                e.preventDefault();
                const $button = $('#save-permissions');
                const originalText = $button.html();
                $button.addClass('loading').html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);

                const permissions = getSelectedPermissions();
                if (permissions.length === 0) {
                    showNotification('No permissions selected.', 'danger');
                    $button.removeClass('loading').html(originalText).prop('disabled', false);
                    return;
                }

                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            const serverPermissions = response.permissions && Array.isArray(response.permissions)
                                ? response.permissions.filter(id => validPermissionIds.includes(id))
                                : permissions;
                            $('input.sub-action-checkbox').each(function() {
                                const permissionId = parseInt($(this).val());
                                $(this).prop('checked', serverPermissions.includes(permissionId));
                            });
                            localStorage.setItem(TEMP_STORAGE_KEY, JSON.stringify(serverPermissions));
                            updateSelectAllCheckboxes();
                            updatePermissionsTable(serverPermissions);
                            updateSidebarPermissions(serverPermissions);
                            showNotification(response.message || 'Permissions saved successfully!');
                            $('input.sub-action-checkbox').each(function() {
                                const permissionId = parseInt($(this).val());
                                $(this).data('initial-assigned', serverPermissions.includes(permissionId) ? '1' : '0');
                            });
                        } else {
                            showNotification(response.message || 'Failed to save permissions.', 'danger');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Failed to save permissions. Please try again.';
                        showNotification(errorMessage, 'danger');
                    },
                    complete: function() {
                        $button.removeClass('loading').html(originalText).prop('disabled', false);
                    }
                });
            });

            // Reset permissions
            $('#reset-permissions').on('click', function() {
                $('input.sub-action-checkbox').each(function() {
                    $(this).prop('checked', $(this).data('initial-assigned') === '1');
                });
                updateSelectAllCheckboxes();
                updatePermissionsTable(getSelectedPermissions());
                showNotification('Permissions reset to initial state.', 'info');
            });

            // Sidebar Toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebarMenu').toggleClass('show');
            });

            // Collapse Sidebar
            $('#collapseSidebar').on('click', function() {
                $('#sidebarMenu').toggleClass('collapsed');
                $(this).html(`<i class="fas fa-${$('#sidebarMenu').hasClass('collapsed') ? 'expand-alt' : 'compress-alt'}"></i>`);
            });

            // Theme Toggle
            $('#themeToggle').on('click', function() {
                const isDark = $('body').attr('data-theme') === 'dark';
                $('body').attr('data-theme', isDark ? 'light' : 'dark');
                $(this).html(`<i class="fas fa-${isDark ? 'moon' : 'sun'}"></i>`);
            });

            // Search Permissions
            $('#permissionSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#permissions-form tr').each(function() {
                    const resource = $(this).find('td:first').text().toLowerCase();
                    const actions = $(this).find('.sub-action-checkbox').next('label').map(function() {
                        return $(this).text().toLowerCase();
                    }).get().join(' ');
                    $(this).toggle(resource.includes(searchTerm) || actions.includes(searchTerm));
                });
                $('#permissions-table tr').each(function() {
                    const resource = $(this).find('td:first').text().toLowerCase();
                    const actions = $(this).find('td:last').text().toLowerCase();
                    $(this).toggle(resource.includes(searchTerm) || actions.includes(searchTerm));
                });
            });

            // Keyboard Accessibility
            $('.nav-link, .custom-checkbox input, .btn').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    $(this).trigger('click');
                }
            });
        });
    </script>
</body>
</html>