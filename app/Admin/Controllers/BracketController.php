<?php

namespace App\Admin\Controllers;

use App\Models\Bracket;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BracketController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bracket';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bracket());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('type', __('Type'))->using(['0' => '固定', '1' => '跟踪'])->label();
        $grid->column('style', __('Style'))->using(['1' => '单排', '2' => '双排'])->label();
        $grid->column('driver', __('Driver'));
        $grid->column('file', __('File'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Bracket::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('type', __('Type'));
        $show->field('style', __('Style'));
        $show->field('driver', __('Driver'));
        $show->field('file', __('File'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bracket());

        $form->text('name', __('Name'));
        $form->radio('type', __('Type'))->options(['0' => '固定', '1'=> '跟踪'])->default('0')->required();
        $form->radio('style', __('Style'))->options(['1' => '单排', '2'=> '双排'])->default('1')->required();

        $form->number('driver', __('Driver'))->rules('required_if:type,1|nullable|integer');
        $form->file('file', __('File'));

        return $form;
    }

    public function getBracket()
    {
        $brackets = Bracket::select('id', 'name')->get();

        return $this->responseSuccess($brackets);
    }
}
