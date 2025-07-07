<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sub Admin Permission Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        /* Custom checkbox styling */
        input[type="checkbox"] {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #4B5563;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        input[type="checkbox"]:checked {
            background-color: #2563EB;
            border-color: #2563EB;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3E%3Cpath fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-size: 1rem;
        }
        input[type="checkbox"]:focus {
            outline: none;
            ring: 2px solid #2563EB;
        }
        /* Hover effects for table rows */
        tbody tr:hover {
            background-color: #F9FAFB;
            transition: background-color 0.2s ease-in-out;
        }
        /* Loading state for button */
        .loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8 max-w-7xl">
        <header class="mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Sub Admin Permission Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage permissions for the subadmin role with ease.</p>
        </header>

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 animate-fadeIn" role="alert">
                {{ session('error') }}
            </div>
        @elseif (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 animate-fadeIn" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <?php
            // Fetch permissions from database
            $permissions = DB::table('permissions')->select('permission_id', 'name', 'display_name')->get();
            // Fetch role permissions for subadmin
            $rolePermissions = DB::table('role_permissions')
                ->where('role', 'subadmin')
                ->pluck('permission_id')
                ->toArray();

            // Group permissions by resource
            $resourceGroups = [];
            foreach ($permissions as $permission) {
                // Extract resource name (e.g., 'create_lead' -> 'Lead')
                $resource = ucfirst(str_replace(['create_', 'view_', 'edit_', 'delete_', 'bulk_upload_', 'disburse_', 'generate_'], '', $permission->name));
                $action = explode('_', $permission->name)[0];
                if (in_array($permission->name, ['bulk_upload_lead', 'disburse_loan', 'generate_report'])) {
                    $action = str_replace('_', ' ', $permission->name);
                }
                $action = ucfirst(str_replace('_', ' ', $action));

                if (!isset($resourceGroups[$resource])) {
                    $resourceGroups[$resource] = [
                        'permissions' => [],
                        'description' => 'Manage ' . strtolower($resource) . ' related actions'
                    ];
                }
                $resourceGroups[$resource]['permissions'][] = (object)[
                    'permission_id' => $permission->permission_id,
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'action' => $action,
                    'assigned' => in_array($permission->permission_id, $rolePermissions)
                ];
            }
        ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Section: Assign Permissions -->
            <div class="bg-white rounded-xl shadow-sm p-6 ring-1 ring-gray-200 animate-fadeIn">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Assign Permissions for Subadmin</h2>
                <form id="permissions-form" class="space-y-4" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">Resource</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/2">Actions</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/4">Select All</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if (empty($resourceGroups))
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No permissions available</td>
                                    </tr>
                                @else
                                    @foreach ($resourceGroups as $resource => $group)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700 capitalize" data-resource="{{ $resource }}">{{ $resource }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                <div class="flex flex-wrap gap-4">
                                                    @foreach ($group['permissions'] as $permission)
                                                        <label class="flex items-center space-x-2">
                                                            <input
                                                                type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permission->permission_id }}"
                                                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 sub-action-checkbox"
                                                                data-resource="{{ $resource }}"
                                                                {{ $permission->assigned ? 'checked' : '' }}
                                                                aria-label="Toggle {{ $permission->action }} for {{ $resource }} for subadmin"
                                                            >
                                                            <span class="text-sm capitalize">{{ $permission->action }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input
                                                    type="checkbox"
                                                    class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 select-all-checkbox"
                                                    data-resource="{{ $resource }}"
                                                    {{ count(array_filter(array_column($group['permissions'], 'assigned'))) === count($group['permissions']) ? 'checked' : '' }}
                                                    aria-label="Select all actions for {{ $resource }} for subadmin"
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                       
                    </div>
                </form>
            </div>

            <!-- Right Section: View Assigned Permissions -->
            <div class="bg-white rounded-xl shadow-sm p-6 ring-1 ring-gray-200 animate-fadeIn">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Assigned Permissions for Subadmin</h2>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table id="permissions-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/3">Resource</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/3">Description</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/3">Assigned Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if (empty($resourceGroups))
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No permissions assigned</td>
                                </tr>
                            @else
                                @foreach ($resourceGroups as $resource => $group)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700 capitalize" data-resource="{{ $resource }}">{{ $resource }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $group['description'] }}</td>
                                        <td class="px-4 py-3 text-sm font-medium">
                                            @if (empty(array_filter(array_column($group['permissions'], 'assigned'))))
                                                <span class="text-red-600" aria-label="{{ $resource }} has no actions assigned for subadmin">None</span>
                                            @else
                                                <div class="flex flex-wrap gap-4">
                                                    @foreach ($group['permissions'] as $permission)
                                                        <span
                                                            class="{{ $permission->assigned ? 'text-green-600' : 'text-red-600' }}"
                                                            aria-label="{{ $permission->action }} is {{ $permission->assigned ? 'enabled' : 'disabled' }} for {{ $resource }}"
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

        <script>
            $(document).ready(function() {
                // Initialize temporary storage key
                const TEMP_STORAGE_KEY = 'subadmin_temp_permissions';

                // Get valid permission IDs from the page
                const validPermissionIds = $('input.sub-action-checkbox').map(function() {
                    return parseInt($(this).val());
                }).get();

                // Load permissions from localStorage or server
                function loadPermissions() {
                    const tempPermissions = localStorage.getItem(TEMP_STORAGE_KEY);
                    let permissions = [];

                    if (tempPermissions) {
                        try {
                            const parsed = JSON.parse(tempPermissions);
                            // Validate permissions: only include valid IDs
                            if (Array.isArray(parsed)) {
                                permissions = parsed.filter(id => validPermissionIds.includes(id));
                                console.log('Loaded permissions from localStorage:', permissions);
                            }
                        } catch (e) {
                            console.warn('Invalid localStorage data:', e);
                        }
                    }

                    if (permissions.length > 0) {
                        // Use localStorage permissions
                        $('input.sub-action-checkbox').each(function() {
                            const permissionId = parseInt($(this).val());
                            $(this).prop('checked', permissions.includes(permissionId));
                        });
                    } else {
                        // Fall back to server permissions
                        permissions = getSelectedPermissions();
                        console.log('Loaded permissions from server:', permissions);
                    }

                    updateSelectAllCheckboxes();
                    updatePermissionsTable(permissions);
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

                // Update permissions table UI
                function updatePermissionsTable(permissions) {
                    console.log('Updating permissions table with:', permissions);
                    $('#permissions-table tbody tr').each(function() {
                        const resource = $(this).find('td:first').attr('data-resource');
                        const statusCell = $(this).find('td:last');
                        const assignedActions = [];

                        $(`input.sub-action-checkbox[data-resource="${resource}"]`).each(function() {
                            const permissionId = parseInt($(this).val());
                            const action = $(this).next('span').text();
                            const isAssigned = permissions.includes(permissionId);
                            assignedActions.push(
                                `<span class="${isAssigned ? 'text-green-600' : 'text-red-600'}" aria-label="${action} is ${isAssigned ? 'enabled' : 'disabled'}">${action} ${isAssigned ? '✓' : '✗'}</span>`
                            );
                        });

                        if (assignedActions.length > 0) {
                            statusCell.html(
                                `<div class="flex flex-wrap gap-4">${assignedActions.join('')}</div>`
                            );
                        } else {
                            statusCell.html(
                                `<span class="text-red-600" aria-label="No actions enabled for this resource">None</span>`
                            );
                        }
                    });
                }

                // Show notification
                function showNotification(message, type = 'success') {
                    const notification = $(`
                        <div class="fixed top-4 right-4 bg-${type === 'success' ? 'green' : 'red'}-50 border-l-4 border-${type === 'success' ? 'green' : 'red'}-500 text-${type === 'success' ? 'green' : 'red'}-700 p-4 rounded-md animate-fadeIn z-50" role="alert">
                            ${message}
                        </div>
                    `);
                    $('body').append(notification);
                    setTimeout(() => notification.fadeOut(300, () => notification.remove()), 3000);
                }

                // Store initial server-assigned permissions
                $('input.sub-action-checkbox').each(function() {
                    $(this).data('initial-assigned', $(this).prop('checked') ? '1' : '0');
                });

                // Load permissions on page load
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
                    const originalText = $button.text();
                    $button.addClass('loading').text('Saving...').prop('disabled', true);

                    const permissions = getSelectedPermissions();
                    if (permissions.length === 0) {
                        showNotification('No permissions selected.', 'error');
                        $button.removeClass('loading').text(originalText).prop('disabled', false);
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
                                // Validate server response
                                const serverPermissions = response.permissions && Array.isArray(response.permissions)
                                    ? response.permissions.filter(id => validPermissionIds.includes(id))
                                    : permissions;
                                console.log('Server returned permissions:', serverPermissions);

                                // Update checkboxes based on server response
                                $('input.sub-action-checkbox').each(function() {
                                    const permissionId = parseInt($(this).val());
                                    $(this).prop('checked', serverPermissions.includes(permissionId));
                                });

                                // Save to localStorage for persistence
                                localStorage.setItem(TEMP_STORAGE_KEY, JSON.stringify(serverPermissions));

                                // Update UI
                                updateSelectAllCheckboxes();
                                updatePermissionsTable(serverPermissions);

                                showNotification(response.message || 'Permissions saved successfully!');

                                // Update initial assigned state
                                $('input.sub-action-checkbox').each(function() {
                                    const permissionId = parseInt($(this).val());
                                    $(this).data('initial-assigned', serverPermissions.includes(permissionId) ? '1' : '0');
                                });
                            } else {
                                console.warn('Server response unsuccessful:', response.message);
                                showNotification(response.message || 'Failed to save permissions.', 'error');
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message || 'Failed to save permissions. Please try again.';
                            console.error('AJAX error:', errorMessage);
                            showNotification(errorMessage, 'error');
                        },
                        complete: function() {
                            $button.removeClass('loading').text(originalText).prop('disabled', false);
                        }
                    });
                });
            });
        </script>
    </body>
</html>