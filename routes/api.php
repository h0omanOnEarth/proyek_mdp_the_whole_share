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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//routes :
//mengambil semua users
Route::get('/listUsers', [UserController::class, "listUsers"]);
//mengambil semua locations
Route::get('/listLocations',[LocationController::class,"listLocations"]);

//insert user baru
Route::post('/register', [UserController::class, "insertUser"]);
//update user
Route::post('/updateUser',[UserController::class, "updateUser"]);
//insert location baru
Route::post('/insertLocation',[LocationController::class,"insertLocation"]);

