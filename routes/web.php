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
    $month = \Carbon\Carbon::today()->subMonth()->toDateString();
    $client = new Client();
    $url = "https://m.smm.cn/get_old_price_chart?priceType=products&ID=201102250376&startTime={$month}&endTime={$today}";
    $response = $client->get($url);
    $data = json_decode($response->getBody()->getContents(), true);
    $lastPrice = array_pop($data['Data']);
    $highs = $lastPrice['Highs'];
    $low = $lastPrice['Low'];
    $average = $lastPrice['Average'];
    $vchange = $lastPrice['Vchange'];
    $renew_date = $lastPrice['RenewDate'];
    
    return response()->json([
        'data'=>['text' => "$highs~$low $average +$vchange $renew_date"]
    ]);
});


