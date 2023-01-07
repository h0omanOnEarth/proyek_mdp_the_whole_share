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
    //update user
    Route::post('/updateUser', 'updateUser');
    //update user kalau ngga ngehash :
    Route::post('/updateUserNoHash','updateUserNoHash');

    // Memverifikasi user ada di DB dan credentials yang diberikan sesuai.
    Route::post('/login', 'loginUser');

    // Menregistrasikan user apabila semua credentials sudah sesuai peraturan.
    Route::post('/register', 'registerUser');

    //mengambil semua requests
    Route::get('/listRequest', 'listRequest');

    //insert request baru
    Route::post('/addrequest', 'addrequest');

    //list news
    Route::get('/listNews','listNews');

    //route insert new participant pada donasi user
    Route::post('/insertParticipant','insertParticipant');

    //route untuk list participants
    Route::get('/listParticipants','listParticipants');

    //route list participants user yang sedang login
    Route::get('/listMyParticipants','listMyParticipants');

    //route ambil user
    Route::get('/getUser','getUser');

    //route untuk mendapatkan list lokasi yang masih tidak expired deadline e
    Route::get('/listLocationsUser','listLocationsUser');

    //route untuk mendapatkan list request atau lokasi yang sudah expired
    Route::get('/listLocationExpired','listLocationExpired');

    //route untuk edit status participant yang sudah expired jadi auto cannceled
    Route::post('/updateStatusParticipants','updateStatusParticipants');
});
