<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// import Group model

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupMembershipRequest;
use Log;

class GroupController extends Controller
{
/**
 * Display the group details page.
 */
public function show($id)
{
    // Load the group along with relationships
    $group = Group::with(['owner'])->findOrFail($id);

    $user = Auth::user();

    // Check if the authenticated user has a pending membership request
    $hasRequestedMembership = GroupMembershipRequest::where('id_user', $user->id)
        ->where('id_group', $group->id)
        ->where('request_status', 'pending')
        ->exists();

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

    // Fetch members of the group excluding the group owner
    $members = $group->memberships()
        ->where('id_user', '!=', $group->id_owner) // Exclude the group owner
        ->with('user')
        ->orderBy('joined_at', 'desc') // Use joined_at for ordering
        ->simplePaginate(5);

    $hasMoreMembers = $members->hasMorePages();

    // Fetch join requests for the group (only accessible by the owner)
    $joinRequests = collect();
    $hasMoreJoinRequests = false;

    // Fetch invites for the group (only accessible by the owner)
    $invites = collect();
    $hasMoreInvites = false;

    if ($group->id_owner === $user->id) {
        $joinRequestsQuery = GroupMembershipRequest::where('id_group', $group->id)
            ->where('request_status', 'pending')
            ->where('is_invite', false) // Only join requests
            ->with('user')
            ->orderBy('requested_at', 'desc');

        $joinRequests = $joinRequestsQuery->simplePaginate(5);
        $hasMoreJoinRequests = $joinRequests->hasMorePages();

        $invitesQuery = GroupMembershipRequest::where('id_group', $group->id)
            ->where('request_status', 'pending')
            ->where('is_invite', true) // Only invites
            ->with('user')
            ->orderBy('requested_at', 'desc');

        $invites = $invitesQuery->simplePaginate(5);
        $hasMoreInvites = $invites->hasMorePages();
    }

    return view('pages.group', compact(
        'group',
        'isMember',
        'posts',
        'members',
        'hasMoreMembers',
        'hasRequestedMembership',
        'joinRequests',
        'hasMoreJoinRequests',
        'invites',
        'hasMoreInvites'
    ));
}

public function index(Request $request)
{
    if ($request->ajax()) {
        $groups = Group::orderBy('created_at', 'desc')->paginate(4);
        $html = '';

        // Loop through each group and pass it to the group-card partial
        foreach ($groups as $group) {
            $html .= view('partials.group-card', ['group' => $group])->render();
        }

        return response()->json(['html' => $html]);
    }

    $groups = Group::orderBy('created_at', 'desc')->paginate(12);
    return view('pages.groups', compact('groups'));
}



}
