<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//routes :
// User API routes
Route::controller(UserController::class)->group(function () {
    //mengambil semua users
    Route::get('/listUsers', 'listUsers');
    //insert user baru
    Route::post('/register', 'insertUser');
    //update user
    Route::post('/updateUser', 'updateUser');

    Route::post('/login', 'loginUser');
});
