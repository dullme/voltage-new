<?php

namespace App\Console\Commands;

use App\Models\HarnessComponent;
use DB;
use Log;
use App\Models\Component;
use App\Models\Harness;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update harness and item';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::today()->subDay()->toDateString();//昨天
        $date = '2020-10-22'; //测试日期
        $bom = 'false'; //true 表示有 BOM 的 Item，false 表示没有 BOM 的 Item
        $client = new Client();
        $item = "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageItem";
        $response = $client->request('GET', $item, [
            'query' => [
                '$filter' => "Date gt {$date} and BOM eq {$bom}"
            ],
            'auth' => [
                'Administrator',
                'Welcome123456',
                'ntlm'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = collect($data['value'])->map(function ($item){
            return [
                'no' => $item['No'],
                'name' => $item['Description'],
                'part_type' => '2',
                'wire_size' => $item['Wire_Size'],
                'match_wire_size' => $item['Match_Wire_Size'],
                'currency' => '1',//币种
                'price' => '1',//价格
                'weight' => '1',//重量
                'created_at' => $item['Date'],
                'updated_at' => $item['Date'],
            ];
        });

        $component = Component::whereIn('no', $data->pluck('no'))->get(); //查询是否存在Item
        DB::beginTransaction();
        try {
            if($component->count()){ //存在Item 更新
                $update_data = $data->whereIn('no', $component->pluck('no'))->map(function ($item){
                    unset($item['created_at']);
                    return $item;
                });

                $updated = updateBatch('components', $update_data->toArray());//需要更新
                Log::info("更新{$updated}【Component】",$component->toArray());

                $need_created = $data->whereNotIn('no', $component->pluck('no')->toArray())->values()->toArray();
                Component::insert($need_created);//需要新增
                Log::info("新增".count($need_created)."【Component】",$need_created);
            }else{ //否则全部新增
                Component::insert($data->toArray());
                Log::info("新增{$data->count()}【Component】",$data->toArray());
            }
            DB::commit();
        }catch (\Exception $exception){
            Log::warning($exception->getMessage());
            DB::rollBack();
        }


        $this->updateHarness();
        echo 'SUCCESS';
    }

    public function updateHarness()
    {
        $date = Carbon::today()->subDay()->toDateString();//昨天
        $date = '2020-10-22'; //测试日期
        $bom = 'true'; //true 表示有 BOM 的 Item，false 表示没有 BOM 的 Item
        $client = new Client();
        $item = "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageItem";
        $response = $client->request('GET', $item, [
            'query' => [
                '$filter' => "Date gt {$date} and BOM eq {$bom}"
            ],
            'auth' => [
                'Administrator',
                'Welcome123456',
                'ntlm'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $data = collect($data['value'])->map(function ($item) use($client){
            $response = $client->request('GET', "http://49.233.206.202:8048/BC140/ODataV4/Company('Voltage')/VoltageBOM", [
                'query' => [
                    '$filter' => "itemno eq '{$item['No']}'"
                ],
                'auth' => [
                    'Administrator',
                    'Welcome123456',
                    'ntlm'
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            $now = Carbon::now()->toDateString();
            $data = collect($data['value'])->map(function ($item) use ($now){
                return [
                    'no' => $item['No'],
                    'part_type' => 2,
                    'length' => 1,
                    'quantity' =>1,
                    'details' => json_encode([['length'=>1,'quantity'=>1,'remarks'=>'22']]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            });

            return [
                'no' => $item['No'],
                'name' => $item['Description'],
                'show_name' => $item['Description'],
                'version' => $item['Version'],
                'min_length' => $item['Min_Length'],
                'max_length' => $item['Max_Length'],
                'have_fuse' => $item['Have_Fuse'],
                'fuse' => $item['Fuse'],
                'string' => $item['Strings'],
                'outlet_length' => $item['Outlet_Length'],
                'module' => $item['Module'],
                'created_at' => $item['Date'],
                'updated_at' => $item['Date'],
                'items' => $data
            ];
        });

        DB::beginTransaction();
        try{
            $harness = Harness::whereIn('no', $data->pluck('no'))->pluck('id', 'no')->toArray();
            $create_or_update = $data->map(function ($item)use($harness){
                $items = $item['items'];
                unset($item['items']);

                if(isset($harness[$item['no']])){ //如果存在该item
                    $create_or_update = ['new' => 0, 'update' => 1];
                    $harness_id = $harness[$item['no']];
                    Harness::where('id', $harness_id)->update($item);//更新item
                    HarnessComponent::where('harness_id', $harness[$item['no']])->delete(); //删除所有的关联
                }else{ //新增Item
                    $create_or_update = ['new' => 1, 'update' => 0];
                    $harness_id = Harness::insertGetId($item);
                }

                $component = Component::whereIn('no', $items->pluck('no'))->pluck('id', 'no')->toArray();
                if(count($component) != $items->count()){
                    throw new \Exception('系统中找不到对应的BOM-itemno:'.$item['no']);
                }

                $res = $items->map(function ($item) use($harness_id, $component){
                    $item['harness_id'] = $harness_id;
                    $item['component_id'] = $component[$item['no']];
                    unset($item['no']);
                    return $item;
                });
                HarnessComponent::insert($res->toArray()); //新增BOM

                return $create_or_update;
            });

            DB::commit();
            Log::info('新增'.$create_or_update->sum('new').',更新'.$create_or_update->sum('update').'【Harness】');
        }catch (\Exception $exception){
            Log::warning($exception->getMessage());
            DB::rollBack();
        }


    }
}
