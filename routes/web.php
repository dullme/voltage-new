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

    $typical = \App\Models\Typical::whereIn('id', $quotation->typical)->get();
    $harnesses_items = \App\Models\Harness::all()->pluck('no', 'id');

    $harnesses = $typical->map(function ($item) use($harnesses_items) {
        $harnesses_selected = collect($item->harnesses_selected)->groupBy('id')->map(function ($item) use($harnesses_items) {
            return [
                "id"          => $item[0]['id'],
                "no"          => $harnesses_items[$item[0]['id']],
                "count"       => count($item)
            ];
        })->values()->toArray();

        return $harnesses_selected;
    });

    $data = [];
    foreach ($harnesses as $harness){
        foreach ($harness as $item){
            isset($data[$item['no']]) ? $data[$item['no']] += $item['count'] : $data[$item['no']] = $item['count'];
        }
    }
    $data = collect($data)->map(function ($item, $key){
        return [
            'no' => $key,
            'count' => $item
        ];
    })->values();

    $typicals = $typical->pluck('res_fuses')->flatten(1)->groupBy('component_comb')->map(function ($item){
        return $item->pluck('attributes')->flatten(1)->groupBy('length')->map(function ($i) use($item){
            return [
                'no' => $item[0]['component_comb'],
                'length' => $i[0]['length'],
                'count' => $i->sum('count'),

            ];
        })->values();
    })->values()->flatten(1);

    return response()->json([
        'harness' => $data,
        'extenders' => $typicals,
        'whips' => $typicals,
    ]);
//    return view('welcome');

});

Route::get('/test', function () {

    return view('test');

});
Route::post('/test', 'IndexController@index');
