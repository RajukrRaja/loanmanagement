<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $credentials['password'] === $user->password) {
            Auth::login($user, $request->filled('remember'));

            // Fetch role name from the related Role model
            $roleName = $user->role->name ?? null;

            switch ($roleName) {
                case 'super_admin':
                    return redirect()->route('superadmin.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'sub_admin':
                    return redirect()->route('subadmin.dashboard');
                case 'branch':
                    return redirect()->route('branch.dashboard');
                case 'sub_branch_admin':
                    return redirect()->route('subbranchadmin.dashboard');
                case 'region_head':
                    return redirect()->route('regionhead.dashboard');
                case 'team_manager':
                    return redirect()->route('teammanager.dashboard');
                case 'telecaller':
                    return redirect()->route('telecaller.dashboard');
                case 'accountant':
                    return redirect()->route('accountant.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                case 'customer':
                    return redirect()->route('customer.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['role' => 'Unauthorized role.']);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }




    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // stored as plain text
            'role' => 'super_admin',
        ]);

        Auth::login($user);

        return redirect()->route('superadmin.dashboard');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:super_admin,admin,sub_admin,branch,sub_branch_admin,region_head,team_manager,telecaller,accountant,employee,customer'],
            'sub_admin_id' => ['nullable', 'integer', 'exists:users,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'employee_id' => ['nullable', 'integer', 'exists:users,id'],
            'team_manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // stored as plain text
            'role' => $validated['role'],
            'sub_admin_id' => $validated['sub_admin_id'],
            'region_id' => $validated['region_id'],
            'branch_id' => $validated['branch_id'],
            'employee_id' => $validated['employee_id'],
            'team_manager_id' => $validated['team_manager_id'],
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:super_admin,admin,sub_admin,branch,sub_branch_admin,region_head,team_manager,telecaller,accountant,employee,customer'],
            'sub_admin_id' => ['nullable', 'integer', 'exists:users,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'employee_id' => ['nullable', 'integer', 'exists:users,id'],
            'team_manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ?? $user->password, // plain text
            'role' => $validated['role'],
            'sub_admin_id' => $validated['sub_admin_id'],
            'region_id' => $validated['region_id'],
            'branch_id' => $validated['branch_id'],
            'employee_id' => $validated['employee_id'],
            'team_manager_id' => $validated['team_manager_id'],
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function superAdminDashboard()
    {
        $users = User::select('id', 'name', 'email', 'role', 'sub_admin_id', 'region_id', 'branch_id', 'employee_id', 'team_manager_id', 'created_at')->get();
        $subAdmins = User::where('role', 'sub_admin')->select('id', 'name')->get();
        $regions = \App\Models\Region::select('id', 'name')->get();
        $branches = \App\Models\Branch::select('id', 'name')->get();
        $employees = User::where('role', 'employee')->select('id', 'name')->get();
        $teamManagers = User::where('role', 'team_manager')->select('id', 'name')->get();

        return view('dashboards.superadmin', compact('users', 'subAdmins', 'regions', 'branches', 'employees', 'teamManagers'));
    }

    public function adminDashboard()
    {
        return view('dashboards.admin');
    }

    public function subAdminDashboard()
    {
        try {
            $subAdmins = User::where('role', 'sub_admin')->get();
            if ($subAdmins->isEmpty()) {
                Log::info('No sub-admins found for subAdminDashboard');
                $subAdmins = collect(); // fallback
            }
            return view('dashboards.subadmin', compact('subAdmins'));
        } catch (\Exception $e) {
            Log::error('Error in subAdminDashboard: ' . $e->getMessage());
            return view('dashboards.subadmin', ['subAdmins' => collect()])->with('error', 'Unable to load sub-admins.');
        }
    }

    public function branchDashboard()
    {
        return view('dashboards.branch');
    }

    public function subBranchAdminDashboard()
    {
        return view('dashboards.subbranchadmin');
    }

    public function regionHeadDashboard()
    {
        return view('dashboards.regionhead');
    }

    public function teamManagerDashboard()
    {
        return view('dashboards.teammanager');
    }

    public function telecallerDashboard()
    {
        return view('dashboards.telecaller');
    }

    public function accountantDashboard()
    {
        return view('dashboards.accountant');
    }

    public function employeeDashboard()
    {
        return view('dashboards.employee');
    }

    public function customerDashboard()
    {
        return view('dashboards.customer');
    }
}
