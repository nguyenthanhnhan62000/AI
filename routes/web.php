<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LawController;
use App\Http\Controllers\MiningController;
use App\Http\Controllers\LandPriceController;
use App\Http\Controllers\MiningCau1Controller;
use App\Http\Controllers\CertificatesLandController;

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
Route::get('/index',  [LandPriceController::class, 'index'])->name('index');
Route::post('/search_post', [LandPriceController::class, 'search_post'])->name('search_post');

//law--cau_1
Route::resource('/law', LawController::class);
Route::post('/law/post_search', [LawController::class, 'post_search']);

Route::get('law/content/crawl_content', [LawController::class, 'crawl_content']);

Route::post('law/post/index', [LawController::class, 'post_index']);

Route::post('law/post/show', [LawController::class, 'post_show']);

//cau 3 
Route::get('cau_3/search',[CertificatesLandController::class, 'search'])->name('cau_3.search');



//data mining
//cau 2
Route::get('/data_mining/data',[LawController::class, 'sendToDataMining']);
Route::post('/data_mining/post_test',[LawController::class, 'post_test']);
Route::get('/data_mining/test',[MiningController::class, 'test']);
Route::get('/data_mining/index',[MiningController::class, 'index']);
Route::get('/data_mining/test_data_mining',[MiningController::class, 'test_data_mining']);
Route::get('/data_mining/guess_mining',[MiningController::class, 'guess_mining']);
Route::post('/data_mining/index/post_test',[MiningController::class, 'post_test']);

//cau 1
Route::get('/data_mining/cau_1/index',[MiningCau1Controller::class, 'index']);
Route::post('/data_mining/cau_1/cluster_post',[MiningCau1Controller::class, 'cluster_post']);
Route::post('/data_mining/cau_1/data_post',[MiningCau1Controller::class, 'data_post']);
Route::post('/data_mining/cau_1/search',[MiningCau1Controller::class, 'search']);


