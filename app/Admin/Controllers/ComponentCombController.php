<?php

namespace App\Admin\Controllers;

use App\Enums\PartType;
use App\Models\Component;
use App\Models\ComponentComb;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ComponentCombController extends ResponseController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ComponentComb';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ComponentComb());

        $grid->column('name', __('Name'));
        $grid->lineId()->name('Line');
        $grid->maleId()->name('Male Connector');
        $grid->femaleId()->name('Female Connector');
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
        $show = new Show(ComponentComb::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('line_id', __('Line id'));
        $show->field('male_id', __('Male id'));
        $show->field('female_id', __('Female id'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ComponentComb());

        $form->text('name', __('Name'))->required();
        $form->select('line_id', __('Line'))->options(Component::whereIn('part_type', [PartType::PVWire, PartType::MVCable])->pluck('name', 'id'))->required();
        $form->select('male_id', __('MaleConnector'))->options(Component::where('part_type', PartType::MaleConnector)->pluck('name', 'id'))->rules('required_without:female_id');
        $form->select('female_id', __('FemaleConnector'))->options(Component::where('part_type', PartType::FemaleConnector)->pluck('name', 'id'))->rules('required_without:male_id');

        return $form;
    }

    public function getComponentCombList()
    {
        $components = ComponentComb::with('lineId', 'maleId', 'femaleId')->get();
    
        return $this->responseSuccess($components);
    }
}
