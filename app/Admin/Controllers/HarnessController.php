<?php

namespace App\Admin\Controllers;

use App\Enums\HarnessModule;
use App\Enums\PartType;
use DB;
use App\Models\Component;
use App\Models\Harness;
use App\Models\HarnessComponent;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use http\Client\Request;
use Illuminate\Support\Facades\Validator;

class HarnessController extends ResponseController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Harness';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Harness());
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
//        $actions->disableEdit();
            $actions->disableDelete();
        });
        $grid->column('image')->lightbox(['width' => 100, 'height' => 100]);
        $grid->column('name', __('Name'))->display(function ($name) {
            $url = url('/admin/harnesses/' . $this->id);

            return "<a href='{$url}'>{$name}</a>";
        });
        $grid->column('length', __('Length'))->display(function () {
            return "{$this->min_length} ~ {$this->max_length}";
        });
        $grid->column('have_fuse', __('有无 Fuse'))->bool();
        $grid->column('fuse', __('Fuse'));
        $grid->column('string', __('String'));
        $grid->column('outlet_length', __('Outlet length'));
        $grid->column('module', __('Module'))->display(function ($module) {
            return HarnessModule::getDescription($module);
        })->label();
        $grid->column('created_at', __('Created at'));

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
            ->body('<harness-create></harness-create>');
    }

    protected function detailInfo($id, Content $content)
    {
        $harness = Harness::with('harnessComponents.component')->findOrFail($id);

//dd($harness->toArray());
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->body(view('admin.harness.detail', compact('harness')));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'min_length'            => 'required|integer',
            'max_length'            => 'required|integer|gte:min_length',
            'have_fuse'             => 'required|integer',
            'string'                => 'required|integer',
            'outlet_length'         => 'required|integer',
            'module'                => 'required|integer',
            'remarks'               => 'nullable',
            'components'            => 'required|array',
            'components.*.id'       => 'required|integer',
            'components.*.length'   => 'nullable|numeric|max:999',
            'components.*.quantity' => 'required|integer|min:1',
            'components.*.remarks'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $data = $validator->validated();
        $time = Carbon::now()->toDateTimeString();
        $data_components = collect($data['components']);
        $components = Component::whereIn('id', $data_components->pluck('id'))->get()->keyBy('id')->toArray();
        $components = $data_components->map(function ($item) use ($components) {
            if (in_array($components[$item['id']]['part_type'], [PartType::PVWire, PartType::MVCable])) {
                if (!!!$item['length']) {
                    throw new \Exception('线必须有长度');
                }
                $length = intval($item['length'] * 100);

            } else {
                $length = null;
            }

            return [
                'component_id'    => $item['id'],
                'length'          => $length / 100,
                'quantity'        => intval($item['quantity']),
                'total'           => $length == null ? intval($item['quantity']) : intval($item['quantity']) * $length / 100,
                'part_type'       => $components[$item['id']]['part_type'],
                'wire_size'       => $components[$item['id']]['wire_size'],
                'match_wire_size' => $components[$item['id']]['match_wire_size'],
                'remarks'         => isset($item['remarks']) ? $item['remarks'] : '',
            ];
        })->sortBy('wire_size')->groupBy('wire_size');

        $components = $components->map(function ($item) {
            $data = collect($item);
            $other_name = '';
            $length = null;
            $quantity = $data->sum('quantity');
            //如果是线 有长度和别名
            if (in_array($item[0]['part_type'], [PartType::PVWire, PartType::MVCable])) {
                $length = $data->sum('total');
                $quantity = 1;
                $other_name = LINE_NUMBER_LETTER[$item[0]['wire_size']] . sprintf('%03d', ceil($length));
            }

            return [
                'other_name'      => $other_name,
                'component_id'    => $item[0]['component_id'],
                'length'          => $length,
                'quantity'        => $quantity,
                'part_type'       => $item[0]['part_type'],
                'wire_size'       => $item[0]['wire_size'],
                'match_wire_size' => $item[0]['match_wire_size'],
                'details'         => $data->map(function ($item) {
                    return [
                        'length'   => $item['length'],
                        'quantity' => $item['quantity'],
                        'remarks'  => $item['remarks'],
                    ];
                })->toArray()
            ];
        });

        if ($components->whereIn('part_type', [PartType::PVWire, PartType::MVCable])->count() <= 0) {
            throw new \Exception('线是构建 Harness 的必要零件');
        }

        $only_wires = $components->whereIn('part_type', [PartType::PVWire, PartType::MVCable])->toArray();
        $other_name = '';
        foreach ($only_wires as $only_wire) {
            $other_name .= $only_wire['other_name'];
        }

        DB::begintransaction();
        try {
            $name = 'VH' . sprintf('%02d', $data['string']) . HarnessModule::getDescription($data['module']) . $other_name;
            $harness = Harness::where('show_name', $name)->orderBy('id', 'DESC')->first();
            $version = $harness ? ++$harness->version : 1;

            $harness = Harness::create([
                'name'          => $name . sprintf('%02d', $version),
                'show_name'     => $name,
                'version'       => $version,
                'min_length'    => $data['min_length'],
                'max_length'    => $data['max_length'],
                'have_fuse'     => $data['have_fuse'],
                'string'        => $data['string'],
                'outlet_length' => $data['outlet_length'],
                'module'        => $data['module'],
                'remarks'       => $data['remarks'],
            ]);

            $components = $components->map(function ($item) use ($harness, $time) {

                return [
                    'harness_id'   => $harness->id,
                    'component_id' => $item['component_id'],
                    'part_type'    => $item['part_type'],
                    'length'       => $item['length'],
                    'quantity'     => $item['quantity'],
                    'details'      => json_encode($item['details']),
                    'created_at'   => $time,
                    'updated_at'   => $time
                ];
            })->values()->toArray();

            HarnessComponent::insert($components);
            DB::commit();

            return $this->responseSuccess(true);
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->setStatusCode(422)->responseError($exception->getMessage());
        }
    }

    public function searchHarness()
    {
        $validator = Validator::make(request()->all(), [
            'string' => 'required|integer',
            'length' => 'nullable|integer',
            'ids'    => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->setStatusCode(422)->responseError($validator->errors()->first());
        }

        $validated = $validator->validated();
        $ids = $validated['ids'];

        $harnesses = Harness::where('string', $validated['string'])->when(request('length'), function ($q) {
            $q->where('min_length', '<=', request('length'))->where('max_length', '>=', request('length'));
        })->get();

        $harnesses = $harnesses->map(function ($item) use ($ids) {
            return [
                'id'         => $item['id'],
                'name'       => $item['name'],
                'min_length' => $item['min_length'],
                'max_length' => $item['max_length'],
                'remarks'    => $item['remarks'],
                'string'     => $item['string'],
                'checked'    => in_array($item['id'], $ids) ? true : false,
                'image'      => $item['image'] ? asset('uploads/' . $item['image']) : '',
                'have_fuse'  => $item['have_fuse'],
            ];
        });

        return $this->responseSuccess($harnesses);
    }

    protected function form()
    {
        $form = new Form(new Harness());

        $form->image('image', __('Image'))->removable()->uniqueName();
        $form->file('file', __('File'))->removable()->uniqueName();

        return $form;
    }
}
