<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission',
        'allowed',
    ];

    public static function allowed(string $role, string $permission): bool
    {
        $override = static::query()
            ->where('role', $role)
            ->where('permission', $permission)
            ->value('allowed');

        if (!is_null($override)) {
            return (bool) $override;
        }

        return (bool) data_get(config('permissions.defaults'), "{$role}.{$permission}", false);
    }
}
