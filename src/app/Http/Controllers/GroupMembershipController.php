<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupMembershipRequest;
use App\Models\User;

use App\Http\Controllers\NotificationController;

class GroupMembershipController extends Controller
{
    /**
     * Request to join a group.
     */
    public function requestMembership($groupId, NotificationController $notificationController)
    {
        $user = Auth::user();
        $group = Group::findOrFail($groupId);

        // Check if the user is already a member or has a pending request
        if (GroupMembership::where('id_user', $user->id)->where('id_group', $groupId)->exists()) {
            return response()->json(['error' => 'You are already a member of this group'], 400);
        }

        if (GroupMembershipRequest::where('id_user', $user->id)->where('id_group', $groupId)->exists()) {
            return response()->json(['error' => 'Membership request is already pending'], 400);
        }

        if ($group->visibility === 'public') {
            // Automatically create the membership if the group is public
            GroupMembership::create([
                'id_user' => $user->id,
                'id_group' => $groupId,
                'joined_at' => now(),
            ]);

            return response()->json(['success' => 'You have successfully joined the group', 'reload' => true], 200);
        }

        // Create a membership request for private groups
        GroupMembershipRequest::create([
            'id_user' => $user->id,
            'id_group' => $groupId,
            'request_status' => 'pending',
            'requested_at' => now(),
        ]);

        // Notify the group owner
        $notificationController->addNotification(new Request([
            'id_user' => $group->id_owner,
            'message_content' => $user->name . ' has requested to join your group ' . $group->name,
            'onclick_url' => 'javascript:goWithCommand(\'/groups/' . $group->id . '\', "openModal(\'join-requests-modal\'); loadInitialJoinRequests(' . $group->id . ')")',
        ]));

        return response()->json(['success' => 'Membership request sent successfully'], 200);
    }

    /**
     * Cancel a pending membership request.
     */
    public function cancelMembershipRequest($groupId)
    {
        $user = Auth::user();

        $request = GroupMembershipRequest::where('id_user', $user->id)
            ->where('id_group', $groupId)
            ->where('request_status', 'pending')
            ->first();

        if (!$request) {
            return response()->json(['error' => 'No pending membership request found'], 404);
        }

        $request->delete();

        return response()->json(['success' => 'Membership request canceled'], 200);
    }

    /**
     * Accept a membership request.
     */
    public function acceptMembershipRequest($requestId, NotificationController $notificationController)
    {
        DB::beginTransaction();

        try {
            $request = GroupMembershipRequest::findOrFail($requestId);
            $group = Group::findOrFail($request->id_group);

            // Ensure only the group owner can accept requests
            $this->authorize('requests', [GroupMembership::class, $group]);

            // Add the user to the group
            GroupMembership::create([
                'id_user' => $request->id_user,
                'id_group' => $request->id_group,
                'joined_at' => now(),
            ]);

            // Delete the request
            $request->delete();

            $notificationController->addNotification(new Request([
                'id_user' => $request->id_user,
                'message_content' => 'Your membership request to join ' . $group->name . ' has been accepted',
                'onclick_url' => 'groups/' . $group->id,
            ]));

            DB::commit();

            return response()->json(['success' => 'Membership request accepted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting membership request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while accepting the membership request.'], 500);
        }
    }

    /**
     * Reject a membership request.
     */
    public function rejectMembershipRequest($requestId, NotificationController $notificationController)
    {
        try {
            $request = GroupMembershipRequest::findOrFail($requestId);
            $group = Group::findOrFail($request->id_group);

            // Ensure only the group owner can reject requests
            if ($group->id_owner !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Delete the request
            $request->delete();

            $notificationController->addNotification(new Request([
                'id_user' => $request->id_user,
                'message_content' => 'Your membership request to join ' . $group->name . ' has been rejected',
            ]));

            return response()->json(['success' => 'Membership request rejected'], 200);
        } catch (\Exception $e) {
            Log::error('Error rejecting membership request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while rejecting the membership request.'], 500);
        }
    }

    /**
     * Leave a group.
     */
    public function leaveGroup($groupId)
    {
        $user = Auth::user();

        // Find the membership using composite keys
        $membership = GroupMembership::where('id_user', $user->id)
            ->where('id_group', $groupId)
            ->first();

        if (!$membership) {
            return response()->json(['error' => 'You are not a member of this group'], 404);
        }

        // Perform the delete operation manually
        GroupMembership::where('id_user', $user->id)
            ->where('id_group', $groupId)
            ->delete();

        return response()->json(['success' => 'You have left the group successfully'], 200);
    }

