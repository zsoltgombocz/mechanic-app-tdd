<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DatabaseRouteController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\MechanicController;
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

Route::middleware(['checkdb', 'auth', 'getworksheets'])->group(function () {
    Route::get('/', [MainPageController::class, 'index'])->name('Kezdőlap');
    Route::get('/mechanics', [MechanicController::class, 'index'])->name('Szerelők');
    Route::get('/mechanics/create', [MechanicController::class, 'create'])->name('Szerelő hozzáadása');
    Route::post('/mechanics/create', [MechanicController::class, 'store']);
    Route::get('/mechanics/{id}', [MechanicController::class, 'edit'])->name('Szerelő szerkesztése - ');
    Route::post('/mechanics/update/{id}', [MechanicController::class, 'update']);
    Route::post('/mechanics/delete/{id}', [MechanicController::class, 'destroy']);
    Route::post('/logout', [LoginController::class, 'destroy']);
});

Route::middleware(['checkdb', 'guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::get('/dbstatus', [DatabaseRouteController::class, 'index'])->name('Adatbázis kapcsolat');
