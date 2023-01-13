<?php

use App\Http\Controllers\CourierController;
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

    //route mengambil participant berdasarkan request
    Route::get("/listPackageByRequest","listPackageByRequest");

    //route untuk edit request
    Route::post('/updateRequest', "updateRequest");

    //route untuk add report
    Route::post('/addreport', "addReport");
    Route::post('/updatebatch', "updatebatch");
    Route::post('/deleteparticipant', "deleteparticipant");

});

// An API that handles the courier requests for the application.
Route::controller(CourierController::class)->group(function () {
    // A route to handle the courier requests for the requests in the database.
    Route::prefix('/requests')->group(function () {
        // Packages count API
        Route::get('/countAvailable', 'countAvailablePackets');
        Route::get('/countOngoing', 'countOngoingPackets');
        Route::get('/countCancelled', 'countCancelledPackets');
        Route::get('/countFinished', 'countFinishedPackets');

        // Packages manipulation APIe
        Route::get('/getAvailablePackets', 'getPendingPackets');
        Route::get('/getOngoingPackets', 'getOngoingPackets');
        Route::get('/getDeliveredPackets', 'getDeliveredPackets');
        Route::put('/takePackage', 'takePackage');
        Route::put('/updatePackageStatus', 'updatePacketStatus');
        Route::put('/cancelPackageDelivery', 'cancelPackageDelivery');

        // Package details
        Route::get('/getPacketDetail', 'getPacketDetail');
    });
});
