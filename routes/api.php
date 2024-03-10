<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

// $dire = __DIR__ . './Test/';

Route::prefix('v1/')->middleware('auth:sanctum')->group(function ()  {
    include __DIR__ . '\\Developers\\Users.php';
    include __DIR__ . '\\Developers\\Products.php';
    include __DIR__ . '\\Developers\\Categories.php';
    // include "{$dire}Products.php";
    // include "{$dire}Categories.php";
});

include __DIR__ . '\\Developers\\Auth.php';


