<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Post;
use App\Models\Message;
use App\Models\Notification;


use App\Models\User;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\GroupPolicy;
use App\Policies\GroupMembershipPolicy;



// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Message::class => MessagePolicy::class,
        User::class => UserPolicy::class,
        Notification::class => NotificationPolicy::class,
        Group::class => GroupPolicy::class,
        GroupMembership::class => GroupMembershipPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
