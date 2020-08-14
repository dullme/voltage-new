<?php

namespace App\Admin\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectInfo;
use App\Models\SolarPanel;
use App\Models\Specification;
use App\Models\Typical;
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

        $project = Project::with('company')->with(['projectInfos' => function($item){
            $item->with('solarPanel', 'bracket');
        }])->findOrFail($id);
        $typical_ids = $project->projectInfos->pluck('typical')->flatten(1)->unique()->toArray();
        $typical = Typical::find($typical_ids);

        $res = $project->projectInfos->map(function ($item) use($typical){
            $data = [];
            foreach($item['typical'] as $t){
                $data[] = $typical->where('id', $t)->first();
            }
            $item['typical'] = $data;
            return $item;
        });

        $project->setAttribute('project_infos', $res);

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

        $data = $validator->validated();
        $solarPanel = SolarPanel::find($data['solar_panel_id']);

        $length = $data['style'] == 0 ? $solarPanel->length : $solarPanel->width;
        $string = (int) ceil($data['of_module_per_string'] * $length * 1.03 * 0.0032808);
        $number_of_string = $data['number_of_string'];

        $typical = Typical::select('id', 'name', 'harnesses_selected')->get();

        $typical = $typical->filter(function ($item) use ($number_of_string, $string) {
            $harnesses_selected = collect($item->harnesses_selected)->filter(function ($item) use ($string) {
                return $item['min_length'] <= $string && $string <= $item['max_length'];
            });
            if ($harnesses_selected->count() == count($item->harnesses_selected)) {
                $strings = collect($item->harnesses_selected)->groupBy('string')->keys()->toArray();

                return count(array_intersect($strings, $number_of_string)) == count($strings);
            }

            return false;
        });

        if ($typical->count() == 0) {
            return $this->setStatusCode(422)->responseError('找不到相关的 Typical');
        }

        $typical = $typical->map(function ($item) {
            $item['checked'] = false;

            return $item;
        });

        return $this->responseSuccess($typical);
    }

    public function saveTypical(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'project_id'                   => 'required|integer',
            'solar_panel_id'               => 'required|integer',
            'of_module_per_string'         => 'required|integer',
            'style'                        => 'required|boolean',
            'connector'                    => 'required',
            'fuse'                         => 'required|integer',
            'junction_box_to_rack1'        => 'required|integer',
            'junction_box_to_rack1_remark' => 'required',
            'junction_box_to_rack2'        => 'nullable|integer',
            'junction_box_to_rack2_remark' => 'required_with:junction_box_to_rack2|nullable',
            'number_of_string'             => 'required',
            'typical'                      => 'required',
            'bracket_id'                   => 'required',
            'end_of_extender'              => 'required|integer',
            'module_to_module_extender'    => 'required|integer',
            'remarks'                      => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();
        $solarPanel = SolarPanel::find($data['solar_panel_id']);
        $length = $data['style'] == 0 ? $solarPanel->length : $solarPanel->width;
        $data['string'] = (int) ceil($data['of_module_per_string'] * $length * 1.03 * 0.0032808);

        $projectInfo = ProjectInfo::create($data);

        return $this->responseSuccess($projectInfo);
    }
}
