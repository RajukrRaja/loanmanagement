<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::with(['region', 'branch', 'subAdmin', 'employee', 'teamManager'])->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin,sub_admin,accountant,region_head,team_manager,telecaller,branch,sub_branch,employee,customer',
            'sub_admin_id' => 'nullable|exists:users,id',
            'region_id' => 'nullable|exists:regions,id',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'nullable|exists:users,id',
            'team_manager_id' => 'nullable|exists:users,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function show(string $id): JsonResponse
    {
        $user = User::with(['region', 'branch', 'subAdmin', 'employee', 'teamManager'])->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:super_admin,admin,sub_admin,accountant,region_head,team_manager,telecaller,branch,sub_branch,employee,customer',
            'sub_admin_id' => 'nullable|exists:users,id',
            'region_id' => 'nullable|exists:regions,id',
            'branch_id' => 'nullable|exists:branches,id',
            'employee_id' => 'nullable|exists:users,id',
            'team_manager_id' => 'nullable|exists:users,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}