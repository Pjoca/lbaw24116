<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class PostPolicy
{
    /**
     * Determine if a post can be created by the authenticated user.
     * Access: USR
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create a post
    }

    /**
     * Determine if the user can update the post.
     * Access: OWN
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->id_user || $user->isAdmin(); // Only the post owner can update or admin or group admin
    }

    /**
     * Determine if the user can delete the post.
     * Access: OWN
     */
    public function delete(User $user, Post $post)
    {
        // Only the owner of the post can delete it
        return $user->id === $post->user->id || $user->isAdmin();
    }

    
}
