<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
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

    public function viewAnyRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('view-any-roles')) {
            return true;
        }

        return false;
    }

    public function createNewRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('create-new-roles')) {
            return true;
        }

        return false;
    }

    public function updateAnyRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('update-any-roles')) {
            return true;
        }

        return false;
    }

    public function deleteAnyRole($user)
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('delete-any-roles')) {
            return true;
        }

        return false;
    }
}
