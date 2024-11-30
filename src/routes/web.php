<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendshipController;

use App\Http\Controllers\MessageController;


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
Route::redirect('/', '/login');

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
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}/edit', [UserController::class, 'edit'])->middleware('auth')->name('profile.edit');
    Route::put('/profile/{id}', 'update')->middleware('auth')->name('profile.update'); // Edit Profile
    
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
    });
    
Route::middleware('auth')->group(function () {
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
});

Route::get('/messages/conversation/{contactId}', [MessageController::class, 'getConversation'])->name('messages.conversation');
Route::get('/messages/{contactId}', [MessageController::class, 'getMessages']);