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
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8 max-w-7xl">
        <header class="mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Admin Permission Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage permissions for the admin role with ease.</p>
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
            // Fetch role permissions for admin
            $rolePermissions = DB::table('role_permissions')
                ->where('role', 'admin')
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
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Assign Permissions for Admin</h2>
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
                                                                aria-label="Toggle {{ $permission->action }} for {{ $resource }} for admin"
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
                                                    aria-label="Select all actions for {{ $resource }} for admin"
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 text-sm font-medium">
                            Save Permissions
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Section: View Assigned Permissions -->
            <div class="bg-white rounded-xl shadow-sm p-6 ring-1 ring-gray-200 animate-fadeIn">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Assigned Permissions for Admin</h2>
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
                                                <span class="text-red-600" aria-label="{{ $resource }} has no actions assigned for admin">None</span>
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
                // Handle "Select All" checkbox
                $('.select-all-checkbox').on('change', function() {
                    let resource = $(this).data('resource');
                    let isChecked = $(this).is(':checked');
                    $(`input.sub-action-checkbox[data-resource="${resource}"]`).prop('checked', isChecked);
                });

                // Update "Select All" checkbox when individual sub-actions are toggled
                $('.sub-action-checkbox').on('change', function() {
                    let resource = $(this).data('resource');
                    let allChecked = $(`input.sub-action-checkbox[data-resource="${resource}"]`).length ===
                                     $(`input.sub-action-checkbox[data-resource="${resource}"]:checked`).length;
                    $(`input.select-all-checkbox[data-resource="${resource}"]`).prop('checked', allChecked);
                });

                $('#permissions-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST', // Laravel handles @method('PUT') via _method
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                updatePermissionsTable(response.permissions);
                                alert(response.message);
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON.message || 'Failed to save permissions'));
                        }
                    });
                });

                function updatePermissionsTable(permissions) {
                    $('#permissions-table tbody tr').each(function() {
                        let resource = $(this).find('td:first').attr('data-resource');
                        let statusCell = $(this).find('td:last');
                        let assignedActions = [];

                        $(`input.sub-action-checkbox[data-resource="${resource}"]`).each(function() {
                            let permissionId = $(this).val();
                            let action = $(this).next('span').text();
                            let isAssigned = permissions.includes(parseInt(permissionId));
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
            });
        </script>
    </body>
</html>