<?php

use App\Http\Controllers\Api\v1\EventController;
use App\Http\Controllers\FortuneWheelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function(){
//     return view('layouts.app');
// });

// Route::view('/welcome','welcome');

Route::get('/api/v1/event', [EventController::class, 'get'])->middleware([]);

Route::get('/meeting-hall', function(){
    return view('app.plugins.zoom');
});
Route::get('/pb', function(){
    return view('photobooth');
});
Route::get('/sponsor/{id}/fortune-wheel/token/{token}',[FortuneWheelController::class, 'index']);
Route::get('{any}', function () {
    return view('layouts.app');
})->where('any', '^(?!storage).*$');


