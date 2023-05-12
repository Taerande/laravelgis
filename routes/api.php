<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EdgeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\NodeEdgeController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();});

Route::get('cus_list', [CustomerController::class, 'index'])->name('user.index');
Route::get('adm_list', [AdminController::class, 'index'])->name('user.index');

Route::post('node',[NodeController::class, 'store'])->name('node.store');
Route::get('node',[NodeController::class, 'index'])->name('node.index');

Route::post('edge',[EdgeController::class, 'store'])->name('edge.store');
Route::get('edge',[EdgeController::class, 'index'])->name('edge.index');

Route::post('path',[NodeEdgeController::class, 'getPath'])->name('path.getPath');
Route::get('path',[NodeEdgeController::class, 'index'])->name('path.index');


Route::get('shop',[ShopController::class, 'index'])->name('shop.index');
Route::post('shop',[ShopController::class, 'store'])->name('shop.store');

Route::get('menu/{id}',[MenuController::class, 'index'])->name('menu.index');
Route::post('menu/{id}',[MenuController::class, 'store'])->name('menu.store');
