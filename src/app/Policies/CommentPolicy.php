<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function update(User $user, Comment $comment): bool
    {
        return $user->id == $comment->id_user;
    }
}
