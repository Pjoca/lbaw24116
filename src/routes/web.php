<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GroupMembershipController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\Auth\ForgotPasswordController;

use Illuminate\Http\Request;

use Pusher\Pusher;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/timeline/personalized');
    } else {
        return redirect('/timeline/public');
    }
});

// Independent Home Route
Route::get('/home', function () {
    return redirect(route('timeline.public'));
})->name('home');


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profile
Route::controller(UserController::class)->group(function () {
    Route::get('/profile/{id}', 'show')->name('profile.show');
    Route::get('/profile/{id}/edit', 'edit')->middleware('auth')->name('profile.edit');
    Route::put('/profile/{id}', 'update')->middleware('auth')->name('profile.update');
    Route::delete('/profile/{id}', 'delete')->name('profile.delete');
});

// Timeline Default Route
Route::get('/timeline', function () {
    if (auth()->check()) {
        return redirect()->route('timeline.personalized');
    }
    return redirect()->route('timeline.public');
})->name('timeline');


// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/timeline/public', 'publicTimeline')->name('timeline.public'); // View Public Timeline
    Route::get('/timeline/personalized', 'personalizedTimeline')->middleware('auth')->name('timeline.personalized'); // View Personalized Timeline
    Route::post('/pages', 'create')->name('posts.store'); // Rota para enviar o post
    Route::get('/posts/{id}', 'show')->name('posts.show');
    Route::put('/posts/{post}', 'update')->middleware('auth')->name('posts.update');
    Route::delete('/post/{id}', 'delete')->middleware('auth')->name('post.delete');
    Route::get('/pages/create-post', 'showCreateForm')->name('posts.create'); // Rota para mostrar o formulário de criação

});

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/users', [UserController::class, 'search'])->name('users.search');

