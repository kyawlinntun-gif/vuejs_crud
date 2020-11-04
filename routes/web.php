<?php

use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// StoreItem
Route::post('/storeItem', [\App\Http\Controllers\ListsController::class, 'store']);

Route::get('getItems', [\App\Http\Controllers\ListsController::class, 'index']);

Route::delete('/deleteItem', [\App\Http\Controllers\ListsController::class, 'destroy']);

Route::match(['put', 'patch'], '/storeItem', [\App\Http\Controllers\ListsController::class, 'update']);
