<?php

use App\Http\Controllers\Api\v1\BoothController;
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

Route::get('booths', [BoothController::class, 'get']);
Route::get('booths/{booth_id}', [BoothController::class, 'show']);
