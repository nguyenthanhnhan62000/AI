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
// cau_2
Route::get('/search',  [LandPriceController::class, 'search'])->name('search');
Route::post('/search_post', [LandPriceController::class, 'search_post'])->name('search_post');

//law--cau_1
Route::resource('/law', LawController::class);
Route::post('/law/post_search', [LawController::class, 'post_search']);

Route::get('law/content/crawl_content', [LawController::class, 'crawl_content']);

Route::post('law/post/index', [LawController::class, 'post_index']);

Route::post('law/post/show', [LawController::class, 'post_show']);
