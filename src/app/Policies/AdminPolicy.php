<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can perform any action.
     */
    public function general(User $user)
    {
        return $user->isAdmin();
    }

}
