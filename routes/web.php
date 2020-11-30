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

    $tong = \App\Models\Awtrix::where('type', 0)->first();
    if($tong){
        //如果今天没更新 并且今天已经是9点50之后了
        if($today != $tong->date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($today.' 14:36:00'))){
            if(\Carbon\Carbon::now()->gt($tong->updated_at->addMinutes(10))){
                $client = new Client();
                $url = "https://m.smm.cn/get_old_price_chart?priceType=products&ID=201102250376&startTime={$month}&endTime={$today}";
                $response = $client->get($url);
                $data = json_decode($response->getBody()->getContents(), true);
                $lastPrice = array_pop($data['Data']);
                $tong = \App\Models\Awtrix::updateOrCreate(
                    ['type' => '0'],
                    [
                        'value' => $lastPrice['Average'],
                        'date' => $lastPrice['RenewDate'],
                        'updated_at' => \Carbon\Carbon::now()
                    ]
                );
            }
        }
    }else{
        $client = new Client();
        $url = "https://m.smm.cn/get_old_price_chart?priceType=products&ID=201102250376&startTime={$month}&endTime={$today}";
        $response = $client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);
        $lastPrice = array_pop($data['Data']);

        $tong = \App\Models\Awtrix::updateOrCreate(
            ['type' => '0'],
            [
                'value' => $lastPrice['Average'],
                'date' => $lastPrice['RenewDate'],
                'updated_at' => \Carbon\Carbon::now()
            ]
        );
    }

    return response()->json([
        'data'=>['text' => $tong->value]
    ]);
});


