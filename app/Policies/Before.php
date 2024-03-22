<?php

namespace App\Policies;

class Before
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(\App\Models\User $user): bool|null
    {
        return $user->isOwner();
    }
}
