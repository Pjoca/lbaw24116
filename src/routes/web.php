<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\PostController;


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
    return view('home'); // Ensure a corresponding 'home.blade.php' exists
})->name('home');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});


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
    Route::post('/post', 'create')->middleware('auth')->name('post.create'); // Create Post
    Route::put('/posts/{post}', 'update')->middleware('auth')->name('posts.update');
    Route::delete('/post/{id}', 'delete')->middleware('auth')->name('post.delete');

});

// Search
Route::get('/search/users', [UserController::class, 'search'])->name('users.search');

//Admin
Route::controller(AdminController::class)->middleware('auth')->group(function () {
    Route::get('/admin', 'listUsers')->name('admin.panel');
    Route::get('/admin/users', 'listUsers')->name('admin.users'); // List Users
    Route::get('/admin/users/{id}', 'showUser')->name('admin.users.show'); // View User
    Route::get('/admin/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::put('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/admin/create-user', 'createUser')->name('admin.createUser'); // Page to create a user
    Route::post('/admin/store-user', 'storeUser')->name('admin.storeUser');   // Submit user creation form
    
});



Route::get('/pages/create-post', [PostController::class, 'showCreateForm'])->name('posts.create'); // Rota para mostrar o formulário de criação

Route::post('/pages', [PostController::class, 'create'])->name('posts.store'); // Rota para enviar o post























