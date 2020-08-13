<?php

namespace App\Admin\Controllers;

use App\Models\SolarPanel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SolarPanelController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SolarPanel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SolarPanel());

        $grid->column('name', __('Name'));

        $grid->column('length', __('Length/mm'))->display(function ($length){
            return sprintf("%.2f", $length);
        });
        $grid->column('width', __('Width/mm'))->display(function ($width){
            return sprintf("%.2f", $width);
        });
        $grid->column('m_l_pos', __('Module lead positive'))->display(function ($m_l_pos){
            return sprintf("%.2f", $m_l_pos);
        });
        $grid->column('m_l_neg', __('Module lead negative'))->display(function ($m_l_neg){
            return sprintf("%.2f", $m_l_neg);
        });
        $grid->column('connector', __('Connector'));
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(SolarPanel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('length', __('Length'));
        $show->field('width', __('Width'));
        $show->field('m_l_pos', __('M l pos'));
        $show->field('m_l_neg', __('M l neg'));
        $show->field('connector', __('Connector'));
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
        $form = new Form(new SolarPanel());

        $form->text('name', __('Name'))->creationRules(['required', "unique:solar_panels"])
            ->updateRules(['required', "unique:solar_panels,name,{{id}}"]);
        $form->number('length', __('Length/mm'))->min(1)->rules('required|integer|min:1');
        $form->number('width', __('Width/mm'))->min(1)->rules('required|integer|min:1');
        $form->number('m_l_pos', __('Module lead positive'))->min(0)->rules('required|integer|min:0');
        $form->number('m_l_neg', __('Module lead negative'))->min(0)->rules('required|integer|min:0');
        $form->text('connector', __('Connector'))->rules('required');

        $form->file('file', __('File'))->hidePreview()->removable();

        $form->saving(function (Form $form) {
            if($form->isEditing()){
                if ($form->width != $form->model()->width) {
                    throw new \Exception("width 被修改了");
                }
            }
        });


        return $form;
    }

    public function getSolarPanel()
    {
        $solar_panels = SolarPanel::all();

        return $this->responseSuccess($solar_panels);
    }
}
