<?php

namespace App\Policies;

use App\Models\GroupMembershipRequest;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view another user's profile.
     * Access: USR
     */
    public function view(User $currentUser, User $profileUser): bool
    {
        // Any authenticated user can view profiles
        return true;
    }

    /**
     * Determine if the user can update their own profile.
     * Access: OWN
     */
    public function update(User $currentUser, User $profileUser): bool
    {
        // Only allow updating their own profile
        return $currentUser->id === $profileUser->id || $currentUser->isAdmin();
    }


    public function show(User $currentUser, User $user): bool
    {
        return $user->visibility === 'public'  || !is_null($currentUser);
    }

    public function startConversation(User $user, User $friend)
    {   
        return $user->id !== $friend->id && $friend->exists;
    }

}
