<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">详细</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="/admin/typicals" class="btn btn-sm btn-default" title="列表">
                    <i class="fa fa-list"></i><span class="hidden-xs"> 列表</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">


            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2" style="text-align: center;font-size: 18px;font-weight: bold;max-width: 500px">Item</th>
                    <th>Quantity</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td rowspan="{{ count($harnesses) + 1 }}" style="text-align: center;vertical-align: middle;font-size: 18px;font-weight: bold;width: 100px">
                        Harness
                    </td>
                </tr>
                @foreach($harnesses as $harness)
                    <tr>
                        <td style="width: 200px"><label style="cursor: pointer">{{ $harness['name'] }}</label></td>
                        <td>{{ $harness['count'] }}</td>
                    </tr>
                @endforeach
                @if(count($res_fuses) > 0)
                    <tr>
                        <td rowspan="{{ count($res_fuses) + 1 }}" style="text-align: center;vertical-align: middle;font-size: 18px;font-weight: bold">
                            Extender
                        </td>
                    </tr>
                    @foreach($res_fuses as $fuse)
                        <tr>
                            <td><label>{{ ceil($fuse['length'] * $typical['margin']/100 + $fuse['length']) }}</label></td>
                            <td>{{ $fuse['count'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: center;vertical-align: middle;font-size: 18px;font-weight: bold">Extender</td>
                        <td><label>0</label></td>
                        <td>0</td>
                    </tr>
                @endif
                </tbody>
            </table>

            <!-- 保存的格局Fuse START -->
            @if(isset($typical->fuse['res']))
            <div style="clear: both">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>有没有保险丝：{{ $typical->fuse['pos_neg'] == 'fuse' ? 'Fuse' : 'Nofuse' }}</span>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover stages" style="width: auto">
                            <thead>
                            <tr>
                                <th style="text-align: center;">
                                    <p style="margin-bottom: unset">NO.</p>
                                </th>
                                @foreach($typical->fuse['motors'] as $motor)
                                <th style="text-align: center;">
                                    <p style="margin-bottom: unset;cursor: pointer">{{ $motor['name'] }}</p>
                                </th>
                                @endforeach
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">
                                    <p style="margin-bottom: unset">长度</p>
                                </th>
                                @foreach($typical->fuse['motors'] as $motor)
                                <th style="text-align: center;">
                                    <p style="margin-bottom: unset;cursor: pointer"
                                       class="{{ $motor['number'] == 0 ? 'color_gray' :''}}">{{ $motor['number'] }}</p>
                                </th>
                                @endforeach
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($typical->fuse['stages'] as $s_index=>$stage)
                            <tr>
                                <th style="max-width: 70px;text-align: center;height: 20px">{{ $s_index + 1 }}
                                </th>
                                @foreach($stage as $i_index=>$item)
                                <td style="text-align: center;vertical-align: middle">
                                    @if($i_index%2 == 0)
                                    <a class="cube" style="{{ $item['color'] ? 'background-color:'.$item['color'] : '' }}">
                                        <span class="{{ $item['motor'] ? 'cube cube_motor is_motor' : '' }}">{{ isset($item['harness_key']) ? $item['harness_key'] : '' }}</span>
                                    </a>
                                    @else
                                    <a style="display: flex;justify-content: center">
                                        <span class="cube cube_motor {{ $item['motor']?'is_motor':'' }}"></span>
                                    </a>
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    @if($typical->fuse['check_list'][$s_index])
                                    @foreach($typical->fuse['res'][$s_index] as $fuse)
                                    <span>
                                        <i class="fa fa-circle" style="font-size:12px;color: {{ $fuse['color'] }}"></i>
                                        {{ ceil($fuse['length'] * $typical['margin']/100 + $fuse['length']) }}
                                    </span>
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($typical->fuse['check_list'][$s_index])
                                    <i class="fa fa-check text-success"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <!-- 保存的格局Fuse END -->


            <!-- 保存的格局Nofuse START -->
            @if(isset($typical->nofuse['res']))
                <div style="clear: both">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>有没有保险丝：{{ $typical->nofuse['pos_neg'] == 'fuse' ? 'Fuse' : 'Nofuse' }}</span>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover stages" style="width: auto">
                                <thead>
                                <tr>
                                    <th style="text-align: center;">
                                        <p style="margin-bottom: unset">NO.</p>
                                    </th>
                                    @foreach($typical->nofuse['motors'] as $motor)
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset;cursor: pointer">{{ $motor['name'] }}</p>
                                        </th>
                                    @endforeach
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">
                                        <p style="margin-bottom: unset">长度</p>
                                    </th>
                                    @foreach($typical->nofuse['motors'] as $motor)
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset;cursor: pointer"
                                               class="{{ $motor['number'] == 0 ? 'color_gray' :''}}">{{ $motor['number'] }}</p>
                                        </th>
                                    @endforeach
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($typical->nofuse['stages'] as $s_index=>$stage)
                                    <tr>
                                        <th style="max-width: 70px;text-align: center;height: 20px">{{ $s_index + 1 }}
                                        </th>
                                        @foreach($stage as $i_index=>$item)
                                            <td style="text-align: center;vertical-align: middle">
                                                @if($i_index%2 == 0)
                                                    <a class="cube" style="{{ $item['color'] ? 'background-color:'.$item['color'] : '' }}">
                                                        <span class="{{ $item['motor'] ? 'cube cube_motor is_motor' : '' }}">{{ isset($item['harness_key']) ? $item['harness_key'] : '' }}</span>
                                                    </a>
                                                @else
                                                    <a style="display: flex;justify-content: center">
                                                        <span class="cube cube_motor {{ $item['motor']?'is_motor':'' }}"></span>
                                                    </a>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            @if($typical->nofuse['check_list'][$s_index])
                                            @foreach($typical->nofuse['res'][$s_index] as $fuse)
                                                <span>
                                        <i class="fa fa-circle" style="font-size:12px;color: {{ $fuse['color'] }}"></i>
                                        {{ ceil($fuse['length'] * $typical['margin']/100 + $fuse['length']) }}
                                    </span>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($typical->nofuse['check_list'][$s_index])
                                                <i class="fa fa-check text-success"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        @endif
        <!-- 保存的格局Nofuse END -->

        </div>
        <!-- /.box-body -->
    </div>
</div>
