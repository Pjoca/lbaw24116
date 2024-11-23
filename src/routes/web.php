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
Route::controller(UserController::class)->middleware('auth')->group(function () {
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile.show');
    Route::put('/profile/{id}', 'update')->name('profile.update'); // Edit Profile
    
});

// Posts
Route::controller(PostController::class)->middleware('auth')->group(function () {
    Route::get('/timeline/public', 'publicTimeline')->name('timeline.public'); // View Public Timeline
    Route::get('/timeline/personalized', 'personalizedTimeline')->name('timeline.personalized'); // View Personalized Timeline
    Route::post('/post', 'create')->name('post.create'); // Create Post
    Route::put('/post/{id}', 'update')->name('post.update'); // Edit Post
    Route::delete('/post/{id}', 'delete')->name('post.delete'); // Delete Post
});

Route::controller(AdminController::class)->middleware('auth')->group(function () {
    Route::get('/admin/users', 'listUsers')->name('admin.users'); // List Users
    Route::get('/admin/users/{id}', 'showUser')->name('admin.users.show'); // View User
    Route::get('/admin/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.editUser');
    Route::put('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::get('/admin/create-user', 'createUser')->name('admin.createUser'); // Page to create a user
    Route::post('/admin/store-user', 'storeUser')->name('admin.storeUser');   // Submit user creation form
    
});

Route::get('/test-post', function () {
    return view('tests.post');
});

Route::get('/test-updatePost', function () {
    return view('tests.update_post');
});

Route::get('/test-adminUpdate', function () {
    return view('tests.adminUpdate');
});


Route::get('/pages/create-post', [PostController::class, 'showCreateForm'])->name('posts.create'); // Rota para mostrar o formulário de criação

Route::post('/pages', [PostController::class, 'create'])->name('posts.store'); // Rota para enviar o post

Route::get('/pages/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // Formulário de 

Route::put('/pages/{post}', [PostController::class, 'update'])->name('posts.update'); // Atualização do post

Route::get('/user/posts', [PostController::class, 'userPosts'])->name('posts.userPosts')->middleware('auth');























