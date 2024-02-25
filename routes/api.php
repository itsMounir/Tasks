<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/')->group(function () {
    Route::apiResource('user', UserController::class)->names([
        'index' => 'users.index',
        'store' => 'users.store',
        'show' => 'users.show',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    Route::apiResource('product', ProductController::class)->names([
        'index' => 'products.index',
        'store' => 'products.store',
        'show' => 'products.show',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    Route::apiResource('category', CategoryController::class)->names([
        'index' => 'categories.index',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});

// Route::delete('image',[CategoryController::class,'deleteImage']);

