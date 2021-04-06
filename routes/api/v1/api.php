<?php

use App\Http\Controllers\Api\v1\BoothController;
use App\Http\Controllers\Api\v1\GuestController;
use App\Http\Controllers\Api\v1\GuestEventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "API Routes";
});

Route::get('test', function () {
    return "Success!";
});

Route::post('/login', [HomeController::class, 'login']);
Route::middleware('auth:api')->post('/registration', [UserController::class, 'storeRegistration']);
Route::middleware('auth:api')->get('/users', [UserController::class, 'allUsers']);

// Route::get('user', [UserController::class, 'user']);
Route::get('user', [UserController::class, 'user']);

Route::get('booths', [BoothController::class, 'get']);
Route::get('booths/{booth_id}', [BoothController::class, 'show']);
Route::get('booths/{booth_id}/questionnaire', [BoothController::class, 'showQuestionnaire']);
Route::post('booths/{booth_id}/message', [BoothController::class, 'storeMessage']);
Route::post('booths/{booth_id}/questionnaire/answer/submit', [BoothController::class, 'storeQuestionnaireAnswerSubmit']);

Route::get('/guests/zoom/join/config', [GuestController::class, 'zoomJoin']);
Route::get('/guests/booths/tracker', [GuestController::class, 'boothTracker']);
Route::post('/guests/event/push', [GuestEventController::class, 'push']);

