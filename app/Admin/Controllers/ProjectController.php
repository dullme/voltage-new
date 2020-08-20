<?php

namespace App\Admin\Controllers;

use App\Enums\QuotationStatus;
use App\Models\Block;
use App\Models\Company;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\QuotationInfo;
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
        $form->decimal('size_dc', __('Size dc'));

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

//        $project = Project::with('company')->with(['quotationInfos' => function($item){
//            $item->with('solarPanel', 'bracket');
//        }])->findOrFail($id);
//        $typical_ids = $project->projectInfos->pluck('typical')->flatten(1)->unique()->toArray();
//        $typical = Typical::find($typical_ids);
//
//        $res = $project->projectInfos->map(function ($item) use($typical){
//            $data = [];
//            foreach($item['typical'] as $t){
//                $data[] = $typical->where('id', $t)->first();
//            }
//            $item['typical'] = $data;
//            return $item;
//        });
//
//        $project->setAttribute('project_infos', $res);

        $project = Project::with('company', 'quotations')->findOrFail($id);

        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(view('admin.project.info', compact('project')));
    }

    public function searchTypical(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'solar_panels'         => 'required|array',
            'style'                => 'required|boolean',
            'of_module_per_string' => 'required|integer',
            'number_of_string'     => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();
        $solarPanel = SolarPanel::find($data['solar_panels']);
        if ($data['style'] == 0) {
            $length = $solarPanel->pluck('length')->max();
        } else {
            $length = $solarPanel->pluck('width')->max();
        }

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
        })->values();

        return $this->responseSuccess($typical);
    }

    public function saveTypical(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotation_id'                 => 'required|integer',
            'of_module_per_string'         => 'required|integer',
            'style'                        => 'required|boolean',
            'connector'                    => 'required',
            'fuse'                         => 'required|integer',
            'junction_box_to_rack1'        => 'required|integer',
            'junction_box_to_rack1_remark' => 'required',
            'junction_box_to_rack2'        => 'nullable|integer',
            'junction_box_to_rack2_remark' => 'required_with:junction_box_to_rack2|nullable',
            'number_of_string'             => 'required',
            'typical'                      => 'required|array',
            'solar_panels'                 => 'required|array',
            'bracket_id'                   => 'required',
            'end_of_extender'              => 'required|integer',
            'module_to_module_extender'    => 'required|integer',
            'remarks'                      => 'required',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();

        $quotation = Quotation::find($data['quotation_id']);
        if ($quotation->status != QuotationStatus::SELECT_TYPICAL) {
            return $this->setStatusCode(422)->responseError('当前状态错误');
        }

        $solarPanel = SolarPanel::find($data['solar_panels']);
        if ($data['style'] == 0) {
            $length = $solarPanel->pluck('length')->max();
        } else {
            $length = $solarPanel->pluck('width')->max();
        }

        $data['string'] = (int) ceil($data['of_module_per_string'] * $length * 1.03 * 0.0032808);

        $projectInfo = QuotationInfo::create($data);

        return $this->responseSuccess($projectInfo);
    }

    public function deleteTypical($id)
    {
        $quotation = QuotationInfo::with('quotation')->find($id);
        if ($quotation->status != QuotationStatus::SELECT_TYPICAL) {
            return $this->setStatusCode(422)->responseError('当前状态错误');
        }

        QuotationInfo::destroy($id);

        return $this->responseSuccess(true);
    }

    public function saveQuotation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id'     => 'required|integer',
            'name'           => 'required',
            'total_quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $quotation = Quotation::create($validator->validated());

        return $this->responseSuccess($quotation);
    }

    public function deleteQuotation($id)
    {
        $count = QuotationInfo::where('quotation_id', $id)->count();
        if ($count) {
            return $this->setStatusCode(422)->responseError('存在 Typical 数据无法删除');
        }

        Quotation::destroy($id);

        return $this->responseSuccess(true);
    }

    public function showQuotation($id, Content $content)
    {
        Admin::script(<<<EOF
            const app = new Vue({
                el: '#app'
            });
EOF
        );

        $quotation = Quotation::with('project.company', 'quotationInfos.bracket', 'blocks')->findOrFail($id);

        $typical_ids = $quotation->quotationInfos->pluck('typical')->flatten(1)->unique()->toArray();
        $solar_panel_ids = $quotation->quotationInfos->pluck('solar_panels')->flatten(1)->unique()->toArray();
        $typical = Typical::find($typical_ids);
        $solar_panels = SolarPanel::find($solar_panel_ids);

        $quotation->setAttribute('typical_list', $typical->keyBy('id'));

        $res = $quotation->quotationInfos->map(function ($item) use ($typical, $solar_panels) {
            $data = [];
            foreach ($item['typical'] as $t) {
                $data[] = $typical->where('id', $t)->first();
            }

            $solar_panel = [];
            foreach ($item['solar_panels'] as $t) {
                $solar_panel[] = $solar_panels->where('id', $t)->first();
            }
            $item['typical'] = $data;
            $item['solar_panels'] = $solar_panel;

            return $item;
        });

        $quotation->setAttribute('quotation_infos', $res);

        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(view('admin.project.quotation', compact('quotation')));
    }

    public function finishQuotation($id)
    {
        $quotation = Quotation::with('quotationInfos')->find($id);
        if ($quotation->status != QuotationStatus::SELECT_TYPICAL) {
            return $this->setStatusCode(422)->responseError('当前状态错误');
        }

        if ($quotation->quotationInfos->count() == 0) {
            return $this->setStatusCode(422)->responseError('请添加 Typical');
        }

        $quotation->status = QuotationStatus::ADD_BLOCK;
        $quotation->typical = $quotation->quotationInfos->pluck('typical')->flatten(1)->values()->toArray();
        $quotation->save();

        return $this->responseSuccess(true);
    }

    public function saveBlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quotation_id' => 'required|integer',
            'name'         => 'required',
            'reference'    => 'required|integer|min:1',
            'block'        => 'required|array',
            'block.*.*'    => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $blocks = collect($request->input('block'));
        $total_typical = [];
        for ($i = 0; $i < count($blocks->first()); $i++) {
            $total_typical[$i] = $blocks->pluck($i)->sum();
        }

        if (collect($total_typical)->sum() <= 0) {
            return $this->setStatusCode(422)->responseError('请输入内容');
        }

        $block = Block::create([
            'quotation_id'  => $request->input('quotation_id'),
            'name'          => $request->input('name'),
            'reference'     => $request->input('reference'),
            'block'         => _unsetNull($request->input('block')),
            'total_typical' => $total_typical
        ]);

        return $this->responseSuccess($block);
    }

    public function deleteBlock($id)
    {
        Block::destroy($id);

        return $this->responseSuccess(true);
    }
}
