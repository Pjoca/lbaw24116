<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function edit(User $user, Group $group)
    {
        return $user->id === $group->id_owner
            ? Response::allow()
            : Response::deny('You are not the owner of this group.');
    }

    
}
