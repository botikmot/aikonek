<?php

use Illuminate\Support\Facades\Route;

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

/* Route::get('/', function () {
    return view('home');
}); */

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/create-post', [App\Http\Controllers\PostController::class, 'createPost'])->name('createPost');
Route::get('/posts', [App\Http\Controllers\PostController::class, 'getPosts'])->name('getPosts');
Route::post('/create-comment/{postId}', [App\Http\Controllers\CommentController::class, 'makeComment'])->name('makeComment');
Route::post('/delete-comment/{id}', [App\Http\Controllers\CommentController::class, 'deleteComment'])->name('deleteComment');
Route::post('/edit-comment/{id}', [App\Http\Controllers\CommentController::class, 'editComment'])->name('editComment');
Route::delete('/delete-post/{id}', [App\Http\Controllers\PostController::class, 'deletePost'])->name('deletePost');
Route::get('/follow/{id}', [App\Http\Controllers\FollowerController::class, 'follow'])->name('follow');
Route::get('/unfollow/{id}', [App\Http\Controllers\FollowerController::class, 'unfollow'])->name('unfollow');
Route::post('/request-friend', [App\Http\Controllers\FriendController::class, 'requestFriend'])->name('requestFriend');
Route::post('/accept-friend', [App\Http\Controllers\FriendController::class, 'acceptFriend'])->name('acceptFriend');
Route::post('/unfriend', [App\Http\Controllers\FriendController::class, 'unFriend'])->name('unFriend');
Route::get('/friends', [App\Http\Controllers\FriendController::class, 'getFriends'])->name('getFriends');
Route::post('/post/{id}/like', [App\Http\Controllers\PostController::class, 'likePost'])->name('likePost');
Route::get('/suggested-friends', [App\Http\Controllers\FriendController::class, 'suggestedFriends'])->name('suggestedFriends');