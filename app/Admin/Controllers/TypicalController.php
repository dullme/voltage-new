<?php

namespace App\Admin\Controllers;

use App\Models\ComponentComb;
use App\Models\Harness;
use App\Models\Typical;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypicalController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Typical';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Typical());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'))->display(function ($name) {
            $url = url('/admin/typicals/' . $this->id);

            return "<a data-toggle='tooltip' data-placement='top' title='VT-【几个几串】-【组串长度最大值】-【电机长的和】-【版本号】' href='{$url}'>{$name}</a>";
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
//            $actions->disableDelete();
        });

        return $grid;
    }

    protected function detailInfo($id, Content $content)
    {
        $typical = Typical::findOrFail($id);
        $harnesses = collect($typical->harnesses_selected)->groupBy('id')->map(function ($item) {
            return [
                "checked"     => $item[0]['checked'],
                "id"          => $item[0]['id'],
                "max_length"  => $item[0]['max_length'],
                "min_length"  => $item[0]['min_length'],
                "name"        => $item[0]['name'],
                "remarks"     => $item[0]['remarks'],
                "string"      => $item[0]['string'],
                "remaining"   => $item[0]['remaining'],
                "harness_key" => $item[0]['harness_key'],
                "color"       => $item[0]['color'],
                "count"       => count($item)
            ];
        })->values();

        $fuse = [];
        if(isset($typical->fuse['res'])){
            foreach ($typical->fuse['check_list'] as $key=>$item){
                if($item['checked']){ //如果这行被选中
                    $fuse[] = $typical->fuse['res'][$key];
                }
            }
        }

        $nofuse = [];
        if(isset($typical->nofuse['res'])){
            foreach ($typical->nofuse['check_list'] as $key=>$item){
                if($item['checked']){ //如果这行被选中
                    $nofuse[] = $typical->nofuse['res'][$key];
                }
            }
        }

        $res_fuses = array_merge($fuse, $nofuse);
        $res_fuses = collect($res_fuses)->flatten(1)->groupBy('length')->map(function ($item){
            return [
                'length' => $item[0]['length'],
                'count' => count($item)
            ];
        })->values()->toArray();

        $combs = collect($typical->fuse['check_list'])->pluck('component_comb')->whereNotNull()->unique()->toArray();
        $component_combs = ComponentComb::whereIn('id', $combs)->get()->pluck('name', 'id');

        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body(view('admin.typical.detail', compact('typical', 'harnesses', 'res_fuses', 'component_combs')));
    }

    public function create(Content $content)
    {
        Admin::script(<<<EOF
            const app = new Vue({
                el: '#app'
            });
EOF
        );

        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body('<typical-create></typical-create>');
    }

    public function stageSave(Request $request)
    {
        $data = $request->all();
        $data['res'] = collect($data['stages'])->map(function ($item) {
            return $this->jiSuan($item);
        });

        $data['ids'] = collect($data['stages'])->flatten(1)->pluck('id')->unique()->filter()->values()->toArray();
        $data['check_list'] = [];
        for ($i = 0; $i < count($data['res']); $i++) {
            $data['check_list'][$i] = [
                'checked' => false,
                'component_comb' => '',
            ];
        }

        return $this->responseSuccess($data);
    }

    public function stageSubmit(Request $request)
    {
        $fuse = $request->input('fuse');
        $nofuse = $request->input('nofuse');
        if(isset($fuse['check_list'])){
            foreach ($fuse['check_list'] as $fuse){
                if($fuse['checked'] && $fuse['component_comb']==''){
                    return $this->setStatusCode(422)->responseError('请选择零件组合');
                }
            }
        }

        if(isset($nofuse['check_list'])){
            foreach ($nofuse['check_list'] as $nofuse){
                if($nofuse['checked'] && $nofuse['component_comb']==''){
                    return $this->setStatusCode(422)->responseError('请选择零件组合');
                }
            }
        }



        $data = $request->all();
        $str = collect($data['harnesses_selected'])->groupBy('string')->map(function ($item, $key) {
            return (string) count($item) . sprintf('%02d', $key);
        })->implode(''); //几个几串

        $max_length = sprintf('%03d', collect($data['harnesses_selected'])->max('max_length'));//组串长度最大值
        $fuse_motors = isset($data['fuse']['motors']) ? collect($data['fuse']['motors'])->sum('number') : 0;
        $nofuse_motors = isset($data['nofuse']['motors']) ? collect($data['nofuse']['motors'])->sum('number') : 0;
        $sum_motor = sprintf('%02d', $fuse_motors + $nofuse_motors);//电机长的和

        $data['show_name'] = "VT-{$str}-{$max_length}-{$sum_motor}";

        $typical = Typical::where('show_name', $data['show_name'])->orderBy('id', 'DESC')->first();
        $data['version'] = $version = sprintf('%02d', $typical ? $typical->version + 1 : 1);

        //VT-【几个几串】-【组串长度最大值】-【电机长的和】-【版本号】
        $data['name'] = "VT-{$str}-{$max_length}-{$sum_motor}-{$version}";
        $data['margin'] = intval($data['margin']);

        $res = Typical::create($data);

        return $this->responseSuccess($res);
    }

    private function jiSuan(array $data)
    {
        $ids = collect($data)->where('id', '!=', null)->pluck('id')->unique()->values()->toArray();
        $harness = Harness::whereIn('id', $ids)->get()->keyBy('id')->toArray();
        $res = [];
        $now_id = null;
        $now_harness_key = null;

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['only_motor'] == false) { //排除中间的电机
                if ($data[$i]['id'] == null) { //如果这个格子没有设置 Harness 则设置为 null
                    $now_id = null;
                } else { //设置了 Harness
                    if ($now_id == null || $now_id != $data[$i]['id'] || ($now_id == $data[$i]['id'] && $now_harness_key != $data[$i]['harness_key'])) { //这个ID是第一次出现（连续不断的）
                        $now_id = $data[$i]['id']; //标记当前的 Harness id
                        $now_harness_key = $data[$i]['harness_key']; //标记当前的 Harness key
                        if ($i != 0) { //除第一格就出现外其他情况都需要加长度
                            $length = 0;
                            for ($j = 0; $j < $i; $j++) {
                                if ($data[$j]['only_motor'] == false) { //如果是组件位置 需要加上组件长度
                                    $length += $harness[$data[$i]['id']]['max_length']; //加上组件长度
                                }
                                $length += $data[$j]['motor_number'];//加上电机长度
                            }
                            $res[] = [
                                'id'     => $data[$i]['id'],
                                'color'  => $data[$i]['color'],
                                'length' => $length,
                            ];
                        }
                    }
                }
            }
        }

        return $res;
    }
}
