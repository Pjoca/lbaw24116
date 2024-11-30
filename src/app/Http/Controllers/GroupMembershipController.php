<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\GroupMembershipRequest;

class GroupMembershipController extends Controller
{
    /**
     * Request to join a group.
     */
    public function requestMembership($groupId)
    {
        $user = Auth::user();
        $group = Group::findOrFail($groupId);

        // Check if the user is already a member or has a pending request
        if (GroupMembership::where('id_user', $user->id)->where('id_group', $groupId)->exists() ||
            GroupMembershipRequest::where('id_user', $user->id)->where('id_group', $groupId)->exists()) {
            return response()->json(['error' => 'Already a member or request pending'], 400);
        }

        // Create a membership request
        GroupMembershipRequest::create([
            'id_user' => $user->id,
            'id_group' => $groupId,
            'request_status' => 'pending',
            'requested_at' => now(),
        ]);

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
    public function acceptMembershipRequest($requestId)
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
    public function rejectMembershipRequest($requestId)
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
}
