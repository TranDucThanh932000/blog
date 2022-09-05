<?php

use App\Http\Controllers\api\v1\MenuController;
use App\Http\Controllers\api\v1\BlogController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/menu')->group( function(){
    Route::get('/', [MenuController::class, 'getAllMenu']);
});

Route::prefix('/blog')->group( function(){
    Route::get('/{id}', [BlogController::class, 'getBlog']);
    Route::post('/', [BlogController::class, 'saveBlog']);
});

Route::prefix('/user')->group(function(){
    Route::get('/{id}', [UserController::class, 'getUserById']);
    Route::post('/store', [UserController::class, 'createUser']);
});

Route::post('/login', [UserController::class, 'login']);
Route::middleware(['auth:api'])->post('/logout', [UserController::class, 'logout']);
