<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\CommentsController;



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/reg', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ViewUser', [UserController::class, 'ViewUser']);
Route::post('/profilepicture',[UserController::class,'profile']);
Route::post('/checkpassword',[UserController::class,'checkpassword']);
Route::put('/updatepassword',[UserController::class,'updatepassword']);
Route::post('/createpost', [PostController::class, 'store'])->name('createpost');
Route::get('/displaypost',[PostController::class, 'show'])->name('displaypost');
Route::resource('/test', MusicController::class);
Route::get('/likes/{postId}/{userId}', [LikeController::class, 'getLikes']);
Route::resource('/likes', LikeController::class);
Route::resource('/comments', CommentsController::class);
Route::resource('/searchusers', FriendsController::class);
Route::post('/sendFriendRequest',[FriendsController::class, 'create']);
Route::get('/friendRequests', [FriendsController::class, 'show']);
Route::post('/respondToFriendRequest', [FriendsController::class, 'update']);
Route::get('/comments/{postId}', [CommentsController::class, 'show']);