//Admin
Route::controller(AdminController::class)->middleware('auth')->group(function () {
    Route::get('/admin', 'listUsers')->name('admin.panel');
    Route::put('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::post('/admin/store-user', 'storeUser')->name('admin.storeUser');   // Submit user creation form
    
});



Route::controller(FriendshipController::class)->middleware('auth')->group(function () {
        Route::post('/friends/add/{id}', 'add');
        Route::delete('/friends/remove/{id}', 'remove');
        Route::delete('/friends/cancel/{id}', 'cancel');
        Route::post('/friends/accept/{id}', 'accept');
        Route::delete('/friends/reject/{id}', 'reject');
        Route::get('/friends/requests/received/{userId}',  'getReceivedRequests')->name('friends.requests.received');
        Route::get('/friends/requests/sent/{userId}',  'getSentRequests')->name('friends.requests.sent');
    });



Route::controller(GroupMembershipController::class)->middleware('auth')->group(function () {
    Route::post('/groups/{id}/join', 'requestMembership')->name('groups.join');
    Route::delete('/groups/{id}/leave', 'leaveGroup')->name('groups.leave');
    Route::delete('/groups/{id}/cancel', 'cancelMembershipRequest')->name('groups.cancel');
    Route::post('/groups/request/{id}/accept', 'acceptMembershipRequest')->name('groups.acceptRequest');
    Route::post('/groups/request/{id}/reject', 'rejectMembershipRequest')->name('groups.rejectRequest');
    Route::post('/groups/{groupId}/invite', 'sendInvite')->name('groups.invite');
    Route::delete('/groups/invite/{inviteId}/cancel', 'cancelInvite')->name('groups.cancelInvite');
    Route::post('/groups/invite/{id}/accept', 'acceptInvite')->name('groups.acceptInvite');
    Route::post('/groups/invite/{id}/reject', 'rejectInvite')->name('groups.rejectInvite');
    Route::get('/groups/{groupId}/members', 'getMembers')->name('groups.getMembers');
    Route::delete('/groups/{groupId}/members/{memberId}', 'removeMember')->name('groups.removeMember');
    Route::get('/groups/{groupId}/join-requests',  'getJoinRequests')->name('groups.getJoinRequests');
    Route::get('/groups/{groupId}/invites', 'getInvites')->name('groups.getInvites');
    Route::get('/groups/invites/{userId}',  'getGroupInvites')->name('groups.invites');
});

Route::controller(GroupController::class)->middleware('auth')->group(function () {
    Route::get('/groups/{id}', 'show')->name('groups.show');
    Route::get('/groups', 'index')->name('groups.index');
    Route::post('/groups', 'store')->name('groups.store');
    Route::put('/groups/{id}',  'update')->name('groups.update');
});


Route::controller(NotificationController::class)->middleware('auth')->group(function () {
    Route::post('notifications/add', [NotificationController::class, 'addNotification']);
    Route::delete('notifications/delete/{id}', [NotificationController::class, 'deleteNotification']);
    Route::get('notifications/{id_user}', [NotificationController::class, 'getNotifications']);
});


Route::controller(ForgotPasswordController::class)->group( function () {
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
    Route::post('password/email',  'sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('password/reset', 'reset')->name('password.update');
});



Route::post('/broadcasting/auth', function (Request $request) {
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $userId = Auth::id();
    $channelName = $request->input('channel_name');

    // Regex for private-notification.<userId> or private-messages.<userId>.<contactId>
    if (!preg_match('/^private-(notification|messages)\.(\d+)(?:\.(\d+))?$/', $channelName, $matches)) {
        Log::info('Invalid channel name: ' . $channelName);
        return response()->json(['message' => 'Invalid channel name'], 403);
    }

    $channelType = $matches[1]; // "notification" or "messages"
    $channelUserId = (int) $matches[2]; // User ID for whom the channel is created
    Log::info('Channel user ID: ' . $channelUserId);
    $contactId = isset($matches[3]) ? (int) $matches[3] : null; // Contact ID (only for messages channel)
    Log::info('Contact ID: ' . $contactId);

    // Check if the authenticated user is authorized to access this channel
    if ($userId !== $channelUserId && $userId !== $contactId) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Configure the Pusher instance
    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => config('broadcasting.connections.pusher.options.useTLS', true),
            'host' => config('broadcasting.connections.pusher.options.host', '127.0.0.1'),
            'port' => config('broadcasting.connections.pusher.options.port', 6001),
            'scheme' => config('broadcasting.connections.pusher.options.scheme', 'http'),
        ]
    );

    // Authenticate the Pusher socket for the channel
    return $pusher->socket_auth($channelName, $request->input('socket_id'));
});




Route::middleware('auth')->group(function () {
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
});

Route::get('/messages/conversation/{contactId}', [MessageController::class, 'getConversation'])->name('messages.conversation');
Route::get('/messages/{contactId}', [MessageController::class, 'getMessages'])->name('messages.get');
Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
Route::post('/messages/start-conversation', [MessageController::class, 'startConversation'])->name('messages.startConversation');


Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');

// Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{postId}', [CommentController::class, 'store'])->name('comments.store');
Route::get('/posts/{postId}/comments', [PostController::class, 'getComments'])->name('comments.get');
Route::get('/posts/{postId}/comments/load/{offset}', [PostController::class, 'loadMoreComments'])->name('comments.loadMore');
Route::get('/posts/{postId}/comments/load/{offset}', [PostController::class, 'loadMoreComments']);


Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');

Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

Route::post('/posts/{post}/like-toggle', [PostController::class, 'toggleLike'])->middleware('auth');

Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth');

Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/matchmaking', [App\Http\Controllers\MatchmakingController::class, 'index'])->name('matchmaking.index');
    Route::post('/matchmaking/interact', [App\Http\Controllers\MatchmakingController::class, 'interact'])->name('matchmaking.interact');
});

Route::get('/posts/{postId}/comments', [CommentController::class, 'loadComments'])->name('comments.load')->middleware('auth');

Route::get('/matchmaking', [App\Http\Controllers\MatchmakingController::class, 'index'])->name('matchmaking.index');






