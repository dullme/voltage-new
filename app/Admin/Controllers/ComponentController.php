<?php

namespace App\Admin\Controllers;

use App\Enums\PartType;
use App\Models\Component;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ComponentController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Component';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Component());
        $grid->model()->resetOrderBy();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('part_type', 'Category')->select(array_flip(DYNAMICS));
        });

        $grid->column('id', __('Id'));
        $grid->column('name', __('Description'));
        $grid->column('description2', __('Description2'));
        $grid->column('part_type', __('Category'))->display(function ($part_type) {
            return PartType::getDescription($part_type);
        })->label();
//        $grid->column('wire_size', __('Wire size'))->display(function ($wire_size) {
//            return $wire_size ? '#' . $wire_size : '';
//        });
//        $grid->column('match_wire_size', __('Match wire size'))->display(function ($match_wire_size) {
//            return $match_wire_size ? '#' . $match_wire_size : '';
//        });
//        $grid->column('price', __('Price'))->display(function ($price) {
//            return isset(CURRENCY_MARK[$this->currency]) ? CURRENCY_MARK[$this->currency] . ' ' . $price : $price;
//        });
//        $grid->column('weight', __('Weight/kg'));
//        $grid->column('created_at', __('Created at'));
        $grid->disableActions();

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
        $show = new Show(Component::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('part_type', __('Part type'));
        $show->field('wire_size', __('Wire size'));
        $show->field('match_wire_size', __('Match wire size'));
        $show->field('currency', __('Currency'));
        $show->field('price', __('Price'));
        $show->field('weight', __('Weight'));
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
        $form = new Form(new Component());

        if ($form->isCreating()) {
            $form->text('name', __('Name'));
            $form->text('no', __('No'))->required();
            $form->select('part_type', __('Part type'))->options(PartType::toSelectArray());
            $form->select('wire_size', __('Wire size'))->options(LINE_NUMBER);
            $form->select('match_wire_size', __('Match wire size'))->options(LINE_NUMBER);
            $form->select('currency', __('Currency'))->options(CURRENCY)->default(1);
            $form->decimal('price', __('Price'));
            $form->decimal('weight', __('Weight'));
        } else {
            $form->display('name', __('Name'));
            $form->decimal('price', __('Price'));
            $form->decimal('weight', __('Weight'));
        }

        return $form;
    }

    /**
     * 零件列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComponentList()
    {
        $components = Component::orderBy('part_type', 'ASC')->get();
        $components = $components->map(function ($item) {
            return [
                'id'        => $item->id,
                'name'      => $item->name,
                'price'     => $item->price,
                'weight'    => $item->weight,
                'part_type' => PartType::getDescription($item->part_type),
                'type'      => $item->part_type,
                'currency'  => CURRENCY_MARK[$item->currency],
            ];
        });

        return response()->json($components);
    }
}
