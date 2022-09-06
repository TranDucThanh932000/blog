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
    Route::get('/menu-child', [MenuController::class, 'getAllMenuChildren']);
    Route::get('/', [MenuController::class, 'getAllMenu']);
    Route::middleware(['auth:api', 'can:add_menu'])->post('/', [MenuController::class, 'addMenu']);
});

Route::prefix('/blog')->group( function(){
    Route::get('/list-blog', [BlogController::class, 'getBlogAcceptedByPaginate']);
    Route::middleware(['auth:api', 'can:accept_blog'])->get('/list-blog-unaccept', [BlogController::class, 'getBlogUnacceptByPaginate']);
    Route::middleware(['auth:api', 'can:accept_blog'])->get('/preview/{id}', [BlogController::class, 'getBlogPreview']);
    Route::middleware(['auth:api', 'can:accept_blog'])->post('/accept', [BlogController::class, 'acceptBlog']);
    Route::middleware(['auth:api', 'can:delete_blog'])->delete('/cancel/{id}', [BlogController::class, 'cancelBlog']);
    Route::get('/{id}', [BlogController::class, 'getBlog']);
    Route::middleware(['auth:api', 'can:add_blog'])->post('/store', [BlogController::class, 'saveBlog']);
});

Route::prefix('/user')->group(function(){
    Route::middleware(['auth:api'])->get('/check-ability/{ability}', [UserController::class, 'checkAbility']);
    Route::middleware(['auth:api'])->get('/current-user', [UserController::class, 'getCurrentUser']);
    Route::get('/{id}', [UserController::class, 'getUserById']);
    Route::post('/store', [UserController::class, 'createUser']);
});

Route::post('/login', [UserController::class, 'login']);
Route::middleware(['auth:api'])->post('/logout', [UserController::class, 'logout']);

Route::get('/sidebar-admin', [MenuController::class, 'getSidebarAdminpage']);
