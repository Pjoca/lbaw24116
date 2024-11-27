<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\FriendshipRequest;
use App\Models\Friendship;

class FriendshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add($id)
    {
        $user = Auth::user();
        $friend = User::findOrFail($id);
    
        // Check if a friend request already exists or they are already friends
        if (FriendshipRequest::where('id_sender', $user->id)
                ->where('id_receiver', $friend->id)
                ->exists() || $user->isFriend($friend)) {
            return response()->json(['error' => 'Friend request already sent or users are already friends'], 400);
        }
    
        // Create a new friendship request
        FriendshipRequest::create([
            'id_sender' => $user->id,
            'id_receiver' => $friend->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);
    
        return response()->json(['success' => 'Friend request sent successfully'], 200);
    }

    public function remove($id)
    {
        DB::beginTransaction();

        try {
            $authUserId = Auth::id();

            // Check if a bidirectional friendship exists
            $friendshipAtoB = Friendship::where('id_user1', $authUserId)
                ->where('id_user2', $id)
                ->exists();

            $friendshipBtoA = Friendship::where('id_user1', $id)
                ->where('id_user2', $authUserId)
                ->exists();

            if (!$friendshipAtoB && !$friendshipBtoA) {
                return response()->json(['error' => 'Friendship not found'], 404);
            }

            // Remove bidirectional friendship
            Friendship::removeFriendship($authUserId, $id);

            DB::commit();

            return response()->json(['success' => 'Friendship removed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing friendship: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while removing the friendship.'], 500);
        }
    }

public function cancel($id)
{
    $user = Auth::user();
    $friend = User::findOrFail($id);

    if (!$user->hasRequestedFriendship($friend)) {
        return response()->json(['error' => 'Action not allowed'], 400);
    }

    $user->requestedFriends()->detach($friend->id);

    return response()->json(['success' => 'Friend request canceled'], 200);
}

public function accept($senderId)
{
    DB::beginTransaction();

    try {
        // Find the friendship request
        $request = FriendshipRequest::where('id_sender', $senderId)
            ->where('id_receiver', Auth::id())
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        // Create bidirectional friendships
        Friendship::createFriendship(Auth::id(), $senderId);

        // Delete the friend request
        $request->delete();

        DB::commit();

        return response()->json(['success' => 'Friend request accepted'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error accepting friend request: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while accepting the friend request.'], 500);
    }
}







public function reject($senderId)
{
    try {
        // Find the friendship request by sender and receiver
        $request = FriendshipRequest::where('id_sender', $senderId)
            ->where('id_receiver', Auth::id())
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        // Delete the friend request
        $request->delete();

        return response()->json(['success' => 'Friend request rejected'], 200);
    } catch (\Exception $e) {
        Log::error('Error rejecting friend request: ' . $e->getMessage());

        return response()->json(['error' => 'An error occurred while rejecting the friend request.'], 500);
    }
}


}
