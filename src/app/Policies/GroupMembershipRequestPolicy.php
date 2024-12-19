<?php

namespace App\Policies;

use App\Models\GroupMembershipRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupMembershipRequestPolicy
{
    public function acceptRejectInvite(User $user, GroupMembershipRequest $request)
    {
        return $request->is_invite && $user->id !== null;
    }
}
