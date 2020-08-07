<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">详细</h3>

        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="/admin/harnesses" class="btn btn-sm btn-default" title="列表">
                    <i class="fa fa-list"></i><span class="hidden-xs"> 列表</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="box-body">

            <div class="fields-group">

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Image</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <img width="35%" src="{{ asset('uploads/'.$harness->image) }}">
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ $harness->name }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Length</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ $harness->min_length }} ~ {{ $harness->max_length }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Fuse</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ $harness->fuse }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">String</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ $harness->string }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Outlet length</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ $harness->outlet_length }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-sm-2 control-label">Module</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                {{ \App\Enums\HarnessModule::getDescription($harness->module) }}
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>

                @if($harness->file)
                <div class="form-group ">
                    <label class="col-sm-2 control-label">File</label>
                    <div class="col-sm-8">
                        <div class="box box-solid box-default no-margin box-show">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <a href="{{ asset('uploads/'.$harness->file) }}" target="_blank">下载</a>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    @foreach($harness->harnessComponents as $item)
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    【{{ \App\Enums\PartType::getDescription($item->part_type) }}】{{ $item->component->name }}：{{ $item->component->price }} * {{ $item->quantity }}
                                    @if(in_array($item->part_type, [\App\Enums\PartType::PVWire, \App\Enums\PartType::MVCable]))
                                        <label class="pull-right">Total length：{{ $item->length }}</label>
                                    @endif
                                </h3>
                            </div>
                            @if(in_array($item->part_type, [\App\Enums\PartType::PVWire, \App\Enums\PartType::MVCable]))
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Length</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($item->details as $detail)
                                            <tr>
                                                <td>{{ $detail['length'] }}</td>
                                                <td>{{ $detail['quantity'] }}</td>
                                                <td>{{ $detail['length'] * $detail['quantity'] }}</td>
                                                <td>{{ $detail['remarks'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
        <!-- /.box-body -->
    </div>
</div>
