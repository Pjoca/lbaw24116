<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class NotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function delete(User $user, Notification $notification): Response
    {
        return $user->id === $notification->id_user
            ? Response::allow()
            : Response::deny('You do not have permission to perform this action.');
    }

    public function addAndGet(User $user): Response
    {
        return Auth::check()
            ? Response::allow()
            : Response::deny('You do not have permission to perform this action.');
    }
}
