<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use app\Models\GroupMembershipRequest;
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

    public function owner(User $user, Group $group)
    {
        return $user->id === $group->id_owner
            ? Response::allow()
            : Response::deny('You are not the owner of this group.');
    }
    
    public function updateInvite(User $user, Group $group, GroupMembershipRequest $request)
    {
        return $user->id === $group->id_owner && $request->is_invite
            ? Response::allow()
            : Response::deny('You are not the owner of this group.');
    }

    
}
