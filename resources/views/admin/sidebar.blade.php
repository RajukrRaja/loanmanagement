<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #22c55e;
            --bg-light: #f8fafc;
            --bg-dark: #1e293b;
            --text-dark: #1e293b;
            --text-muted: #6b7280;
            --shadow: 0 6px 24px rgba(0,0,0,0.1);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [data-theme="dark"] {
            --bg-light: #1e293b;
            --bg-dark: #111827;
            --text-dark: #f1f5f9;
            --text-muted: #9ca3af;
        }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            transition: var(--transition);
            overflow-x: hidden;
        }
        .sidebar {
            min-height: 100vh;
            background: var(--bg-dark);
            color: #f1f5f9;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
            transition: var(--transition);
            position: sticky;
            top: 0;
        }
        .sidebar .nav-link {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0.3rem 0.5rem;
            padding: 0.8rem 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
            transform: translateX(4px);
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 3px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .profile-img:hover {
            transform: scale(1.05);
        }
        .sidebar h5 {
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .badge.bg-success {
            background: linear-gradient(90deg, var(--success) 60%, #16a34a 100%) !important;
            box-shadow: var(--shadow-sm);
        }
        .notification-badge {
            position: relative;
        }
        .notification-badge::after {
            content: '5';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }
        .dropdown-container {
            position: relative;
            width: 100%;
        }
        .dropdown-toggle {
            color: #d1d5db;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0.3rem 0.5rem;
            padding: 0.8rem 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: none;
            width: 100%;
            text-align: left;
        }
        .dropdown-toggle:hover, .dropdown-toggle.show {
            color: #fff;
            background: linear-gradient(90deg, var(--primary) 60%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-sm);
            transform: translateX(4px);
        }
        .dropdown-toggle::after {
            margin-left: auto;
            transition: transform 0.3s ease;
            border: none;
            font: normal normal normal 14px/1 FontAwesome;
            content: "\f078";
        }
        .dropdown-toggle.show::after {
            transform: rotate(180deg);
        }
        .dropdown-menu {
            display: none;
            background: linear-gradient(135deg, #1e293b 0%, #2d3748 100%);
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            min-width: calc(100% - 1rem);
            margin: 0.5rem;
            padding: 0.75rem 0;
            animation: slideIn 0.3s ease-out;
            position: static;
            max-height: 60vh;
            overflow-y: auto;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }
        .dropdown-menu::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }
        .dropdown-item {
            color: #e5e7eb;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0.5rem;
        }
        .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
            transform: translateX(4px);
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        .dropdown .dropdown-menu {
            background: var(--bg-dark);
            border: none;
            box-shadow: var(--shadow);
            border-radius: 0.5rem;
            max-height: 50vh;
            overflow-y: auto;
            z-index: 1050;
            position: absolute;
            top: 100%;
            left: 0;
            width: calc(100% - 1rem);
            margin: 0 0.5rem;
        }
        .dropdown .dropdown-menu.show {
            display: block !important;
            visibility: visible !important;
        }
        .dropdown .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }
        .dropdown .dropdown-menu::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }
        .dropdown .dropdown-item {
            color: #e5e7eb;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .dropdown .dropdown-item:hover {
            background: var(--primary);
            color: #fff;
            transform: translateX(4px);
        }
        .dropdown .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: -240px;
                width: 240px;
                z-index: 1040;
                transition: left 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
            #sidebarToggle {
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1050;
            }
            .dropdown-menu {
                min-width: calc(100% - 2rem);
                margin: 0.5rem 1rem;
                max-height: 50vh;
            }
            .dropdown .dropdown-menu {
                min-width: calc(100% - 2rem);
                margin: 0.5rem 1rem;
                max-height: 50vh;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button for Mobile -->
    <button id="sidebarToggle" class="btn btn-primary d-md-none m-3">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar HTML -->
    <nav id="sidebarMenu" class="col-lg-2 col-md-3 d-md-block sidebar py-4">
        <div class="text-center mb-4">
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}" alt="Profile" class="profile-img mb-2">
            <h5>{{ Auth::user()->name ?? 'User' }}</h5>
            <span class="badge bg-success">Online</span>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="" 
                   data-bs-toggle="tooltip" 
                   title="Dashboard Overview">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item dropdown-container">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                   href="#" 
                   id="userDropdown" 
                   role="button" 
                   aria-expanded="false" 
                   data-bs-toggle="tooltip" 
                   title="User Management">
                    <i class="fas fa-users"></i> Master User
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown" id="userDropdownMenu">
                    <!-- User Management items will be dynamically populated here -->
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('permissions.*') ? 'active' : '' }}" 
                   href="#" 
                   id="permissionsDropdown" 
                   role="button" 
                   aria-expanded="false" 
                   data-bs-toggle="tooltip" 
                   title="Manage Permissions">
                    <i class="fas fa-shield-alt"></i> Manage Permissions
                </a>
                <ul class="dropdown-menu" aria-labelledby="permissionsDropdown" id="permissionsDropdownMenu">
                    <!-- Permissions items will be dynamically populated here -->
                </ul>
            </li>
            <li class="nav-item dropdown-container">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('leads.*') ? 'active' : '' }}" 
                   href="#" 
                   id="leadsDropdown" 
                   role="button" 
                   aria-expanded="false" 
                   data-bs-toggle="tooltip" 
                   title="Lead Management">
                    <i class="fas fa-user-plus"></i> Lead Management
                </a>
                <ul class="dropdown-menu" aria-labelledby="leadsDropdown" id="leadsDropdownMenu">
                    <!-- Lead Management items will be dynamically populated here -->
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" 
                   href="" 
                   data-bs-toggle="tooltip" 
                   title="System Settings">
                    <i class="fas fa-cogs"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link notification-badge {{ request()->routeIs('notifications') ? 'active' : '' }}" 
                   href="" 
                   data-bs-toggle="tooltip" 
                   title="View Notifications">
                    <i class="fas fa-bell"></i> Notifications
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" 
                   href="{{ route('logout') }}" 
                   data-bs-toggle="tooltip" 
                   title="Sign Out"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            try {
                // Initialize Tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
                console.log('Tooltips initialized');

                // Initialize Sidebar Toggle for Mobile
                function initSidebarToggle() {
                    const sidebarToggle = document.querySelector('#sidebarToggle');
                    const sidebarMenu = document.querySelector('#sidebarMenu');
                    if (sidebarToggle && sidebarMenu) {
                        sidebarToggle.addEventListener('click', function() {
                            sidebarMenu.classList.toggle('show');
                            console.log('Sidebar toggled');
                        });
                    } else {
                        console.warn('Sidebar toggle or menu not found');
                    }
                }
                initSidebarToggle();

                // Custom Dropdown Toggle for Dropdown Containers
                $('.dropdown-container .dropdown-toggle').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const $dropdown = $(this).next('.dropdown-menu');
                        const isShown = $dropdown.hasClass('show');
                        $('.dropdown-menu').removeClass('show');
                        $('.dropdown-toggle').removeClass('show').attr('aria-expanded', 'false');
                        if (!isShown) {
                            $dropdown.addClass('show');
                            $(this).addClass('show').attr('aria-expanded', 'true');
                        }
                        console.log(`Dropdown ${$(this).attr('id')} toggled, menu ${isShown ? 'hidden' : 'shown'}`);
                    });
                });

                // Initialize Bootstrap Dropdown for Permissions
                function initPermissionsDropdown() {
                    const dropdown = document.querySelector('#permissionsDropdown');
                    if (dropdown) {
                        $(dropdown).on('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const $menu = $('#permissionsDropdownMenu');
                            const isShown = $menu.hasClass('show');
                            $('.dropdown-menu').removeClass('show');
                            $('.dropdown-toggle').attr('aria-expanded', 'false');
                            $menu.toggleClass('show');
                            $(this).attr('aria-expanded', !isShown);
                            console.log(`permissionsDropdown toggled, menu ${isShown ? 'hidden' : 'shown'}`);
                        });
                    } else {
                        console.warn('permissionsDropdown not found');
                    }
                }
                initPermissionsDropdown();

                // Close dropdowns when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.dropdown-container').length && !$(e.target).closest('.dropdown').length) {
                        $('.dropdown-menu').removeClass('show');
                        $('.dropdown-toggle').removeClass('show').attr('aria-expanded', 'false');
                        console.log('Dropdowns closed due to outside click');
                    }
                });

                // Update sidebar dropdowns
                function updateSidebarItems(dropdowns) {
                    console.log('Updating sidebar items:', dropdowns);
                    const $userMenu = $('#userDropdownMenu');
                    const $leadsMenu = $('#leadsDropdownMenu');
                    const $permissionsMenu = $('#permissionsDropdownMenu');
                    $userMenu.empty();
                    $leadsMenu.empty();
                    $permissionsMenu.empty();

                    let hasItems = false;
                    dropdowns.forEach(dropdown => {
                        if (!dropdown.name || !dropdown.items || !Array.isArray(dropdown.items) || dropdown.items.length === 0) {
                            console.warn('Skipping invalid dropdown:', dropdown);
                            return;
                        }

                        hasItems = true;
                        let $targetMenu;
                        if (dropdown.id === 1) { // User Management
                            $targetMenu = $userMenu;
                        } else if (dropdown.id === 2) { // Manage Permissions
                            $targetMenu = $permissionsMenu;
                        } else if (dropdown.id === 3) { // Lead Management
                            $targetMenu = $leadsMenu;
                        } else {
                            console.warn(`Unknown dropdown ID: ${dropdown.id}`);
                            return;
                        }

                        dropdown.items.forEach(item => {
                            if (!item.name || !item.url) {
                                console.warn('Skipping invalid item:', item);
                                return;
                            }
                            const $item = $(`
                                <li>
                                    <a class="dropdown-item text-capitalize" href="${item.url}" data-bs-toggle="tooltip" title="${item.name}">
                                        <i class="fas fa-check-circle"></i> ${item.name}
                                    </a>
                                </li>
                            `);
                            $targetMenu.append($item);
                            console.log(`Added item to dropdown ${dropdown.name}: ${item.name}`);
                        });
                    });

                    [$userMenu, $leadsMenu, $permissionsMenu].forEach($menu => {
                        if ($menu.children().length === 0) {
                            $menu.append('<li><a class="dropdown-item text-muted" href="#">No items assigned</a></li>');
                        }
                    });

                    // Reinitialize tooltips for newly added dropdown items
                    $('[data-bs-toggle="tooltip"]').tooltip();
                    console.log('Sidebar dropdowns populated', { hasItems });
                }

                // Load items from API with retry logic
                function loadItems(retryCount = 0, maxRetries = 2) {
                    console.log('Loading dropdown items, attempt:', retryCount + 1);

                    $.ajax({
                        url: '/api/user/permissions',
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('API response:', JSON.stringify(response, null, 2));
                            if (response.success && Array.isArray(response.dropdowns)) {
                                if (response.dropdowns.length === 0) {
                                    console.warn('No items returned from API');
                                    showNotification(response.message || 'No dropdown items assigned to your role.', 'info');
                                    updateSidebarItems([]);
                                } else {
                                    console.log('Fetched dropdowns from API:', response.dropdowns);
                                    updateSidebarItems(response.dropdowns);
                                }
                            } else {
                                console.error('API returned invalid response:', response);
                                showNotification(response.message || 'Invalid dropdown items data.', 'danger');
                                updateSidebarItems([]);
                            }
                        },
                        error: function(xhr) {
                            console.error('API fetch error:', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                response: xhr.responseText
                            });
                            let errorMessage = 'Error fetching dropdown items. Please try again later.';
                            if (xhr.status === 401) {
                                errorMessage = 'Please log in to view dropdown items.';
                            } else if (xhr.status === 403) {
                                errorMessage = 'You are not authorized to view dropdown items.';
                            } else if (xhr.status === 404) {
                                errorMessage = 'Dropdown items endpoint not found.';
                            } else if (xhr.status === 500) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || errorMessage;
                                } catch (e) {
                                    errorMessage = 'Server error occurred. Please contact support.';
                                }
                            }
                            showNotification(errorMessage, 'danger');

                            if (retryCount < maxRetries) {
                                console.warn('Retrying item fetch in 2s');
                                setTimeout(() => loadItems(retryCount + 1, maxRetries), 2000);
                            } else {
                                console.error('Max retries reached, displaying empty items');
                                updateSidebarItems([]);
                            }
                        }
                    });
                }

                // Show notification
                function showNotification(message, type = 'danger') {
                    console.log('Showing notification:', message, type);
                    $('.alert').each(function() {
                        const alert = bootstrap.Alert.getInstance(this);
                        if (alert) {
                            alert.close();
                        }
                    });
                    const $notification = $(`
                        <div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                    $('body').append($notification);
                    const alertInstance = new bootstrap.Alert($notification[0]);
                    setTimeout(() => {
                        if (bootstrap.Alert.getInstance($notification[0])) {
                            alertInstance.close();
                        }
                    }, 5000);
                }

                // Initial load of items
                loadItems();

                // Listen for permissions update event
                $(document).on('permissionsUpdated', function(e, data) {
                    console.log('Received permissionsUpdated event:', data);
                    if (data && data.role) {
                        console.log(`Permissions updated for role: ${data.role}`);
                        loadItems();
                    } else {
                        console.warn('No role specified in permissionsUpdated event');
                        loadItems();
                    }
                });

            } catch (error) {
                console.error('Error in sidebar initialization:', error);
                showNotification('Failed to initialize sidebar: ' + error.message, 'danger');
            }
        });
    </script>
</body>
</html>