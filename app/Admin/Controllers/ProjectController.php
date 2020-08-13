<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\Specification;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Project';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project());

        $grid->company()->name();
        $grid->column('name', __('Name'))->display(function ($name) {
            return "<a href='projects/info/{$this->id}'>{$name}</a>";
        });
        $grid->column('address', __('Address'));
        $grid->column('total_quantity', __('Total quantity'));
        $grid->column('size_dc', __('Size dc'));
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
        $show = new Show(Project::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('company_id', __('Company id'));
        $show->field('code', __('Code'));
        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
        $show->field('total_quantity', __('Total quantity'));
        $show->field('size_dc', __('Size dc'));
        $show->field('layout_of_whip', __('Layout of whip'));
        $show->field('distance_between_poles', __('Distance between poles'));
        $show->field('remarks', __('Remarks'));
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
        $form = new Form(new Project());

        $form->select('company_id', __('Company'))->options(Company::pluck('name', 'id'))->required();
        $form->hidden('code', __('Code'));
        $form->text('name', __('Name'))->required();
        $form->text('address', __('Address'));
        $form->number('total_quantity', __('Total quantity'))->min(1);
        $form->decimal('size_dc', __('Size dc'));
//        $form->number('layout_of_whip', __('Layout of whip'));
//        $form->number('distance_between_poles', __('Distance between poles'));
//        $form->textarea('remarks', __('Remarks'));

        if ($form->isCreating()) {
            $form->saving(function ($form) {
                $form->code = rand();
            });
        }

        return $form;
    }

    public function info($id, Content $content)
    {
        Admin::script(<<<EOF
            const app = new Vue({
                el: '#app'
            });
EOF
        );

        $project = Project::with('company')->findOrFail($id);

        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(view('admin.project.info', compact('project')));
    }

    public function searchTypical(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'solar_panel_id'       => 'required|integer',
            'style'                => 'required|boolean',
            'of_module_per_string' => 'required|integer',
            'number_of_string'     => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }
    }
}