    public function sendInvite(Request $request, $groupId, NotificationController $notificationController)
    {
        $group = Group::findOrFail($groupId);
    
        // Ensure only the group owner can send invites
        if ($group->id_owner !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $validated = $request->validate([
            'usernameOrEmail' => 'required|string', // Validate the username or email
        ]);
    
        // Find the user by username or email
        $user = User::where('username', $validated['usernameOrEmail'])
                    ->orWhere('email', $validated['usernameOrEmail'])
                    ->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        // Check if the user is already a member or has a pending request
        if (GroupMembership::where('id_user', $user->id)->where('id_group', $groupId)->exists() ||
            GroupMembershipRequest::where('id_user', $user->id)->where('id_group', $groupId)->exists()) {
            return response()->json(['error' => 'User is already a member or has a pending request'], 400);
        }
    
        // Create an invite request
        $invite = GroupMembershipRequest::create([
            'id_user' => $user->id,
            'id_group' => $groupId,
            'request_status' => 'pending',
            'requested_at' => now(),
            'is_invite' => true, // Mark as an invite
        ]);

        // Notify the user
        $notificationController->addNotification(new Request([
            'id_user' => $user->id,
            'message_content' => 'You have been invited to join ' . $group->name,
            'onclick_url' => 'groups/' . $groupId,
        ]));
    
        return response()->json([
            'success' => 'Invite sent successfully',
            'invite_id' => $invite->id,
            'username' => $user->name, // Return the user's name
        ]);
    }
    

public function cancelInvite($inviteId)
{
    try {
        $invite = GroupMembershipRequest::findOrFail($inviteId);

        // Ensure only the group owner can cancel the invite
        if ($invite->is_invite && $invite->group->id_owner !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $invite->delete();

        return response()->json(['success' => 'Invite canceled successfully'], 200);
    } catch (\Exception $e) {
        Log::error('Error canceling invite: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while canceling the invite.'], 500);
    }
}

public function acceptInvite($inviteId, NotificationController $notificationController)
{
    $invite = GroupMembershipRequest::findOrFail($inviteId);

    if (!$invite->is_invite || $invite->id_user !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    DB::beginTransaction();

    try {
        GroupMembership::create([
            'id_user' => $invite->id_user,
            'id_group' => $invite->id_group,
            'joined_at' => now(),
        ]);

        $invite->delete();

        DB::commit();

        $notificationController->addNotification(new Request([
            'id_user' => $invite->id_group,
            'message_content' => Auth::user()->name . ' has accepted your invite to join ' . $invite->group->name,
            'onclick_url' => 'groups/' . $invite->id_group,
        ]));

        return response()->json(['success' => 'Invite accepted successfully'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error accepting invite: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while accepting the invite.'], 500);
    }
}

public function rejectInvite($inviteId, NotificationController $notificationController)
{
    $invite = GroupMembershipRequest::findOrFail($inviteId);

    if (!$invite->is_invite || $invite->id_user !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $invite->delete();

    $notificationController->addNotification(new Request([
        'id_user' => $invite->id_group,
        'message_content' => Auth::user()->name . ' has rejected your invite to join ' . $invite->group->name,
        'onclick_url' => 'groups/' . $invite->id_group,
    ]));

    return response()->json(['success' => 'Invite rejected successfully'], 200);
}

public function removeMember($groupId, $memberId)
{
    Log::info('Group ID: ' . $groupId);
    // Ensure the group exists
    $group = Group::findOrFail($groupId);

    Log::info('Authenticated User ID: ' . Auth::id());
    Log::info('Group Owner ID: ' . $group->id_owner);

    // Check if the authenticated user is the group owner
    if ($group->id_owner !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Find the membership using the composite key
    $membership = GroupMembership::where('id_user', $memberId)
        ->where('id_group', $groupId)
        ->first();

    if (!$membership) {
        return response()->json(['error' => 'Membership not found'], 404);
    }

    // Delete the membership
    $membership->delete();

    return response()->json(['success' => 'Member removed successfully'], 200);
}

public function getMembers(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);

    $members = GroupMembership::where('id_group', $groupId)
        ->with('user') // Assuming the GroupMembership model has a 'user' relationship
        ->orderBy('joined_at', 'desc')
        ->simplePaginate(5); // Load 10 members at a time

    return response()->json([
        'html' => view('partials.members-renderer', ['members' => $members, 'group' => $group])->render(),
        'hasMorePages' => $members->hasMorePages(),
        'nextPageUrl' => $members->nextPageUrl()
    ]);
}


public function getJoinRequests(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);

    $user = Auth::user();
    // Ensure the user is the group owner
    if ($group->id_owner !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Query join requests (pending status)
    $query = GroupMembershipRequest::where('id_group', $groupId)
        ->where('request_status', 'pending')
        ->where('is_invite', false) // Only join requests, not invites
        ->with('user')
        ->orderBy('requested_at', 'desc'); // Assuming requested_at tracks the join request time

    $joinRequests = $query->simplePaginate(5);

    return response()->json([
        'html' => view('partials.join-requests-renderer', compact('joinRequests', 'group'))->render(),
        'hasMorePages' => $joinRequests->hasMorePages(),
        'nextPageUrl' => $joinRequests->nextPageUrl()
    ]);

    
}


public function getInvites(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);

    $user = Auth::user();
    // Ensure the user is the group owner
    if ($group->id_owner !== $user->id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Query pending invites
    $query = GroupMembershipRequest::where('id_group', $groupId)
        ->where('request_status', 'pending')
        ->where('is_invite', true) // Only invites, not join requests
        ->with('user')
        ->orderBy('requested_at', 'desc'); // Assuming requested_at tracks the invite time

    $invites = $query->simplePaginate(5);

    return response()->json([
        'html' => view('partials.invites-renderer', compact('invites', 'group'))->render(),
        'hasMorePages' => $invites->hasMorePages(),
        'nextPageUrl' => $invites->nextPageUrl()
    ]);
}



}
