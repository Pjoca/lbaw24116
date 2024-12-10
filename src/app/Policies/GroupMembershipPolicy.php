<?php

namespace App\Policies;

use App\Models\GroupMembership;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupMembershipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function requests(User $user, Group $group){
        return $user->id == $group->id_owner;
    }
}
