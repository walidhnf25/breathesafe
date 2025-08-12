<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\DetectionImageController;
use App\Http\Controllers\NumberOfViolationsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerakun'])->name('registerakun');
Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('proseslogin');
Route::get('/proseslogout', [AuthController::class, 'proseslogout'])->name('proseslogout');

Route::middleware(['auth:user', 'checkRole:Administrator,User'])->group(function () {
    Route::get('/live-stream', [LiveStreamController::class, 'index'])->name('live-stream');
    Route::get('/original-image-location', [OriginalImageController::class, 'location'])->name('original-image-location');
    Route::get('/original-image-location/original-image/{id}', [OriginalImageController::class, 'index'])->name('original-image');
    Route::get('/detection-image-location', [DetectionImageController::class, 'location'])->name('detection-image-location');
    Route::get('/detection-image-location/detection-image/{id}', [DetectionImageController::class, 'index'])->name('detection-image');
    Route::get('/number-of-violations', [NumberOfViolationsController::class, 'index'])->name('number-of-violations');
});

Route::middleware(['auth:user', 'checkRole:Administrator'])->group(function () {
    Route::get('/location', [LocationController::class, 'index'])->name('location');
    Route::post('/location', [LocationController::class, 'store'])->name('location.store');
    Route::put('/location/update/{id}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location/destroy/{id}', [LocationController::class, 'destroy'])->name('location.destroy');

    Route::get('/camera', [CameraController::class, 'index'])->name('camera');
    Route::post('/camera', [CameraController::class, 'store'])->name('camera.store');
    Route::put('/camera/update/{id}', [CameraController::class, 'update'])->name('camera.update');
    Route::delete('/camera/destroy/{id}', [CameraController::class, 'destroy'])->name('camera.destroy');
});