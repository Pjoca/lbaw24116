<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// import Group model

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupMembershipRequest;

class GroupController extends Controller
{
/**
 * Display the group details page.
 */
public function show($id)
{
    // Load the group along with relationships
    $group = Group::with(['owner', 'memberships.user', 'membershipRequests.user'])
        ->findOrFail($id);

    $user = Auth::user();

    // Check if the authenticated user is a member of the group
    $isMember = GroupMembership::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->exists();

    // If not a member, restrict access to posts
    $posts = collect();
    if ($isMember) {
        $posts = $group->posts()
            ->where('visibility', 'group')
            ->where('id_group', $id)
            ->latest()
            ->get();
    }

    // Check if the authenticated user has a pending membership request
    $hasRequestedMembership = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->exists();

    // Fetch group membership requests and invites if the user is the owner of the group
    $joinRequests = collect();
    $invites = collect();
    if ($group->owner->id === $user->id) {
        $joinRequests = GroupMembershipRequest::where('id_group', $group->id)
            ->where('request_status', 'pending')
            ->where('is_invite', false) // Only join requests
            ->with('user') // Ensure 'user' relationship is loaded
            ->get();

        $invites = GroupMembershipRequest::where('id_group', $group->id)
            ->where('request_status', 'pending')
            ->where('is_invite', true) // Only invites
            ->with('user') // Ensure 'user' relationship is loaded
            ->get();
    }

    // Fetch members of the group excluding the group owner
    $members = $group->memberships()
        ->where('id_user', '!=', $group->id_owner) // Exclude the group owner
        ->with('user')
        ->get();

    return view('pages.group', compact(
        'group',
        'isMember',
        'hasRequestedMembership',
        'posts',
        'joinRequests',
        'invites',
        'members'
    ));
}


}
