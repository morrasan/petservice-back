<?php

use App\Http\Controllers\PetController;
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

Route::controller(PetController::class)->group(function () {

    Route::post('pet', 'store');

    Route::get('pet/find-by-status', 'index');

    Route::middleware(['check.pet.exists'])->group(function () {

        Route::post('pet/{petId}', 'update');

        Route::post('pet/{petId}/upload-image', 'uploadImage');

        Route::delete('pet/{petId}', 'destroy');

        Route::get('pet/{petId}', 'show');
    });
});
