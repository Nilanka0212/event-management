<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no specific roles required, proceed
        if (empty($roles)) {
            return $next($request);
        }

        $userRole = strtolower(trim((string) ($user->role ?? '')));
        $allowedRoles = array_map(fn($r) => strtolower(trim((string) $r)), $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. You do not have permission to access this resource.'
                ], 403);
            }

            abort(403, 'Unauthorized access. Only ' . implode(' or ', $roles) . ' can access this resource.');
        }

        return $next($request);
    }
}
