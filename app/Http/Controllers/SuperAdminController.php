<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;


class SuperAdminController extends Controller
{
    // Normalize role names to match users table
    private function normalizeRole($role)
    {
        $roleMap = [
            'subadmin' => 'sub_admin',
            'branch' => 'branch_login',
            'subbranchadmin' => 'sub_branch_admin',
            'regionhead' => 'region_head',
            'teammanager' => 'team_manager',
            'admin' => 'admin',
            'telecaller' => 'telecaller',
            'accountant' => 'accountant',
            'employee' => 'employee',
            'customer' => 'customer',
        ];
        return $roleMap[$role] ?? $role;
    }

    // Shared logic for fetching permissions
    private function getPermissionsForRole($role)
    {
        try {
            \DB::connection()->getPdo();
            $normalizedRole = $this->normalizeRole($role);
            $permissions = DB::table('permissions')->select('permission_id', 'name', 'display_name')->get();
            $rolePermissions = DB::table('role_permissions')
                ->where('role', $normalizedRole)
                ->pluck('permission_id')
                ->toArray();
            Log::info("Fetched permissions for role: {$role} (normalized: {$normalizedRole})", [
                'permissions_count' => $permissions->count(),
                'role_permissions_count' => count($rolePermissions),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return compact('permissions', 'rolePermissions', 'role');
        } catch (QueryException $e) {
            Log::error("Database query error fetching permissions for role: {$role}", [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return [
                'permissions' => collect(),
                'rolePermissions' => [],
                'role' => $role,
                'error' => 'Database error: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error("General error fetching permissions for role: {$role}", [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return [
                'permissions' => collect(),
                'rolePermissions' => [],
                'role' => $role,
                'error' => 'An unexpected error occurred. Please check the logs.'
            ];
        }
    }

    // Shared logic for updating permissions
    private function updatePermissionsForRole(Request $request, $role)
    {
        try {
            \DB::connection()->getPdo();
            if (!Schema::hasTable('role_permissions')) {
                throw new \Exception('role_permissions table does not exist. Please run migrations.');
            }

            $validated = $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,permission_id',
            ]);

            $normalizedRole = $this->normalizeRole($role);
            DB::transaction(function () use ($normalizedRole, $validated) {
                DB::table('role_permissions')->where('role', $normalizedRole)->delete();
                $permissions = $validated['permissions'] ?? [];
                if (!empty($permissions)) {
                    $data = array_map(function ($permissionId) use ($normalizedRole) {
                        return [
                            'role' => $normalizedRole,
                            'permission_id' => (int)$permissionId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }, $permissions);
                    DB::table('role_permissions')->insert($data);
                }
            });

            Log::info("Permissions updated for role: {$role} (normalized: {$normalizedRole})", [
                'permissions_count' => count($validated['permissions'] ?? []),
                'user_id' => Auth::id() ?? 'guest'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully for ' . ucfirst($role),
                'permissions' => $validated['permissions'] ?? []
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation error updating permissions for role: {$role}", [
                'errors' => $e->errors(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            Log::error("Database query error updating permissions for role: {$role}", [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Database error updating permissions',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error("General error updating permissions for role: {$role}", [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dashboard(Request $request)
    {
        return view('dashboards.superadmin');
    }

    public function users(Request $request)
    {
        try {
            \DB::connection()->getPdo();
            $users = User::with('branch')->paginate(10);
            $branches = Branch::all();
            Log::info('Fetched all users', [
                'count' => $users->count(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return view('superadmin.users', compact('users', 'branches'));
        } catch (QueryException $e) {
            Log::error('Database query error fetching users', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            $users = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => url()->current()]
            );
            return view('superadmin.users', [
                'users' => $users,
                'branches' => collect(),
                'error' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error('General error fetching users', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            $users = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                10,
                1,
                ['path' => url()->current()]
            );
            return view('superadmin.users', [
                'users' => $users,
                'branches' => collect(),
                'error' => 'An unexpected error occurred. Please check the logs.'
            ]);
        }
    }
public function adminUserView(Request $request)
{
    try {
        \DB::connection()->getPdo();

        // Check if user is authenticated
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $adminRole = DB::table('roles')->where('name', 'admin')->first();

        if (!$adminRole) {
            Log::warning('Admin role not found in roles table', [
                'user_id' => Auth::id() ?? 'guest',
            ]);
            return view('superadmin.admin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Admin role not found.',
            ]);
        }

        // Fetch permissions
        $roleId = Auth::user()->role_id;
        $dropdownItemId = 1; // Changed to 1 for "Admin" based on dropdown_items
        $permissions = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissions ? [
            'can_create' => $permissions->can_create,
            'can_read' => $permissions->can_read,
            'can_update' => $permissions->can_update,
            'can_delete' => $permissions->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        // Debug permissions
        Log::info('Fetched permissions for admin user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permissions' => $permissions,
            'user_id' => Auth::id(),
            'dropdown_item' => DB::table('dropdown_items')->where('id', $dropdownItemId)->first(),
        ]);

        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $adminRole->id)->with('branch')->get();
        $branches = Branch::all();

        Log::info('Fetched admin users and roles', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => Auth::id(),
        ]);

        return view('superadmin.admin-users', compact('users', 'branches', 'roles', 'permissions'));
    } catch (QueryException $e) {
        Log::error('Database query error fetching admin users or roles', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest',
        ]);
        return view('superadmin.admin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);
    } catch (\Exception $e) {
        Log::error('General error fetching admin users or roles', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest',
        ]);
        return view('superadmin.admin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}

 public function subadminUserView(Request $request)
    {
        try {
            // Check DB connection
            \DB::connection()->getPdo();
            Log::info('Database connection established for subadminUserView');

            // Auth check
            if (!Auth::check()) {
                Log::error('No authenticated user found');
                return redirect()->route('login');
            }

            $user = Auth::user();
            $roleId = $user->role_id;

            Log::info('Authenticated user', [
                'user_id' => $user->id,
                'role_id' => $roleId,
                'email' => $user->email,
            ]);

            // Fetch subadmin role
            $subAdminRole = DB::table('roles')->where('name', 'sub_admin')->first();
            if (!$subAdminRole) {
                Log::warning('Subadmin role not found', ['user_id' => $user->id]);
                return view('superadmin.subadmin-users', [
                    'users' => collect(),
                    'branches' => collect(),
                    'roles' => collect(),
                    'permissions' => [
                        'can_create' => false,
                        'can_read' => false,
                        'can_update' => false,
                        'can_delete' => false,
                    ],
                    'error' => 'Subadmin role not found.',
                ]);
            }

            // Get permissions
            $dropdownItemId = 5; // Replace with actual dropdown item ID for subadmin
            $permissionRecord = DB::table('role_dropdown_permissions')
                ->where('role_id', $roleId)
                ->where('dropdown_item_id', $dropdownItemId)
                ->first();

            $permissions = $permissionRecord ? [
                'can_create' => (bool) $permissionRecord->can_create,
                'can_read' => (bool) $permissionRecord->can_read,
                'can_update' => (bool) $permissionRecord->can_update,
                'can_delete' => (bool) $permissionRecord->can_delete,
            ] : [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ];

            Log::info('Fetched permissions for subadmin view', [
                'role_id' => $roleId,
                'dropdown_item_id' => $dropdownItemId,
                'permissions' => $permissions,
            ]);

            // Fetch records with pagination
            $roles = DB::table('roles')->get(['id', 'name']);
            $users = User::where('role_id', $subAdminRole->id)
                ->with(['role', 'branch'])
                ->paginate(10); // supports hasPages(), links()

            $branches = Branch::all();

            Log::info('Fetched subadmin users and roles', [
                'user_count' => $users->count(),
                'role_count' => $roles->count(),
                'branch_count' => $branches->count(),
                'user_id' => $user->id,
            ]);

            return view('superadmin.subadmin-users', compact('users', 'branches', 'roles', 'permissions'));
        } catch (QueryException $e) {
            Log::error('Database query error in subadminUserView', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'none',
            ]);
            return view('superadmin.subadmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Database error occurred. Please contact support.',
            ]);
        } catch (\Exception $e) {
            Log::error('General error in subadminUserView', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'none',
            ]);
            return view('superadmin.subadmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'An unexpected error occurred. Please check the logs.',
            ]);
        }
    }

   public function branchadminUserView(Request $request)
    {
        try {
            // Verify database connection
            \DB::connection()->getPdo();
            Log::info('Database connection established for branchadminUserView');

            // Check authentication
            if (!Auth::check()) {
                Log::error('No authenticated user found');
                return redirect()->route('login');
            }

            $user = Auth::user();
            $roleId = $user->role_id;
            Log::info('Authenticated user details', [
                'user_id' => $user->id,
                'role_id' => $roleId,
                'email' => $user->email,
            ]);

            // Fetch branch_login role
            $branchRole = DB::table('roles')->where('name', 'branch_login')->first();
            if (!$branchRole) {
                Log::warning('Branch role not found in roles table', [
                    'user_id' => $user->id,
                ]);
                return view('superadmin.branchadmin-users', [
                    'users' => collect(),
                    'branches' => collect(),
                    'roles' => collect(),
                    'permissions' => [
                        'can_create' => false,
                        'can_read' => false,
                        'can_update' => false,
                        'can_delete' => false,
                    ],
                    'error' => 'Branch role not found.',
                ]);
            }

            // Fetch permissions
            $dropdownItemId = 3; // Branch from dropdown_items
            $permissionRecord = DB::table('role_dropdown_permissions')
                ->where('role_id', $roleId)
                ->where('dropdown_item_id', $dropdownItemId)
                ->first();

            $permissions = $permissionRecord ? [
                'can_create' => (bool) $permissionRecord->can_create,
                'can_read' => (bool) $permissionRecord->can_read,
                'can_update' => (bool) $permissionRecord->can_update,
                'can_delete' => (bool) $permissionRecord->can_delete,
            ] : [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ];

            Log::info('Fetched permissions for branch admin user view', [
                'role_id' => $roleId,
                'dropdown_item_id' => $dropdownItemId,
                'permission_record' => $permissionRecord ? (array) $permissionRecord : null,
                'permissions' => $permissions,
                'user_id' => $user->id,
                'dropdown_item' => DB::table('dropdown_items')->where('id', $dropdownItemId)->first(),
            ]);

            // Fetch data
            $roles = DB::table('roles')->get(['id', 'name']);
            $users = User::where('role_id', $branchRole->id)->with(['role', 'branch'])->get();
            $branches = Branch::all();

            Log::info('Fetched branch admin users and roles', [
                'user_count' => $users->count(),
                'role_count' => $roles->count(),
                'branch_count' => $branches->count(),
                'user_id' => $user->id,
            ]);

            return view('superadmin.branchadmin-users', compact('users', 'branches', 'roles', 'permissions'));
        } catch (QueryException $e) {
            Log::error('Database query error in branchadminUserView', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'none',
            ]);
            return view('superadmin.branchadmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Database error occurred. Please contact support.',
            ]);
        } catch (\Exception $e) {
            Log::error('General error in branchadminUserView', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'none',
            ]);
            return view('superadmin.branchadmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'An unexpected error occurred. Please check the logs.',
            ]);
        }
    }


public function subbranchadminUserView(Request $request)
{
    try {
        // Ensure DB connection is active
        \DB::connection()->getPdo();
        Log::info('Database connection established for subbranchadminUserView');

        // Check authentication
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;

        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Get role ID for sub_branch_admin
        $subBranchAdminRole = DB::table('roles')->where('name', 'sub_branch_login')->first();
        if (!$subBranchAdminRole) {
            Log::warning('Sub Branch Admin role not found in roles table', [
                'user_id' => $user->id,
            ]);
            return view('superadmin.subbranchadmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Sub Branch Admin role not found.',
            ]);
        }

        // Permission check for dropdown item (set your dropdown_item_id accordingly)
        $dropdownItemId = 5; // You can customize this
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched permissions for sub-branch admin user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permission_record' => $permissionRecord ? (array) $permissionRecord : null,
            'permissions' => $permissions,
            'user_id' => $user->id,
        ]);

        // Fetch users, roles and branches
        $users = User::where('role_id', $subBranchAdminRole->id)->with(['role', 'branch'])->get();
        $roles = DB::table('roles')->get(['id', 'name']);
        $branches = Branch::all();

        Log::info('Fetched sub-branch admin users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.subbranchadmin-users', compact('users', 'branches', 'roles', 'permissions'));

    } catch (QueryException $e) {
        Log::error('Database query error in subbranchadminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest',
        ]);
        return view('superadmin.subbranchadmin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);
    } catch (\Exception $e) {
        Log::error('General error in subbranchadminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest',
        ]);
        return view('superadmin.subbranchadmin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}


   public function teammanagerAdminUserView(Request $request)
{
    try {
        // Verify database connection
        \DB::connection()->getPdo();
        Log::info('Database connection established for teammanagerAdminUserView');

        // Check authentication
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;
        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Fetch team_manager role
        $teamManagerRole = DB::table('roles')->where('name', 'team_manager')->first();
        if (!$teamManagerRole) {
            Log::warning('Team Manager role not found in roles table', [
                'user_id' => $user->id,
            ]);
            return view('superadmin.teammanager-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Team Manager role not found.',
            ]);
        }

        // Fetch permissions for "Team Manager" dropdown item
        $dropdownItemId = 4; // <-- adjust this based on your dropdown_items table
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched permissions for team manager user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permission_record' => $permissionRecord ? (array) $permissionRecord : null,
            'permissions' => $permissions,
            'user_id' => $user->id,
            'dropdown_item' => DB::table('dropdown_items')->where('id', $dropdownItemId)->first(),
        ]);

        // Fetch data
        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $teamManagerRole->id)->with(['role', 'branch'])->get();
        $branches = Branch::all();

        Log::info('Fetched team manager users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.teammanager-users', compact('users', 'branches', 'roles', 'permissions'));
    } catch (QueryException $e) {
        Log::error('Database query error in teammanagerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);
        return view('superadmin.teammanager-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);
    } catch (\Exception $e) {
        Log::error('General error in teammanagerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);
        return view('superadmin.teammanager-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}


    public function telecallerAdminUserView(Request $request)
{
    try {
        // Verify database connection
        \DB::connection()->getPdo();
        Log::info('Database connection established for telecallerAdminUserView');

        // Check authentication
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;
        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Fetch telecaller role
        $telecallerRole = DB::table('roles')->where('name', 'telecaller')->first();
        if (!$telecallerRole) {
            Log::warning('Telecaller role not found in roles table', [
                'user_id' => $user->id,
            ]);
            return view('superadmin.telecalleradmin-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Telecaller role not found.',
            ]);
        }

        // Fetch permissions for telecaller dropdown item
        $dropdownItemId = 5; // <-- Set actual dropdown_item_id for "Telecaller"
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched permissions for telecaller admin user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permission_record' => $permissionRecord ? (array) $permissionRecord : null,
            'permissions' => $permissions,
            'user_id' => $user->id,
            'dropdown_item' => DB::table('dropdown_items')->where('id', $dropdownItemId)->first(),
        ]);

        // Fetch users and related data
        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $telecallerRole->id)->with(['role', 'branch'])->get();
        $branches = Branch::all();

        Log::info('Fetched telecaller users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.telecalleradmin-users', compact('users', 'branches', 'roles', 'permissions'));
    } catch (QueryException $e) {
        Log::error('Database query error in telecallerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);
        return view('superadmin.telecalleradmin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);
    } catch (\Exception $e) {
        Log::error('General error in telecallerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);
        return view('superadmin.telecalleradmin-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}

public function AccountantAdminUserView(Request $request)
{
    try {
        // Verify DB connection
        \DB::connection()->getPdo();
        Log::info('Database connection established for AccountantAdminUserView');

        // Check auth
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;

        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Fetch accountant role
        $accountantRole = DB::table('roles')->where('name', 'accountant')->first();
        if (!$accountantRole) {
            Log::warning('Accountant role not found in roles table', [
                'user_id' => $user->id,
            ]);
            return view('superadmin.accountant-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Accountant role not found.',
            ]);
        }

        // Fetch permissions
        $dropdownItemId = 6; // 🔁 <-- Replace with actual dropdown_item_id for "Accountant"
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched permissions for accountant admin user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permissions' => $permissions,
            'user_id' => $user->id,
        ]);

        // Fetch user list + dependencies
        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $accountantRole->id)->with(['role', 'branch'])->get();
        $branches = Branch::all();

        Log::info('Fetched accountant users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.accountant-users', compact('users', 'branches', 'roles', 'permissions'));

    } catch (QueryException $e) {
        Log::error('Database query error in AccountantAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.accountant-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);
    } catch (\Exception $e) {
        Log::error('General error in AccountantAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.accountant-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}


public function EmployeeAdminUserView(Request $request)
{
    try {
        // Verify DB connection
        \DB::connection()->getPdo();
        Log::info('Database connection established for EmployeeAdminUserView');

        // Check authentication
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;

        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Fetch employee role
        $employeeRole = DB::table('roles')->where('name', 'employee')->first();
        if (!$employeeRole) {
            Log::warning('Employee role not found', ['user_id' => $user->id]);
            return view('superadmin.employee-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Employee role not found.',
            ]);
        }

        // Define dropdown item ID for employee (update as needed)
        $dropdownItemId = 7; // ⬅️ Use actual ID for "Employee" in dropdown_items table

        // Fetch permission record
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched employee permissions', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permissions' => $permissions,
            'user_id' => $user->id,
        ]);

        // Fetch all roles, users, and branches
        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $employeeRole->id)->with(['branch', 'role'])->get();
        $branches = Branch::all();

        Log::info('Fetched employee users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.employee-users', compact('users', 'branches', 'roles', 'permissions'));

    } catch (QueryException $e) {
        Log::error('Database query error in EmployeeAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.employee-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);

    } catch (\Throwable $e) {
        Log::critical('Unhandled error in EmployeeAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.employee-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}

public function CustomerAdminUserView(Request $request)
{
    try {
        // Verify DB connection
        \DB::connection()->getPdo();
        Log::info('Database connection established for CustomerAdminUserView');

        // Check authentication
        if (!Auth::check()) {
            Log::error('No authenticated user found');
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roleId = $user->role_id;

        Log::info('Authenticated user details', [
            'user_id' => $user->id,
            'role_id' => $roleId,
            'email' => $user->email,
        ]);

        // Fetch customer role
        $customerRole = DB::table('roles')->where('name', 'customer')->first();
        if (!$customerRole) {
            Log::warning('Customer role not found', ['user_id' => $user->id]);
            return view('superadmin.customer-users', [
                'users' => collect(),
                'branches' => collect(),
                'roles' => collect(),
                'permissions' => [
                    'can_create' => false,
                    'can_read' => false,
                    'can_update' => false,
                    'can_delete' => false,
                ],
                'error' => 'Customer role not found.',
            ]);
        }

        // Define dropdown_item_id for customer module
        $dropdownItemId = 8; // 🔁 Replace with actual dropdown_item_id for "Customer"

        // Fetch permissions
        $permissionRecord = DB::table('role_dropdown_permissions')
            ->where('role_id', $roleId)
            ->where('dropdown_item_id', $dropdownItemId)
            ->first();

        $permissions = $permissionRecord ? [
            'can_create' => (bool) $permissionRecord->can_create,
            'can_read' => (bool) $permissionRecord->can_read,
            'can_update' => (bool) $permissionRecord->can_update,
            'can_delete' => (bool) $permissionRecord->can_delete,
        ] : [
            'can_create' => false,
            'can_read' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        Log::info('Fetched permissions for customer admin user view', [
            'role_id' => $roleId,
            'dropdown_item_id' => $dropdownItemId,
            'permissions' => $permissions,
            'user_id' => $user->id,
        ]);

        // Fetch user data
        $roles = DB::table('roles')->get(['id', 'name']);
        $users = User::where('role_id', $customerRole->id)->with(['branch', 'role'])->get();
        $branches = Branch::all();

        Log::info('Fetched customer users and related data', [
            'user_count' => $users->count(),
            'role_count' => $roles->count(),
            'branch_count' => $branches->count(),
            'user_id' => $user->id,
        ]);

        return view('superadmin.customer-users', compact('users', 'branches', 'roles', 'permissions'));

    } catch (QueryException $e) {
        Log::error('Database query error in CustomerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.customer-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'Database error occurred. Please contact support.',
        ]);

    } catch (\Throwable $e) {
        Log::critical('Unhandled error in CustomerAdminUserView', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'none',
        ]);

        return view('superadmin.customer-users', [
            'users' => collect(),
            'branches' => collect(),
            'roles' => collect(),
            'permissions' => [
                'can_create' => false,
                'can_read' => false,
                'can_update' => false,
                'can_delete' => false,
            ],
            'error' => 'An unexpected error occurred. Please check the logs.',
        ]);
    }
}


   public function storeUser(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id', // correct key
            'branch_id' => 'nullable|exists:branches,branch_id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $validated['role_id'],
            'branch_id' => $validated['branch_id'] ?? null,
        ]);

        Log::info('User created', [
            'email' => $validated['email'],
            'user_id' => Auth::id() ?? 'guest',
        ]);

        return response()->json(['message' => 'User created successfully'], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error creating user', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest'
        ]);
        return response()->json([
            'message' => 'Error adding user',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function permissions(Request $request)
    {
        try {
            \DB::connection()->getPdo();
            $branches = Branch::all();
            $roles = User::distinct()->pluck('role')->filter()->toArray();
            $permissions = Permission::all();
            $matrix = [];
            foreach ($permissions as $permission) {
                foreach ($roles as $role) {
                    $matrix[$permission->permission_id][$role] = DB::table('role_permissions')
                        ->where('permission_id', $permission->permission_id)
                        ->where('role', $role)
                        ->exists();
                }
            }

            Log::info('Fetched permissions data', [
                'roles_count' => count($roles),
                'permissions_count' => $permissions->count(),
                'user_id' => Auth::id() ?? 'guest'
            ]);

            return view('superadmin.permissions', compact('branches', 'roles', 'permissions', 'matrix'));
        } catch (QueryException $e) {
            Log::error('Database query error fetching permissions', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return view('superadmin.permissions', [
                'branches' => collect(),
                'roles' => [],
                'permissions' => collect(),
                'matrix' => [],
                'error' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            Log::error('General error fetching permissions', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return view('superadmin.permissions', [
                'branches' => collect(),
                'roles' => [],
                'permissions' => collect(),
                'matrix' => [],
                'error' => 'An unexpected error occurred. Please check the logs.'
            ]);
        }
    }

    public function updatePermissions(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        try {
            Log::debug('Permissions request data', [
                'input' => $request->all(),
                'headers' => $request->headers->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);

            if (!$request->hasHeader('X-CSRF-TOKEN') && !$request->has('_token')) {
                throw new \Exception('CSRF token missing. Please include a valid CSRF token.');
            }

            \DB::connection()->getPdo();

            if (!Schema::hasTable('role_permissions')) {
                throw new \Exception('role_permissions table does not exist. Please run migrations.');
            }

            $validRoles = User::distinct()->pluck('role')->filter()->toArray();
            if (empty($validRoles)) {
                throw new \Exception('No valid roles found in users table.');
            }

            $data = $request->validate([
                'permissions' => ['required', 'array'],
                'permissions.*' => ['array'],
                'permissions.*.*' => ['boolean'],
            ]);

            DB::beginTransaction();

            Log::debug('Clearing existing permissions', ['table' => 'role_permissions']);
            DB::table('role_permissions')->delete();

            $inserts = [];
            foreach ($data['permissions'] as $role => $permissions) {
                if (!in_array($role, $validRoles)) {
                    continue; // Skip invalid roles
                }
                foreach ($permissions as $permissionId => $hasPermission) {
                    if ($hasPermission) {
                        Log::debug('Preparing permission insert', [
                            'role' => $role,
                            'permission_id' => $permissionId
                        ]);
                        $inserts[] = [
                            'role' => $role,
                            'permission_id' => (int)$permissionId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                Log::debug('Inserting permissions', ['count' => count($inserts)]);
                DB::table('role_permissions')->insert($inserts);
            }

            DB::commit();

            Log::info('Permissions updated successfully', [
                'user_id' => Auth::id() ?? 'guest',
                'roles_updated' => array_keys($data['permissions']),
                'insert_count' => count($inserts)
            ]);

            return response()->json([
                'message' => 'Permissions updated successfully',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error updating permissions', [
                'errors' => $e->errors(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 422);

        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database query error updating permissions', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'message' => 'Database error updating permissions',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error updating permissions', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'message' => 'Error updating permissions',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

   public function updateUser(Request $request, User $user)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'branch_id' => 'nullable|exists:branches,branch_id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        Log::info('User updated', [
            'email' => $user->email,
            'user_id' => Auth::id() ?? 'guest'
        ]);

        return response()->json(['message' => 'User updated successfully'], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error updating user', [
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id() ?? 'guest'
        ]);

        return response()->json([
            'message' => 'Error updating user',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function destroyUser(User $user)
    {
        try {
            $email = $user->email;
            $user->delete();
            Log::info('User deleted', [
                'email' => $email,
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id() ?? 'guest'
            ]);
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Permission view methods
    public function adminPermissionView()
    {
        $data = $this->getPermissionsForRole('admin');
        return view('admin.permissions', $data);
    }

    public function updateAdminPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'admin');
    }

    public function subadminPermissionView()
    {
        $data = $this->getPermissionsForRole('subadmin');
        return view('subadmin.permissions', $data);
    }

    public function updateSubadminPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'subadmin');
    }

    public function branchPermissionView()
    {
        $data = $this->getPermissionsForRole('branch');
        return view('admin.permissions', $data);
    }

    public function updateBranchPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'branch');
    }

    public function subBranchAdminPermissionView()
    {
        $data = $this->getPermissionsForRole('subbranchadmin');
        return view('admin.permissions', $data);
    }

    public function updateSubBranchAdminPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'subbranchadmin');
    }

    public function regionHeadPermissionView()
    {
        $data = $this->getPermissionsForRole('regionhead');
        return view('admin.permissions', $data);
    }

    public function updateRegionHeadPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'regionhead');
    }

    public function teamManagerPermissionView()
    {
        $data = $this->getPermissionsForRole('teammanager');
        return view('admin.permissions', $data);
    }

    public function updateTeamManagerPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'teammanager');
    }

    public function telecallerPermissionView()
    {
        $data = $this->getPermissionsForRole('telecaller');
        return view('admin.permissions', $data);
    }

    public function updateTelecallerPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'telecaller');
    }

    public function accountantPermissionView()
    {
        $data = $this->getPermissionsForRole('accountant');
        return view('admin.permissions', $data);
    }

    public function updateAccountantPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'accountant');
    }

    public function employeePermissionView()
    {
        $data = $this->getPermissionsForRole('employee');
        return view('admin.permissions', $data);
    }

    public function updateEmployeePermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'employee');
    }

    public function customerPermissionView()
    {
        $data = $this->getPermissionsForRole('customer');
        return view('admin.permissions', $data);
    }

    public function updateCustomerPermissions(Request $request)
    {
        return $this->updatePermissionsForRole($request, 'customer');
    }
}