<?php

use App\Models\Block;
use GuzzleHttp\Client;
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

    return view('welcome');
});

Route::get('login', function (){
    return response()->json('You need login first!', 403);
})->name('login');

Route::get('/test',function(){
    return view('test');
});

Route::post('/test','IndexController@index');
Route::get('/awtrix/COPPER','IndexController@copper');
Route::get('/awtrix/ALUMINUM','IndexController@aluminum');


