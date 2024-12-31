<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/reg', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ViewUser', [AuthController::class, 'ViewUser']);
Route::post('/profilepicture',[AuthController::class,'profile']);
Route::post('/checkpassword',[AuthController::class,'checkpassword']);
Route::put('/updatepassword',[AuthController::class,'updatepassword']);
Route::post('/createpost', [AuthController::class, 'create'])->name('createpost');

Route::resource('/test', MusicController::class);
