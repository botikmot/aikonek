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
Route::get('/myprofile', [App\Http\Controllers\HomeController::class, 'myprofile'])->name('myprofile');

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
Route::get('/posts/{postId}/comments/{page}', [App\Http\Controllers\CommentController::class, 'getMoreComments'])->name('getMoreComments');

Route::post('/create-todo', [App\Http\Controllers\TodoController::class, 'createTodo'])->name('createTodo');
Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index'])->name('getTodos');
Route::delete('/remove-todo/{id}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('removeTodo');
Route::post('/complete-todo/{id}', [App\Http\Controllers\TodoController::class, 'completeTodo'])->name('completeTodo');

Route::post('/create-note', [App\Http\Controllers\NoteController::class, 'store'])->name('createNote');
Route::post('/update-note/{id}', [App\Http\Controllers\NoteController::class, 'updateNote'])->name('updateNote');
Route::get('/notes', [App\Http\Controllers\NoteController::class, 'index'])->name('getNotes');
Route::delete('/remove-note/{id}', [App\Http\Controllers\NoteController::class, 'destroy'])->name('removeNote');

Route::post('/create-event', [App\Http\Controllers\EventController::class, 'store'])->name('createEvent');
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('getEvents');


Route::get('/forms', [App\Http\Controllers\HomeController::class, 'forms'])->name('forms');
Route::post('/create-form', [App\Http\Controllers\FormController::class, 'store'])->name('createForm');


Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:cache');
    return '<h1>Cleared!!!</h1>';
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return '<h1>Migrated!!!</h1>';
});