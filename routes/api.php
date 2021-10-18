<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

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

Route::prefix('files')->group(function(){
    Route::middleware(['CustomAuth'])->group(function () {
        //Route::post('/upload',[\App\Http\Controllers\UploadController::class,'UploadController@store']);
        Route::post('/upload',[\App\Http\Controllers\UploadController::class,'store']);
        Route::post('/delete{id}',[\App\Http\Controllers\UploadController::class,'deleteFiles']);
        Route::post('/download',[\App\Http\Controllers\UploadController::class,'downloadFile']);
        Route::get('/all',[\App\Http\Controllers\UploadController::class,'viewAllFiles']);
        Route::post('/search{id}',[\App\Http\Controllers\SearchController::class,'search']);

    });
});

Route::post('/register',[RegisterController::class,'registerUser']);
Route::post('/login',[LoginController::class,'login']);
