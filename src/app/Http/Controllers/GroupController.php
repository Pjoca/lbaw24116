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
    $group = Group::with(['owner', 'memberships.user', 'membershipRequests.user'])
        ->findOrFail($id);

    $user = Auth::user();

    // Check if the authenticated user is a member of the group
    $isMember = GroupMembership::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->exists();

    // If not a member, restrict access to posts
    $posts = [];
    if ($isMember) {
        // Retrieve only posts with visibility = 'group' and id_group = $id
        $posts = $group->posts()->where('visibility', 'group')->where('id_group', $id)->get();
    }

    // Check if the authenticated user has a pending membership request
    $hasRequestedMembership = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->exists();

    return view('pages.group', compact('group', 'isMember', 'hasRequestedMembership', 'posts'));
}
}
