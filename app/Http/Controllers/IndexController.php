<?php


namespace App\Http\Controllers;


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
}
