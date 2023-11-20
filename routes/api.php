<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\ImageUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('token/validate', [AuthController::class, 'verifyToken']);


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('jwt.verify')->group(function () {
    // Route::get('users', [UserController::class, 'index']);
    Route::post('post', [PostController::class, 'store']);
    Route::post('/upload', [ImageUploadController::class, 'uploadImage']);
});

Route::get('users', [UserController::class, 'index']);

