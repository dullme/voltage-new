<?php


namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;

class IndexController
{

    public function index(Request $request)
    {
        $id = $request->input('id'); //按票刷箱子
        $code = $request->input('code'); //按箱刷箱
        if($code && $id){ // 随机刷票和刷箱
            $ran = random_int(1,2);
            if($ran == 1){
                $url = "http://4pl.eportyun.com/wxPushCtnByBill?id={$id}&tagRebrush=0";
            }else {
                $url = "http://4pl.eportyun.com/wxPushCtnByCtn?code={$code}&tagRebrush=0";
            }
        }else if($id){ //只 按票刷箱子
            $url = "http://4pl.eportyun.com/wxPushCtnByBill?id={$id}&tagRebrush=0";
        }else if($code){ //只 按箱刷箱
            $url = "http://4pl.eportyun.com/wxPushCtnByCtn?code={$code}&tagRebrush=0";
        }else{
            return response()->json(['data'=>['message' => '请输入编号']]);
        }
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', $url, ['timeout' => 5]);

        return $res->getBody()->getContents();
    }

    public function copper()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $month = \Carbon\Carbon::today()->subMonth()->toDateString();

        $tong = \App\Models\Awtrix::where('type', 0)->first();
        if($tong){
            //如果今天没更新 并且今天已经是9点50之后了
            if($today != $tong->date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($today.' 09:50:00'))){
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
                            'vchange_rate' => $lastPrice['VchangeRateStr'],
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
                    'vchange_rate' => $lastPrice['VchangeRateStr'],
                    'date' => $lastPrice['RenewDate'],
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
        }
        $tt = $tong->date == $today ? 'T-' : '';
        return response()->json([
            'Global Quote'=>[
                '01. symbol' => $tt.'COP',
                '02. open' => '1764.5400',
                '03. high' => '1797.0100',
                '04. low' => '1764.5400',
                '05. price' => $tong->value,
                '06. volume' => '739507',
                '07. latest trading day' => $tong->date,
                '08. previous close' => '1764.1300',
                '09. change' => '22.8900',
                '10. change percent' => $tong->vchange_rate,
            ]
        ]);
    }

    public function aluminum()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $month = \Carbon\Carbon::today()->subMonth()->toDateString();

        $tong = \App\Models\Awtrix::where('type', 1)->first();
        if($tong){
            //如果今天没更新 并且今天已经是9点50之后了
            if($today != $tong->date && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($today.' 09:50:00'))){
                if(\Carbon\Carbon::now()->gt($tong->updated_at->addMinutes(10))){
                    $client = new Client();
                    $url = "https://m.smm.cn/get_old_price_chart?priceType=products&ID=201102250311&startTime={$month}&endTime={$today}";
                    $response = $client->get($url);
                    $data = json_decode($response->getBody()->getContents(), true);
                    $lastPrice = array_pop($data['Data']);
                    $tong = \App\Models\Awtrix::updateOrCreate(
                        ['type' => '1'],
                        [
                            'value' => $lastPrice['Average'],
                            'vchange_rate' => $lastPrice['VchangeRateStr'],
                            'date' => $lastPrice['RenewDate'],
                            'updated_at' => \Carbon\Carbon::now()
                        ]
                    );
                }
            }
        }else{
            $client = new Client();
            $url = "https://m.smm.cn/get_old_price_chart?priceType=products&ID=201102250311&startTime={$month}&endTime={$today}";
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);
            $lastPrice = array_pop($data['Data']);

            $tong = \App\Models\Awtrix::updateOrCreate(
                ['type' => '1'],
                [
                    'value' => $lastPrice['Average'],
                    'vchange_rate' => $lastPrice['VchangeRateStr'],
                    'date' => $lastPrice['RenewDate'],
                    'updated_at' => \Carbon\Carbon::now()
                ]
            );
        }
        $tt = $tong->date == $today ? 'T-' : '';
        return response()->json([
            'Global Quote'=>[
                '01. symbol' => $tt.'ALU',
                '02. open' => '1764.5400',
                '03. high' => '1797.0100',
                '04. low' => '1764.5400',
                '05. price' => $tong->value,
                '06. volume' => '739507',
                '07. latest trading day' => $tong->date,
                '08. previous close' => '1764.1300',
                '09. change' => '22.8900',
                '10. change percent' => $tong->vchange_rate,
            ]
        ]);
    }

    public function usd()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $month = \Carbon\Carbon::today()->subMonth()->toDateString();

        $tong = \App\Models\Awtrix::where('type', 2)->first();
        if($tong) {
            if ($today == $tong->date && \Carbon\Carbon::now()->lt($tong->updated_at->addHours(3))) {

                return response()->json([
                    'Global Quote'=>[
                        '01. symbol' => 'USD',
                        '02. open' => '1764.5400',
                        '03. high' => '1797.0100',
                        '04. low' => '1764.5400',
                        '05. price' => $tong->value,
                        '06. volume' => '739507',
                        '07. latest trading day' => $tong->date,
                        '08. previous close' => '1764.1300',
                        '09. change' => '22.8900',
                        '10. change percent' => $tong->vchange_rate,
                    ]
                ]);
            }
        }

        $client = new Client();
        $response = $client->get("http://api.currencylayer.com/live?access_key=3322148d44669a295496f68f479a9fcf&currencies=CNY");
        $data = json_decode($response->getBody()->getContents(), true);

        $tong = \App\Models\Awtrix::updateOrCreate(
            ['type' => '2'],
            [
                'value' => $data['quotes']['USDCNY'],
                'vchange_rate' => $data['quotes']['USDCNY'],
                'date' => $today,
                'updated_at' => \Carbon\Carbon::now()
            ]
        );

        return response()->json([
            'Global Quote'=>[
                '01. symbol' => 'T-USD',
                '02. open' => '1764.5400',
                '03. high' => '1797.0100',
                '04. low' => '1764.5400',
                '05. price' => $tong->value,
                '06. volume' => '739507',
                '07. latest trading day' => $tong->date,
                '08. previous close' => '1764.1300',
                '09. change' => '22.8900',
                '10. change percent' => $tong->vchange_rate,
            ]
        ]);

    }
}
