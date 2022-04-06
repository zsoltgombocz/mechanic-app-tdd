<?php

use App\Http\Controllers\Auth\LoginController;
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

Route::get('/', [MainPageController::class, 'index'])->middleware('auth')->middleware('injectdata')->name('Kezdőlap');
Route::get('/mechanics', [MechanicController::class, 'index'])->middleware('auth')->middleware('injectdata')->name('Szerelők');


Route::get('/login', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth');
