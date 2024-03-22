<?php

namespace App\Policies;

use App\Models\{Role,User};
use Illuminate\Auth\Access\Response;

class RolePolicy extends Before
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('update_role');
    }
}
