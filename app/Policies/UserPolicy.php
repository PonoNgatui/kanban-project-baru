<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    protected function getUserPermissions($user)
    {
        return $user
            ->role()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name');
    }

    public function viewUserRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('view-users-and-roles')) {
            return true;
        }

        return false;
    }

    public function updateUserRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('manage-user-roles')) {
            return true;
        }

        return false;
    }
}
