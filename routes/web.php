<?php

use App\Models\Block;
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
    $quotation = \App\Models\Quotation::with('blocks')->find(4);
    $total_typical= $quotation->blocks->pluck('total_typical');
    $all_block = collect($quotation->typical)->map(function ($item, $key) use($total_typical){
        return [
            'typical_id' => $item,
            'count' => $total_typical->sum($key),
        ];
    });
dd($all_block);

    return view('welcome');

});

Route::get('/test', function () {

    return view('test');

});
Route::post('/test', 'IndexController@index');
