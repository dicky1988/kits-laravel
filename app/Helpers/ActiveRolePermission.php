<?php

use Spatie\Permission\Models\Permission;

if (!function_exists('canActiveRole')) {
    function canActiveRole(string $permission): bool
    {
        $user = auth()->user();

        if (!$user || !$user->active_role_id) {
            return false;
        }

        return $user->roles
            ->where('id', $user->active_role_id)
            ->first()
            ?->hasPermissionTo($permission) ?? false;
    }
}
