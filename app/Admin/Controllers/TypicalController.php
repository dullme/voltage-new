<?php

namespace App\Admin\Controllers;

use App\Models\Harness;
use App\Models\Typical;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

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
        $grid->column('harness_id', __('Harness id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
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
        $data['res'] = collect($data['stages'])->map(function ($item){
            return $this->jiSuan($item);
        });

        return $this->responseSuccess($data);
    }

    private function jiSuan(array $data)
    {
        $ids = collect($data)->where('id', '!=', null)->pluck('id')->unique()->values()->toArray();
        $harness = Harness::whereIn('id', $ids)->get()->keyBy('id')->toArray();
        $res = [];
        $now_id = null;
        $now_harness_key = null;

        for($i = 0; $i< count($data); $i++){
            if($data[$i]['only_motor'] == false){ //排除中间的电机
                if($data[$i]['id'] == null){ //如果这个格子没有设置 Harness 则设置为 null
                    $now_id = null;
                }else{ //设置了 Harness
                    if($now_id == null || $now_id != $data[$i]['id'] || ($now_id == $data[$i]['id'] && $now_harness_key != $data[$i]['harness_key'])){ //这个ID是第一次出现（连续不断的）
                        $now_id = $data[$i]['id']; //标记当前的 Harness id
                        $now_harness_key = $data[$i]['harness_key']; //标记当前的 Harness key
                        if($i != 0){ //除第一格就出现外其他情况都需要加长度
                            $length = 0;
                            for($j = 0; $j < $i; $j++){
                                if($data[$j]['only_motor'] == false){ //如果是组件位置 需要加上组件长度
                                    $length += $harness[$data[$i]['id']]['max_length']; //加上组件长度
                                }
                                $length += $data[$j]['motor_number'];//加上电机长度
                            }
                            $res[] = [
                                'id' => $data[$i]['id'],
                                'color' => $data[$i]['color'],
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
