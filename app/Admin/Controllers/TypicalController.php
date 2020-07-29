<?php

namespace App\Admin\Controllers;

use App\Models\Typical;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class TypicalController extends AdminController
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

        dd($data);

        $data = collect($data['stages'])->map(function ($item){
            $count = intval(collect($item)->count() /2) + 1;
            return collect($item)->where('id','!=', null)->count() == $count;
        });

        dd(array_search(true, $data->toArray()));
    }
}
