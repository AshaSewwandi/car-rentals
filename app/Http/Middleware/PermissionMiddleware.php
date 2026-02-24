<?php

namespace App\Http\Middleware;

use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!RolePermission::allowed($user->role, $permission)) {
            abort(403, 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
