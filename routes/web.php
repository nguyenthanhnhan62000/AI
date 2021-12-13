<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LawController;
use App\Http\Controllers\LandPriceController;

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

Route::get('/search',  [LandPriceController::class, 'search'])->name('search');

Route::post('/search_post', [LandPriceController::class, 'search_post'])->name('search_post');

Route::resource('/law', LawController::class);
