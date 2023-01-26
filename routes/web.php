<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/admins-only', function(){
    //Gate is created in AuthServiceProvider.php
    // if(Gate::allows('visitAdminPages')){
    //     return 'Admins only';
    // }

    // return "you cannot view this page, fucker";

    return 'Only admins here';
})->middleware('can:visitAdminPages'); //Using Gate as middleware; remove and uncomment out upper code to use in controller/inline-function in routing

//User related routes
//==========================================================
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('home');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

//Follow Related Routes
//=======================================================
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

//Blog post related routes
//========================================================
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth'); //Use this middleware so only logged in users trigger this route
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn'); //Custom middleware
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post'); //This middleware is controlling a policy
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

//Profile-related Routes
//=========================================================
Route::get('/profile/{user:username}', [UserController::class, 'profile']); //Adding ':username' to the variable forces to Laravel to look up by username instead of id
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);