<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole {
    public function handle(Request $request, Closure $next, $role) {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userRole = Auth::user()->role->name ?? null;
        if ($userRole === $role) {
            return $next($request);
        }

        return response()->json([
            'error' => "Unauthorized: Role '" . json_encode(Auth::user()->role ?? null) . "' does not match required '$role'"
        ], 403);
    }
}