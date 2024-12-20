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

use App\Http\Controllers\NotificationController;

class FriendshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function add($id, NotificationController $notificationController)
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

        $sender = User::findOrFail($user->id);

        $notificationController->addNotification(new Request([
            'id_user' => $id,
            'message_content' => $sender->name . ' sent you a friend request.',
            'onclick_url' => '/profile/' . $user->id,
        ]));
    
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
        $userId = Auth::id();
        $friend = User::findOrFail($id);

        $friendRequest = FriendshipRequest::where('id_sender', $userId)
                                          ->where('id_receiver', $friend->id)
                                          ->first();
        if (!$friendRequest) {
            return response()->json(['error' => 'No friend request found for this user'], 404);
        }
        
        $friendRequest->delete();
    
        return response()->json(['success' => 'Friend request canceled'], 200);
    }
    

public function accept($senderId, NotificationController $notificationController)
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

            // Find the user who sent the friend request
            $sender = User::findOrFail($senderId);

            // Call addNotification method in the NotificationController
            $notificationController->addNotification(new Request([
                'id_user' => $senderId,
                'message_content' =>$sender->name . ' accepted your friend request.',
                'onclick_url' => '/profile/' . $senderId,
            ]));

            DB::commit();

            return response()->json(['success' => 'Friend request accepted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting friend request: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while accepting the friend request.'], 500);
        }
    }







public function reject($senderId, NotificationController $notificationController)
{
    try {
        $request = FriendshipRequest::where('id_sender', $senderId)
            ->where('id_receiver', Auth::id())
            ->first();

        if (!$request) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        $request->delete();

        $sender = User::findOrFail($senderId);

        $notificationController->addNotification(new Request([
            'id_user' => $senderId,
            'message_content' => $sender->name . ' rejected your friend request.',
        ]));

        return response()->json(['success' => 'Friend request rejected'], 200);
    } catch (\Exception $e) {
        Log::error('Error rejecting friend request: ' . $e->getMessage());

        return response()->json(['error' => 'An error occurred while rejecting the friend request.'], 500);
    }
}

public function getReceivedRequests(Request $request, $userId)
{
    $requests = FriendshipRequest::where('id_receiver', $userId)->paginate(5);
    return response()->json([
        'html' => view('partials.friend-request-renderer', ['requests' => $requests, 'type'=> 'received'])->render(),
        'hasMorePages' => $requests->hasMorePages(),
        'nextPageUrl' => $requests->nextPageUrl(),
    ]);
}

public function getSentRequests(Request $request, $userId)
{
    $requests = FriendshipRequest::where('id_sender', $userId)->paginate(5);
    return response()->json([
        'html' => view('partials.friend-request-renderer', ['requests' => $requests, 'type' => 'sent'])->render(),
        'hasMorePages' => $requests->hasMorePages(),
        'nextPageUrl' => $requests->nextPageUrl(),
    ]);
}


public function getFriends(Request $request, $userId)
{
    $perPage = $request->query('per_page', 5); // Default 10 friends per page
    $page = $request->query('page', 1); // Default page 1

    try {
        $friends = User::select('user.*')
            ->join('friendship', 'user.id', '=', 'friendship.id_user2')
            ->where('friendship.id_user1', $userId)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'html' => view('partials.friend-list', ['friends' => $friends])->render(),
            'current_page' => $friends->currentPage(),
            'hasMorePages' => $friends->hasMorePages(),
            'last_page' => $friends->lastPage(),
            'nextPageUrl' => $friends->nextPageUrl(),
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching friends: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json(['error' => 'An error occurred while fetching friends.'], 500);
    }
}





}
