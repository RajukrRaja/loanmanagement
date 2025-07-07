<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Models\RoleDropdownPermission;

class RoleDropdownPermissions
{
    public function handle(Request $request, Closure $next, $dropdownItemId = null)
    {
        // Resolve dropdown item ID from request or parameter
        $dropdownItemId = $request->input('dropdown_item_id') ?? $dropdownItemId;

        Log::info('Entering RoleDropdownPermissions middleware', [
            'user_id' => Auth::id() ?? 'guest',
            'role_id' => Auth::user()->role_id ?? null,
            'route' => $request->path(),
            'method' => $request->method(),
            'dropdown_item_id' => $dropdownItemId,
        ]);

        if (!Auth::check()) {
            Log::warning('Unauthorized access attempt: User not authenticated.');
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: User not authenticated.',
            ], 401);
        }

        $user = Auth::user();

        // Define dynamic gates
        $permissions = [
            'create' => 'can_create',
            'read' => 'can_read',
            'update' => 'can_update',
            'delete' => 'can_delete',
            'view-lead' => 'can_view_lead',
            'approve-kyc' => 'can_approve_kyc',
            'reject-kyc' => 'can_reject_kyc',
        ];

       foreach ($permissions as $ability => $column) {
    if (!Gate::has($ability)) {
        Gate::define($ability, function ($user, $itemId = null) use ($column, $dropdownItemId, $ability) {
            $itemId = $itemId ?? $dropdownItemId;

            if ($user->role_id == 1) {
                Log::info("Super Admin bypass: Ability {$ability}, user_id {$user->id}");
                return true;
            }

            if (!$itemId) {
                Log::warning("Missing dropdown_item_id for ability {$ability}, user_id {$user->id}");
                return false;
            }

            $allowed = RoleDropdownPermission::where([
                'role_id' => $user->role_id,
                'dropdown_item_id' => (int)$itemId,
            ])->value($column);

            Log::info("Gate check: {$ability} => " . ($allowed ? 'allowed' : 'denied'), [
                'role_id' => $user->role_id,
                'dropdown_item_id' => $itemId,
                'column' => $column,
            ]);

            return (bool) $allowed;
        });
    }
}


        return $next($request);
    }
}
