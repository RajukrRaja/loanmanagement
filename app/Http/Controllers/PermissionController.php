<?php

namespace App\Http\Controllers;

use App\Events\PermissionsUpdated;
use App\Models\AuditLog;
use App\Models\Dropdown;
use App\Models\DropdownItem;
use App\Models\Role;
use App\Models\RoleDropdownItem;
use App\Models\RoleDropdownPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    /**
     * Initialize controller with middleware for authentication and authorization.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->role_id, [1, 2])) {
                Log::error('Unauthorized access attempt', [
                    'user_id' => Auth::id() ?? 'Guest',
                    'path' => $request->path(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
                abort(403, 'Unauthorized');
            }
            return $next($request);
        })->only(['superadminPermissionForAdminView', 'updateRolePermissions', 'store']);
    }


    /**
     * Check if a table exists in the database.
     *
     * @param string $table
     * @return bool
     */
    private function tableExists($table)
    {
        return Schema::hasTable($table);
    }

    /**
     * Fetch all permissions with related role and dropdown item data.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAllPermissions()
    {
        if (!$this->tableExists('role_dropdown_permissions')) {
            Log::error('Table role_dropdown_permissions does not exist', ['timestamp' => now()->toDateTimeString()]);
            return collect([]);
        }

        Log::debug('Fetching all permissions from database', ['timestamp' => now()->toDateTimeString()]);
        return RoleDropdownPermission::with([
            'role' => fn($query) => $query->select('id', 'name'),
            'dropdownItem' => fn($query) => $query->select('id', 'dropdown_id', 'name', 'url', 'description')
                ->with(['dropdown' => fn($query) => $query->select('id', 'name')]),
        ])
            ->select('id', 'role_id', 'dropdown_item_id', 'can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc', 'created_at', 'updated_at')
            ->get()
            ->tap(function ($permissions) {
                Log::info('Fetched permissions', ['count' => $permissions->count(), 'timestamp' => now()->toDateTimeString()]);
            });
    }

    /**
     * Fetch all dropdowns with their items and associated permissions.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDropdowns()
    {
        if (!$this->tableExists('dropdowns')) {
            Log::error('Table dropdowns does not exist', ['timestamp' => now()->toDateTimeString()]);
            return collect([]);
        }

        Log::debug('Fetching all dropdowns and items from database', ['timestamp' => now()->toDateTimeString()]);
        return Dropdown::with([
            'items' => fn($query) => $query->select('id', 'dropdown_id', 'name', 'url', 'description')
                ->with(['actions' => fn($query) => $query->select('id', 'role_id', 'dropdown_item_id', 'can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc')]),
        ])
            ->select('id', 'name')
            ->whereIn('name', ['Master User', 'Manage Permissions', 'Lead Management'])
            ->get()
            ->tap(function ($dropdowns) {
                Log::info('Fetched dropdowns', ['count' => $dropdowns->count(), 'timestamp' => now()->toDateTimeString()]);
            });
    }

    /**
     * Fetch dropdown item IDs for a specific role.
     *
     * @param string $roleName
     * @return array
     */
    private function getRoleDropdownItems(string $roleName)
    {
        if (!$this->tableExists('roles') || !$this->tableExists('role_dropdown_items')) {
            Log::error('Required tables (roles or role_dropdown_items) do not exist', ['timestamp' => now()->toDateTimeString()]);
            return [];
        }

        Log::debug("Fetching dropdown items for role: {$roleName}", ['timestamp' => now()->toDateTimeString()]);
        $role = Role::where('name', $roleName)->select('id')->first();
        if (!$role) {
            Log::warning("Role not found: {$roleName}", ['timestamp' => now()->toDateTimeString()]);
            return [];
        }
        return RoleDropdownItem::where('role_id', $role->id)
            ->pluck('dropdown_item_id')
            ->tap(function ($items) use ($roleName) {
                Log::info("Fetched dropdown items for role: {$roleName}", ['count' => $items->count(), 'timestamp' => now()->toDateTimeString()]);
            })
            ->toArray();
    }

    /**
     * Load permission view for a specific role.
     *
     * @param string $viewName
     * @param string $roleName
     * @return \Illuminate\View\View
     */
    private function loadPermissionView(string $viewName, string $roleName)
    {
        Log::info("Loading view: {$viewName} for role: {$roleName}", ['timestamp' => now()->toDateTimeString()]);
        $permissions = $this->getAllPermissions();
        $dropdowns = $this->getDropdowns();
        $role = Role::where('name', $roleName)->select('id', 'name')->firstOrFail();
        $roleDropdownItems = $this->getRoleDropdownItems($roleName);

        return view("superadmin.permissions.{$viewName}", compact('permissions', 'dropdowns', 'role', 'roleDropdownItems'));
    }

    /**
     * Display the permission management view for the admin role.
     *
     * @return \Illuminate\View\View
     */
    public function superadminPermissionForAdminView()
    {
        return $this->loadPermissionView('admin', 'admin');
    }

    /**
     * Update dropdown items and permissions for a specific role.
     *
     * @param string $roleName
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRolePermissions(string $roleName, Request $request)
    {
        $requiredTables = ['roles', 'dropdown_items', 'role_dropdown_items', 'role_dropdown_permissions'];
        if (!collect($requiredTables)->every(fn($table) => $this->tableExists($table))) {
            Log::error('Required database tables are missing for updating permissions', ['timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => false, 'message' => 'Database tables missing'], 500);
        }

        $role = Role::where('name', $roleName)->select('id', 'name')->first();
        if (!$role) {
            Log::error("Role not found: {$roleName}", ['timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => false, 'message' => "Role '$roleName' not found"], 404);
        }

        Log::info('Starting updateRolePermissions', [
            'role' => $roleName,
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $request->validate([
            'dropdown_items' => ['nullable', 'array'],
            'dropdown_items.*' => ['integer', 'exists:dropdown_items,id'],
            'actions' => ['nullable', 'array'],
            'actions.*' => ['nullable', 'array'],
            'actions.*.can_create' => ['nullable', 'in:0,1'],
            'actions.*.can_read' => ['nullable', 'in:0,1'],
            'actions.*.can_update' => ['nullable', 'in:0,1'],
            'actions.*.can_delete' => ['nullable', 'in:0,1'],
            'actions.*.can_view_lead' => [
                'nullable', 'in:0,1',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
            'actions.*.can_approve_kyc' => [
                'nullable', 'in:0,1',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
            'actions.*.can_reject_kyc' => [
                'nullable', 'in:0,1',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
        ], [
            'dropdown_items.*.exists' => 'Invalid dropdown item ID: :input',
            'actions.*.*.in' => 'Invalid permission value for :attribute',
        ]);

        $submittedItems = array_filter((array)$request->input('dropdown_items', []), fn($id) => is_numeric($id));
        $submittedActions = array_filter((array)$request->input('actions', []), fn($actions, $id) => is_numeric($id), ARRAY_FILTER_USE_BOTH);

        // Apply default permissions for admin role (id=2) for dropdown_item_id = 1 if not in submitted actions
        if ($role->id == 2 && !isset($submittedActions[1])) {
            $specificItemId = 1;
            $existingSpecificPermission = RoleDropdownPermission::where('role_id', $role->id)
                ->where('dropdown_item_id', $specificItemId)
                ->first();
            $defaultPermissions = [
                'can_create' => 1,
                'can_read' => 1,
                'can_update' => 1,
                'can_delete' => 0,
                'can_view_lead' => 0,
                'can_approve_kyc' => 0,
                'can_reject_kyc' => 0,
            ];
            if (!$existingSpecificPermission) {
                RoleDropdownPermission::create(array_merge([
                    'role_id' => $role->id,
                    'dropdown_item_id' => $specificItemId,
                ], $defaultPermissions));
                Log::info('Created default admin permissions', [
                    'dropdown_item_id' => $specificItemId,
                    'role_id' => $role->id,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            } elseif ($this->permissionsHaveChanged($existingSpecificPermission, $defaultPermissions)) {
                $existingSpecificPermission->update($defaultPermissions);
                Log::info('Updated default admin permissions', [
                    'dropdown_item_id' => $specificItemId,
                    'role_id' => $role->id,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
            if (!in_array($specificItemId, $submittedItems)) {
                $submittedItems[] = $specificItemId;
            }
        }

        // Validate that actions keys match dropdown_items
        $invalidActionKeys = array_diff(array_keys($submittedActions), array_map('strval', $submittedItems));
        if (!empty($invalidActionKeys)) {
            Log::warning('Invalid action keys detected', [
                'invalid_keys' => $invalidActionKeys,
                'role_id' => $role->id,
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid dropdown item IDs in actions: ' . implode(', ', $invalidActionKeys),
            ], 422);
        }

        Log::info('Processed request data', [
            'role' => $roleName,
            'submitted_items' => $submittedItems,
            'submitted_actions' => array_keys($submittedActions),
            'timestamp' => now()->toDateTimeString(),
        ]);

        DB::beginTransaction();
        try {
            $existingItems = RoleDropdownItem::where('role_id', $role->id)->pluck('dropdown_item_id')->toArray();
            $existingPermissions = RoleDropdownPermission::where('role_id', $role->id)->get()->keyBy('dropdown_item_id');

            Log::debug('Existing permissions before update', [
                'role_id' => $role->id,
                'permissions' => $existingPermissions->map(fn($p) => [
                    'id' => $p->id,
                    'dropdown_item_id' => $p->dropdown_item_id,
                    'permissions' => $p->only(['can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc']),
                ])->toArray(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Remove unselected items
            $this->removeUnselectedItems($role, $existingItems, $submittedItems);

            // Add or update selected items and permissions
            $this->updateSelectedItems($role, $existingItems, $submittedItems, $submittedActions, $existingPermissions);

            DB::commit();

            // Clear caches for dropdown_id = 1 and 3
            $this->clearPermissionCaches($role->id, [1, 3]);

            // Log updated permissions
            $updatedPermissions = RoleDropdownPermission::where('role_id', $role->id)->get()->keyBy('dropdown_item_id');
            Log::info('Permissions updated successfully', [
                'role' => $roleName,
                'user_id' => Auth::id(),
                'items_count' => count($submittedItems),
                'actions_count' => count($submittedActions),
                'updated_permissions' => $updatedPermissions->map(fn($p) => [
                    'id' => $p->id,
                    'dropdown_item_id' => $p->dropdown_item_id,
                    'permissions' => $p->only(['can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc']),
                ])->toArray(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            broadcast(new PermissionsUpdated($roleName))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully',
                'dropdown_items' => $submittedItems,
                'actions' => $submittedActions,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission update failed', [
                'error' => $e->getMessage(),
                'role' => $roleName,
                'stack' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to update permissions'], 500);
        }
    }

    /**
     * Validate lead-specific permissions.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     * @param Request $request
     */
    private function validateLeadPermission($attribute, $value, $fail, Request $request)
    {
        $itemId = str_contains($attribute, '.') ? explode('.', $attribute)[1] : $request->input('dropdown_item_id');
        $item = DropdownItem::find($itemId);
        Log::debug('Validating lead permission', [
            'attribute' => $attribute,
            'value' => $value,
            'item_id' => $itemId,
            'dropdown_id' => $item ? $item->dropdown_id : null,
            'item_name' => $item ? $item->name : 'Not Found',
            'timestamp' => now()->toDateTimeString(),
        ]);
        if ($value && (!$item || $item->dropdown_id != 3 || strtolower($item->name) != 'lead')) {
            Log::warning('Lead permission validation failed', [
                'item_id' => $itemId,
                'dropdown_id' => $item ? $item->dropdown_id : null,
                'item_name' => $item ? $item->name : 'Not Found',
                'attribute' => $attribute,
                'timestamp' => now()->toDateTimeString(),
            ]);
            $fail('This permission is only applicable to Lead items in Lead Management.');
        } else {
            Log::info('Lead permission validated successfully', [
                'item_id' => $itemId,
                'dropdown_id' => $item ? $item->dropdown_id : null,
                'item_name' => $item ? $item->name : 'Not Found',
                'timestamp' => now()->toDateTimeString(),
            ]);
        }
    }

    /**
     * Remove unselected dropdown items and their permissions.
     *
     * @param Role $role
     * @param array $existingItems
     * @param array $submittedItems
     */
    private function removeUnselectedItems($role, $existingItems, $submittedItems)
    {
        $itemsToRemove = array_diff($existingItems, $submittedItems);
        if ($itemsToRemove) {
            $removedRecords = RoleDropdownItem::where('role_id', $role->id)
                ->whereIn('dropdown_item_id', $itemsToRemove)
                ->get(['id', 'dropdown_item_id']);
            RoleDropdownItem::where('role_id', $role->id)
                ->whereIn('dropdown_item_id', $itemsToRemove)
                ->delete();
            RoleDropdownPermission::where('role_id', $role->id)
                ->whereIn('dropdown_item_id', $itemsToRemove)
                ->delete();

            foreach ($removedRecords as $record) {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Remove Dropdown Item',
                    'table_name' => 'role_dropdown_items',
                    'record_id' => $record->id,
                    'old_value' => json_encode(['dropdown_item_id' => $record->dropdown_item_id]),
                    'new_value' => null,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
            Log::info('Removed unselected items', [
                'dropdown_item_ids' => $itemsToRemove,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }
    }

    /**
     * Add or update selected dropdown items and their permissions.
     *
     * @param Role $role
     * @param array $existingItems
     * @param array $submittedItems
     * @param array $submittedActions
     * @param \Illuminate\Support\Collection $existingPermissions
     */
    private function updateSelectedItems($role, $existingItems, $submittedItems, $submittedActions, $existingPermissions)
    {
        foreach ($submittedItems as $dropdownItemId) {
            Log::debug('Processing dropdown item', [
                'dropdown_item_id' => $dropdownItemId,
                'item_name' => DropdownItem::find($dropdownItemId)->name ?? 'Unknown',
                'timestamp' => now()->toDateTimeString(),
            ]);
            if (!in_array($dropdownItemId, $existingItems)) {
                $item = RoleDropdownItem::create(['role_id' => $role->id, 'dropdown_item_id' => $dropdownItemId]);
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Assign Dropdown Item',
                    'table_name' => 'role_dropdown_items',
                    'record_id' => $item->id,
                    'old_value' => null,
                    'new_value' => json_encode(['dropdown_item_id' => $dropdownItemId]),
                    'timestamp' => now()->toDateTimeString(),
                ]);
                Log::info('Assigned new dropdown item', [
                    'dropdown_item_id' => $dropdownItemId,
                    'item_name' => DropdownItem::find($dropdownItemId)->name ?? 'Unknown',
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }

            if (isset($submittedActions[$dropdownItemId])) {
                Log::debug('Updating permissions for item', [
                    'dropdown_item_id' => $dropdownItemId,
                    'actions' => $submittedActions[$dropdownItemId],
                    'timestamp' => now()->toDateTimeString(),
                ]);
                $this->updatePermission($role, $dropdownItemId, $submittedActions[$dropdownItemId], $existingPermissions);
            } else {
                // Ensure a default permission record exists if no actions are submitted
                $this->ensureDefaultPermission($role, $dropdownItemId, $existingPermissions);
            }
        }
    }

    /**
     * Ensure a default permission record exists for a dropdown item.
     *
     * @param Role $role
     * @param int $dropdownItemId
     * @param \Illuminate\Support\Collection $existingPermissions
     */
    private function ensureDefaultPermission($role, $dropdownItemId, $existingPermissions)
    {
        if (!$existingPermissions->has($dropdownItemId)) {
            $dropdownItem = DropdownItem::find($dropdownItemId);
            $isLeadItem = $dropdownItem && $dropdownItem->dropdown_id == 3 && strtolower($dropdownItem->name) == 'lead';
            $defaultPermissions = [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
                'can_view_lead' => $isLeadItem ? false : 0,
                'can_approve_kyc' => $isLeadItem ? false : 0,
                'can_reject_kyc' => $isLeadItem ? false : 0,
            ];
            $permission = RoleDropdownPermission::create(array_merge([
                'role_id' => $role->id,
                'dropdown_item_id' => $dropdownItemId,
            ], $defaultPermissions));
            Log::info('Created default permission record', [
                'permission_id' => $permission->id,
                'role_id' => $role->id,
                'dropdown_item_id' => $dropdownItemId,
                'item_name' => $dropdownItem ? $dropdownItem->name : 'Unknown',
                'timestamp' => now()->toDateTimeString(),
            ]);
            $this->logPermissionUpdate($permission, $defaultPermissions, 'Create');
        }
    }

    /**
     * Update permissions for a specific dropdown item.
     *
     * @param Role $role
     * @param int $dropdownItemId
     * @param array $actionData
     * @param \Illuminate\Support\Collection $existingPermissions
     */
    private function updatePermission($role, $dropdownItemId, $actionData, $existingPermissions)
    {
        $dropdownItem = DropdownItem::find($dropdownItemId);
        if (!$dropdownItem) {
            Log::warning('Invalid dropdown_item_id in updatePermission', [
                'dropdown_item_id' => $dropdownItemId,
                'role_id' => $role->id,
                'timestamp' => now()->toDateTimeString(),
            ]);
            return;
        }

        $isLeadItem = $dropdownItem->dropdown_id == 3 && strtolower($dropdownItem->name) == 'lead';
        $existingPermission = $existingPermissions->get($dropdownItemId);
        $defaultPermissions = [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
            'can_view_lead' => $isLeadItem ? false : 0,
            'can_approve_kyc' => $isLeadItem ? false : 0,
            'can_reject_kyc' => $isLeadItem ? false : 0,
        ];
        $currentPermission = $existingPermission ? $existingPermission->only(array_keys($defaultPermissions)) : $defaultPermissions;

        $updatedPermission = array_merge($currentPermission, array_intersect_key([
            'can_create' => (bool)($actionData['can_create'] ?? $currentPermission['can_create']),
            'can_read' => (bool)($actionData['can_read'] ?? $currentPermission['can_read']),
            'can_update' => (bool)($actionData['can_update'] ?? $currentPermission['can_update']),
            'can_delete' => (bool)($actionData['can_delete'] ?? $currentPermission['can_delete']),
            'can_view_lead' => $isLeadItem ? (bool)($actionData['can_view_lead'] ?? $currentPermission['can_view_lead']) : 0,
            'can_approve_kyc' => $isLeadItem ? (bool)($actionData['can_approve_kyc'] ?? $currentPermission['can_approve_kyc']) : 0,
            'can_reject_kyc' => $isLeadItem ? (bool)($actionData['can_reject_kyc'] ?? $currentPermission['can_reject_kyc']) : 0,
        ], $actionData));

        Log::debug('Updating permission', [
            'role_id' => $role->id,
            'dropdown_item_id' => $dropdownItemId,
            'dropdown_id' => $dropdownItem->dropdown_id,
            'item_name' => $dropdownItem->name,
            'submitted_permissions' => $actionData,
            'current_permissions' => $currentPermission,
            'updated_permissions' => $updatedPermission,
            'timestamp' => now()->toDateTimeString(),
        ]);

        $success = false;
        if ($existingPermission) {
            if ($this->permissionsHaveChanged($existingPermission, $updatedPermission)) {
                try {
                    $existingPermission->update($updatedPermission);
                    $success = true;
                } catch (\Exception $e) {
                    Log::error('Eloquent update failed, falling back to raw query', [
                        'error' => $e->getMessage(),
                        'dropdown_item_id' => $dropdownItemId,
                        'role_id' => $role->id,
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                }
                if (!$success) {
                    DB::update(
                        'UPDATE role_dropdown_permissions SET can_create = ?, can_read = ?, can_update = ?, can_delete = ?, can_view_lead = ?, can_approve_kyc = ?, can_reject_kyc = ? WHERE role_id = ? AND dropdown_item_id = ?',
                        [
                            $updatedPermission['can_create'],
                            $updatedPermission['can_read'],
                            $updatedPermission['can_update'],
                            $updatedPermission['can_delete'],
                            $updatedPermission['can_view_lead'],
                            $updatedPermission['can_approve_kyc'],
                            $updatedPermission['can_reject_kyc'],
                            $role->id,
                            $dropdownItemId,
                        ]
                    );
                    $success = true;
                }
                $this->logPermissionUpdate($existingPermission, $updatedPermission, 'Update');
                Log::info('Updated permission', [
                    'permission_id' => $existingPermission->id,
                    'dropdown_item_id' => $dropdownItemId,
                    'item_name' => $dropdownItem->name,
                    'changes' => $updatedPermission,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            } else {
                Log::info('No changes detected for existing permission', [
                    'permission_id' => $existingPermission->id,
                    'dropdown_item_id' => $dropdownItemId,
                    'item_name' => $dropdownItem->name,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
        } else {
            try {
                $permission = RoleDropdownPermission::create(array_merge([
                    'role_id' => $role->id,
                    'dropdown_item_id' => $dropdownItemId,
                ], $updatedPermission));
                $success = true;
            } catch (\Exception $e) {
                Log::error('Eloquent create failed, falling back to raw query', [
                    'error' => $e->getMessage(),
                    'dropdown_item_id' => $dropdownItemId,
                    'role_id' => $role->id,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
            if (!$success) {
                DB::insert(
                    'INSERT INTO role_dropdown_permissions (role_id, dropdown_item_id, can_create, can_read, can_update, can_delete, can_view_lead, can_approve_kyc, can_reject_kyc, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE can_create = VALUES(can_create), can_read = VALUES(can_read), can_update = VALUES(can_update), can_delete = VALUES(can_delete), can_view_lead = VALUES(can_view_lead), can_approve_kyc = VALUES(can_approve_kyc), can_reject_kyc = VALUES(can_reject_kyc), updated_at = NOW()',
                    [
                        $role->id,
                        $dropdownItemId,
                        $updatedPermission['can_create'],
                        $updatedPermission['can_read'],
                        $updatedPermission['can_update'],
                        $updatedPermission['can_delete'],
                        $updatedPermission['can_view_lead'],
                        $updatedPermission['can_approve_kyc'],
                        $updatedPermission['can_reject_kyc'],
                    ]
                );
                $permission = RoleDropdownPermission::where('role_id', $role->id)
                    ->where('dropdown_item_id', $dropdownItemId)
                    ->first();
                $success = true;
            }
            if ($success) {
                $this->logPermissionUpdate($permission, $updatedPermission, 'Create');
                Log::info('Created permission', [
                    'permission_id' => $permission->id ?? 'raw_query',
                    'dropdown_item_id' => $dropdownItemId,
                    'item_name' => $dropdownItem->name,
                    'permissions' => $updatedPermission,
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
        }
    }

    /**
     * Check if permissions have changed.
     *
     * @param RoleDropdownPermission $existingPermission
     * @param array $updatedPermission
     * @return bool
     */
    private function permissionsHaveChanged($existingPermission, $updatedPermission)
    {
        foreach ($updatedPermission as $key => $value) {
            if ($existingPermission->$key !== (bool)$value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Log permission update in audit log.
     *
     * @param RoleDropdownPermission $permission
     * @param array $updatedPermission
     * @param string $action
     */
    private function logPermissionUpdate($permission, $updatedPermission, $action)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => "{$action} Permission",
            'table_name' => 'role_dropdown_permissions',
            'record_id' => $permission->id,
            'old_value' => $action === 'Update' ? json_encode($permission->getOriginal()) : null,
            'new_value' => json_encode($updatedPermission),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Clear permission-related caches.
     *
     * @param int $roleId
     * @param array $dropdownIds
     */
    private function clearPermissionCaches($roleId, $dropdownIds = [])
    {
        Cache::forget("permissions_role:{$roleId}");
        Cache::forget("user_permissions_{$roleId}");
        Cache::forget('user_permissions_' . Auth::id());
        Session::forget('user_permissions');

        $affectedUsers = DB::table('users')->where('role_id', $roleId)->pluck('id');
        foreach ($affectedUsers as $userId) {
            Cache::forget("user_permissions_{$userId}");
            foreach (['create', 'read', 'update', 'delete', 'view-lead', 'approve-kyc', 'reject-kyc'] as $ability) {
                Cache::forget("permission_{$ability}_{$roleId}_20");
            }
        }

        foreach ($dropdownIds as $dropdownId) {
            Cache::forget("permissions_dropdown_{$roleId}_{$dropdownId}");
        }
        Log::info('Cleared permission caches', [
            'role_id' => $roleId,
            'dropdown_ids' => $dropdownIds,
            'affected_users' => $affectedUsers->toArray(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Fetch permissions for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPermissions(Request $request)
    {
        $requiredTables = ['roles', 'role_dropdown_items', 'dropdown_items', 'dropdowns'];
        if (!collect($requiredTables)->every(fn($table) => $this->tableExists($table))) {
            Log::error('Database tables missing for fetching permissions', ['timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => false, 'message' => 'Database tables missing'], 500);
        }

        $user = Auth::user();
        if (!$user) {
            Log::warning('No authenticated user', ['timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $roleId = $user->role_id;
        if (!$roleId || !Role::where('id', $roleId)->exists()) {
            Log::info('No valid role assigned', ['user_id' => $user->id, 'timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => true, 'dropdowns' => [], 'message' => 'No permissions assigned'], 200);
        }

        if ($request->has('refresh')) {
            Cache::forget("permissions_role:{$roleId}");
            Cache::forget("user_permissions_{$roleId}");
            Cache::forget('user_permissions_' . Auth::id());
            Session::forget('user_permissions');
            foreach (['create', 'read', 'update', 'delete', 'view-lead', 'approve-kyc', 'reject-kyc'] as $ability) {
                Cache::forget("permission_{$ability}_{$roleId}_20");
            }
            Log::info('Forced permission cache clear', [
                'user_id' => $user->id,
                'role_id' => $roleId,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        $cacheKey = "permissions_role:{$roleId}";
        $dropdowns = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($roleId) {
            $items = RoleDropdownItem::where('role_dropdown_items.role_id', $roleId)
                ->join('dropdown_items', 'role_dropdown_items.dropdown_item_id', '=', 'dropdown_items.id')
                ->join('dropdowns', 'dropdown_items.dropdown_id', '=', 'dropdowns.id')
                ->leftJoin('role_dropdown_permissions', function ($join) use ($roleId) {
                    $join->on('role_dropdown_permissions.dropdown_item_id', '=', 'dropdown_items.id')
                         ->where('role_dropdown_permissions.role_id', '=', $roleId);
                })
                ->select(
                    'dropdowns.id as dropdown_id',
                    'dropdowns.name as dropdown_name',
                    'dropdown_items.id as item_id',
                    'dropdown_items.dropdown_id',
                    'dropdown_items.name as item_name',
                    'dropdown_items.url as item_url',
                    'role_dropdown_permissions.can_create',
                    'role_dropdown_permissions.can_read',
                    'role_dropdown_permissions.can_update',
                    'role_dropdown_permissions.can_delete',
                    'role_dropdown_permissions.can_view_lead',
                    'role_dropdown_permissions.can_approve_kyc',
                    'role_dropdown_permissions.can_reject_kyc'
                )
                ->whereNotNull('dropdown_items.url')
                ->whereIn('dropdowns.name', ['Master User', 'Manage Permissions', 'Lead Management'])
                ->orderBy('dropdowns.id')
                ->orderBy('dropdown_items.id')
                ->get();

            $dropdowns = [];
            foreach ($items as $item) {
                $dropdownIndex = array_search($item->dropdown_id, array_column($dropdowns, 'id'));
                if ($dropdownIndex === false) {
                    $dropdowns[] = [
                        'id' => $item->dropdown_id,
                        'name' => $item->dropdown_name,
                        'items' => [],
                    ];
                    $dropdownIndex = count($dropdowns) - 1;
                }

                $dropdowns[$dropdownIndex]['items'][] = [
                    'id' => $item->item_id,
                    'dropdown_id' => $item->dropdown_id,
                    'name' => $item->item_name,
                    'url' => $item->item_url,
                    'permissions' => [
                        'can_create' => (bool)($item->can_create ?? false),
                        'can_read' => (bool)($item->can_read ?? false),
                        'can_update' => (bool)($item->can_update ?? false),
                        'can_delete' => (bool)($item->can_delete ?? false),
                        'can_view_lead' => (bool)($item->can_view_lead ?? false),
                        'can_approve_kyc' => (bool)($item->can_approve_kyc ?? false),
                        'can_reject_kyc' => (bool)($item->can_reject_kyc ?? false),
                    ],
                ];
            }

            return $dropdowns;
        });

        Session::put('user_permissions', $dropdowns);

        Log::info('Fetched user permissions', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'dropdowns_count' => count($dropdowns),
            'timestamp' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'dropdowns' => $dropdowns,
            'message' => 'Permissions fetched successfully',
        ]);
    }

    /**
     * Store a new dropdown item and its permissions for a role.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $requiredTables = ['roles', 'dropdown_items', 'role_dropdown_items', 'role_dropdown_permissions'];
        if (!collect($requiredTables)->every(fn($table) => $this->tableExists($table))) {
            Log::error('Database tables missing for storing dropdown item', ['timestamp' => now()->toDateTimeString()]);
            return response()->json(['success' => false, 'message' => 'Database tables missing'], 500);
        }

        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'dropdown_item_id' => ['required', 'exists:dropdown_items,id'],
            'can_create' => ['nullable', 'boolean'],
            'can_read' => ['nullable', 'boolean'],
            'can_update' => ['nullable', 'boolean'],
            'can_delete' => ['nullable', 'boolean'],
            'can_view_lead' => [
                'nullable', 'boolean',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
            'can_approve_kyc' => [
                'nullable', 'boolean',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
            'can_reject_kyc' => [
                'nullable', 'boolean',
                fn($attribute, $value, $fail) => $this->validateLeadPermission($attribute, $value, $fail, $request),
            ],
        ]);

        DB::beginTransaction();
        try {
            $dropdownItemId = $request->dropdown_item_id;

            $item = RoleDropdownItem::firstOrCreate([
                'role_id' => $request->role_id,
                'dropdown_item_id' => $dropdownItemId,
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Assign Dropdown Item',
                'table_name' => 'role_dropdown_items',
                'record_id' => $item->id,
                'old_value' => $item->wasRecentlyCreated ? null : json_encode(['dropdown_item_id' => $item->dropdown_item_id]),
                'new_value' => json_encode(['dropdown_item_id' => $item->dropdown_item_id]),
                'timestamp' => now()->toDateTimeString(),
            ]);

            if ($request->hasAny(['can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc'])) {
                $permissionData = [
                    'can_create' => filter_var($request->input('can_create', false), FILTER_VALIDATE_BOOLEAN),
                    'can_read' => filter_var($request->input('can_read', false), FILTER_VALIDATE_BOOLEAN),
                    'can_update' => filter_var($request->input('can_update', false), FILTER_VALIDATE_BOOLEAN),
                    'can_delete' => filter_var($request->input('can_delete', false), FILTER_VALIDATE_BOOLEAN),
                    'can_view_lead' => filter_var($request->input('can_view_lead', false), FILTER_VALIDATE_BOOLEAN),
                    'can_approve_kyc' => filter_var($request->input('can_approve_kyc', false), FILTER_VALIDATE_BOOLEAN),
                    'can_reject_kyc' => filter_var($request->input('can_reject_kyc', false), FILTER_VALIDATE_BOOLEAN),
                ];

                Log::debug('Storing permission', [
                    'role_id' => $request->role_id,
                    'dropdown_item_id' => $dropdownItemId,
                    'item_name' => DropdownItem::find($dropdownItemId)->name ?? 'Unknown',
                    'permissions' => $permissionData,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                $allPermissionsFalse = !array_filter($permissionData, fn($value) => (bool)$value);
                if ($allPermissionsFalse) {
                    RoleDropdownPermission::where('role_id', $request->role_id)
                        ->where('dropdown_item_id', $dropdownItemId)
                        ->delete();
                } else {
                    $permission = RoleDropdownPermission::updateOrCreate(
                        ['role_id' => $request->role_id, 'dropdown_item_id' => $dropdownItemId],
                        $permissionData
                    );

                    AuditLog::create([
                        'user_id' => Auth::id(),
                        'action' => 'Update Permission',
                        'table_name' => 'role_dropdown_permissions',
                        'record_id' => $permission->id,
                        'old_value' => $permission->wasRecentlyCreated ? null : json_encode($permission->getOriginal()),
                        'new_value' => json_encode($permissionData),
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                }
            }

            DB::commit();

            $this->clearPermissionCaches($request->role_id, [1, 3]);

            $role = Role::find($request->role_id);
            if ($role) {
                broadcast(new PermissionsUpdated($role->name))->toOthers();
            }

            Log::info('Dropdown item assigned successfully', [
                'user_id' => Auth::id(),
                'role_id' => $request->role_id,
                'dropdown_item_id' => $dropdownItemId,
                'item_name' => DropdownItem::find($dropdownItemId)->name ?? 'Unknown',
                'timestamp' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'success' => true,
                'dropdown_item' => $item,
                'permissions' => $request->only(['can_create', 'can_read', 'can_update', 'can_delete', 'can_view_lead', 'can_approve_kyc', 'can_reject_kyc']),
                'message' => 'Dropdown item assigned successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store dropdown item', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to assign dropdown item'], 500);
        }
    }

    /**
     * Debug endpoint to refresh user permissions.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshPermissions(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role_id, [1, 2])) {
            Log::error('Unauthorized attempt to refresh permissions', [
                'user_id' => Auth::id() ?? 'Guest',
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $roleId = Auth::user()->role_id;
        $userId = Auth::id();

        Cache::forget("permissions_role:{$roleId}");
        Cache::forget("user_permissions_{$roleId}");
        Cache::forget("user_permissions_{$userId}");
        Session::forget('user_permissions');
        foreach (['create', 'read', 'update', 'delete', 'view-lead', 'approve-kyc', 'reject-kyc'] as $ability) {
            Cache::forget("permission_{$ability}_{$roleId}_20");
        }

        Log::info('Permissions refreshed manually', [
            'user_id' => $userId,
            'role_id' => $roleId,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permissions refreshed successfully',
        ]);
    }
}
