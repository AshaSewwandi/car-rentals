<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionManagementController extends Controller
{
    public function index()
    {
        $roles = ['admin', 'partner', 'customer'];
        $modules = config('permissions.modules', []);

        $matrix = [];
        foreach ($roles as $role) {
            foreach (array_keys($modules) as $permission) {
                $matrix[$role][$permission] = RolePermission::allowed($role, $permission);
            }
        }

        return view('permissions.index', compact('roles', 'modules', 'matrix'));
    }

    public function update(Request $request, string $role)
    {
        $allowedRoles = ['admin', 'partner', 'customer'];
        abort_unless(in_array($role, $allowedRoles, true), 404);

        $modules = array_keys(config('permissions.modules', []));

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($modules)],
        ]);

        $selected = $data['permissions'] ?? [];

        foreach ($modules as $permission) {
            RolePermission::updateOrCreate(
                ['role' => $role, 'permission' => $permission],
                ['allowed' => in_array($permission, $selected, true)]
            );
        }

        return back()->with('success', ucfirst($role) . ' permissions updated successfully.');
    }
}
