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


Route::get('/awtrix',function(){
    $today = \Carbon\Carbon::today()->toDateString();
    $client = new Client();
    $response = $client->get('https://hq.smm.cn/ajax/spot/history/201102250376/'.$today.'/'.$today);
    $data = json_decode($response->getBody()->getContents(), true);

    $lastPrice = array_pop($data['data']);
    $highs = $lastPrice['highs'];
    $low = $lastPrice['low'];
    $average = $lastPrice['average'];
    $vchange = $lastPrice['vchange'];
    $renew_date = $lastPrice['renew_date'];
    return response()->json([
        'data'=>['text' => "$highs~$low $average +$vchange $renew_date"]
    ]);
});


