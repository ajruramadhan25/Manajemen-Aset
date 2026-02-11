<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        $normalize = function ($role) {
            $role = strtolower(trim((string) $role));
            return str_replace(' ', '_', $role);
        };

        $allowed = array_map($normalize, explode(',', $roles));
        $role = $normalize($user->role ?? 'karyawan');

        if ($role === 'super_admin') {
            return $next($request);
        }

        if (!in_array($role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
