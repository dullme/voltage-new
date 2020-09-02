<template>
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">{{ quotation_info.name }}</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a :href="'/admin/projects/info/'+quotation_info.project.id" class="btn btn-sm btn-default" title="List">
                        <i class="fa fa-list"></i><span class="hidden-xs"> Back</span>
                    </a>
                </div>

                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a :href="'/admin/projects/show/whip/'+quotation_info.id" class="btn btn-sm btn-success">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Add whip</span>
                    </a>
                </div>

                <div class="btn-group pull-right" style="margin-right: 5px" v-if="quotation_info.status == 1">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#projectInfo">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Add typical</span>
                    </button>
                </div>


                <div class="btn-group pull-right" style="margin-right: 5px" v-if="quotation_info.status == 1 && quotation_info.quotation_infos.length > 0">
                    <button type="button" class="btn btn-sm btn-success" @click="finishTypical">Finish</button>
                </div>

            </div>
        </div>

        <form class="form-horizontal">
            <div class="box-body">
<!--                <div class="col-md-12">-->
<!--                    <div class="form-group">-->
<!--                        <label class="col-sm-2 control-label">Company</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            <span class="form-control">{{ quotation_info.project.company.name }}</span>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="form-group">-->
<!--                        <label class="col-sm-2 control-label">Name</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            <span class="form-control">{{ quotation_info.project.name }}</span>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="form-group">-->
<!--                        <label class="col-sm-2 control-label">Address</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            <span class="form-control">{{ quotation_info.project.address }}</span>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="form-group">-->
<!--                        <label class="col-sm-2 control-label">Size Dc</label>-->
<!--                        <div class="col-sm-8">-->
<!--                            <span class="form-control">{{ quotation_info.project.size_dc }}</span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="col-md-6" v-for="(info,index) in quotation_info.quotation_infos" style="margin: 20px auto">
                    <div class="form-horizontal" style="border: 2px solid #e3e3e3;padding: 10px 20px 20px 20px;display: flow-root;">
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <button v-if="quotation_info.status == 1" type="button" class="btn btn-danger btn-sm pull-right" v-on:click="deleteInfo(info.id, index)"><i class="fa fa-trash"></i></button>

                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">摆放方式</span>
                                <span class="form-control">{{ info.style == 0 ? '横着' : '竖着' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">太阳能板</span>
                                <span class="form-control">
                                    <span v-for="item in info.solar_panels">
                                        <span class="label label-primary">{{ item.name }}</span>&nbsp;
                                    </span>
                                </span>

                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Connector</span>
                                <span class="form-control">{{ info.connector }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Fuse</span>
                                <span class="form-control">{{ info.fuse }}</span>
                                <span class="input-group-addon">A</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Junction box to rack</span>
                                <span class="form-control">{{ info.junction_box_to_rack1 }}</span>
                                <span class="input-group-addon" style="border-left: unset">mm</span>
                                <span class="input-group-addon">{{ Math.round(info.junction_box_to_rack1 * 0.0032808 * 100) / 100 }} ft</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Remark</span>
                                <span class="form-control">{{ info.junction_box_to_rack1_remark }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Junction box to rack'</span>
                                <span class="form-control">{{ info.junction_box_to_rack2 }}</span>
                                <span class="input-group-addon" style="border-left: unset">mm</span>
                                <span class="input-group-addon" v-if="info.junction_box_to_rack2">{{ Math.round(info.junction_box_to_rack2 * 0.0032808 * 100) / 100 }} ft</span>
                            </div>

                            <div class="input-group" v-if="info.junction_box_to_rack2">
                                <span class="input-group-addon">Remark</span>
                                <span class="form-control">{{ info.junction_box_to_rack2_remark }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Of module per string</span>
                                <span class="form-control">{{ info.of_module_per_string }}</span>
                                <span class="input-group-addon" style="font-size: 14px;font-weight: bold" v-text="info.string + ' ft'"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-addon">支架</span>
                                <span class="form-control">{{ info.bracket.name }} : {{ info.bracket.driver ? info.bracket.driver : 'unset' }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">End of extender</span>
                                <span class="form-control" v-if="info.end_of_extender == '0'">UNKNOWN</span>
                                <span class="form-control" v-else-if="info.end_of_extender == '1'">ROW HEAD</span>
                                <span class="form-control" v-else-if="info.end_of_extender == '2'">CBX</span>
                                <span class="form-control" v-else>INV</span>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Module to module extender</span>
                                <span class="form-control">{{ info.module_to_module_extender }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">串数</span>
                                <span class="form-control">
                                    <span v-for="item in info.number_of_string">
                                        <span class="label label-primary">{{ item }}串</span>&nbsp;
                                    </span>
                                </span>

                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Remarks</span>
                                <textarea class="form-control" rows="3" :style="info.junction_box_to_rack2 ? 'min-height: 166px' : 'min-height: 124px'" v-text="info.remarks"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>组成：组串长度范围</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in info.typical">
                                    <td>{{ item.name }}</td>
                                    <td>
                                                <span v-for="harness in item.harnesses_selected">
                                                    <span class="label label-success">{{ harness.string }}：{{ harness.min_length }}～{{  harness.max_length }}</span>&nbsp;
                                                </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-md-12" v-if="quotation_info.status == 2">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div class="col-sm-4">
                        <div class="input-group" style="margin-bottom: 0px">
                            <label class="input-group-addon">Name</label>
                            <input type="text" class="form-control" v-model="block_form_data.name">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="input-group">
                            <label class="input-group-addon">Reference</label>
                            <input type="text" class="form-control" v-model="block_form_data.reference" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                    </div>

<!--                    <div class="col-sm-4">-->
<!--                        <div class="input-group">-->
<!--                            <label class="input-group-addon">Order</label>-->
<!--                            <input type="text" class="form-control" v-model="block_form_data.order" oninput="this.value = this.value.replace(/[^0-9]/g, '');">-->
<!--                        </div>-->
<!--                    </div>-->

                    <div style="clear: both"></div>
                </div>

                <div class="panel-body">
                    <div style="overflow: auto">
                        <table class="table table-hover" style="width: auto">
                            <thead>
                            <tr>
                                <th style="text-align: center;min-width: 50px">
                                    <p style="margin-bottom: unset">Total</p>
                                    <p style="margin-bottom: unset">NO.</p>
                                </th>
                                <th style="text-align: center;min-width: 30px" v-for="(typical,index) in quotation_info.typical">
                                    <p style="margin-bottom: unset;color: #f1f1f1" v-text="block_form_data.total_typical[index] ? block_form_data.total_typical[index] : 0" :class="block_form_data.total_typical[index]?'has-number' : ''"></p>
                                    <p style="margin-bottom: unset" data-toggle="tooltip" data-placement="top" :data-original-title="quotation_info['typical_list'][typical]['name']">T{{ index+1 }}</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(block,index) in block_form_data.block">
                                    <th style="max-width: 70px;text-align: center;height: 20px">CBX-{{ index + 1 }}
                                    </th>
                                    <td v-for="(item,b_index) in block" style="text-align: center">
                                            <span data-toggle="tooltip" data-placement="top" :data-original-title="'CBX-'+(index+1)+' : T'+(b_index+1)">
                                                <input style="max-width: 30px;text-align: center;height: 20px;" :class="block[b_index] ? 'has-number' : ''"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                       type="text" v-model="block[b_index]" @input="updateView($event, b_index)">
                                            </span>
                                    </td>
                                </tr>
                                <tr style="height: 40px" class="add-block-row">
                                    <td :colspan="quotation_info.typical.length + 1">
                                        <button type="button" class="pull-right btn btn-success btn-xs" @click="addBlockLine"><i class="fa fa-plus"></i></button>
                                        <button v-if="block_form_data.block.length" type="button" class="pull-right btn btn-danger btn-xs" style="margin-right: 4px" @click="deleteBlockLine"><i class="fa fa-minus"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="button" @click="addBlock" class="btn btn-sm btn-success pull-right" id="add-table-field">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;Submit
                    </button>
                    <div style="clear: both"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" v-for="(item,index) in quotation_info.blocks" :class="index==0?'active':''">
                    <a :href="'#block_'+item.id" role="tab" data-toggle="tab">{{ item.name }}</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" :class="index==0?'active':''" :id="'block_'+item.id" v-for="(item,index) in quotation_info.blocks">

                    <div style="padding: 10px">
                        <span><b>Reference：</b>{{ item.reference }}</span>
                        <button type="button" class="btn btn-danger btn-xs" style="margin-left: 20px" @click="deleteBlock(item.id)"><i class="fa fa-trash"></i></button>
                    </div>

                    <div style="overflow: auto">
                        <table class="table table-hover" style="width: auto">
                            <thead>
                            <tr>
                                <th style="text-align: center;min-width: 50px">
                                    <p style="margin-bottom: unset">Total</p>
                                    <p style="margin-bottom: unset">NO.</p>
                                </th>
                                <th style="text-align: center;min-width: 30px" v-for="(typical,index) in item.total_typical">
                                    <p style="margin-bottom: unset;color: #f1f1f1" v-text="typical" :class="typical?'has-number' : ''"></p>
                                    <p style="margin-bottom: unset">T{{ index + 1 }}</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(b,index) in item.block">
                                <th style="max-width: 70px;text-align: center;height: 20px">CBX-{{ index + 1 }}
                                </th>
                                <td v-for="i in b" style="text-align: center">
                                            <span data-toggle="tooltip" data-placement="top" :data-original-title="'CBX-'+(index+1)+' : T'+i">
                                                <input v-if="i" style="max-width: 30px;text-align: center;height: 20px;" class="has-number" :value="i" readonly>
                                                <input v-else style="max-width: 30px;text-align: center;height: 20px;" value="" readonly>
                                            </span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
        <div style="clear: both"></div>

        <div class="modal fade in" id="projectInfo">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Project Info：<span class="po_client_no"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span>摆放方式</span>
                                    <label class="mgl10">横着<input type="radio" name="type" value="0" v-model="form_data.style"></label>
                                    <label class="mgl10">竖着<input type="radio" name="type" value="1" v-model="form_data.style"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">太阳能板</span>
                                    <select class="form-control" id="solar-panels" v-model="form_data.solar_panels" multiple="multiple">
                                        <option v-for="item in this.solar_panels" :value="item.id">
                                            <span v-if="form_data.style == '0'">{{ item.name }}(length:{{ item.length }})</span>
                                            <span v-else>{{ item.name }}(width:{{ item.width }})</span>
                                        </option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Connector</span>
                                    <input class="form-control" v-model="form_data.connector">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Fuse</span>
                                    <input class="form-control" v-model="form_data.fuse">
                                    <span class="input-group-addon">A</span>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Junction box to rack</span>
                                    <input class="form-control" v-model="form_data.junction_box_to_rack1">
                                    <span class="input-group-addon" style="border-left: unset">mm</span>
                                    <span class="input-group-addon" v-if="form_data.junction_box_to_rack1">{{ Math.round(form_data.junction_box_to_rack1 * 0.0032808 * 100) / 100 }} ft</span>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Remark</span>
                                    <input class="form-control" v-model="form_data.junction_box_to_rack1_remark">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Junction box to rack'</span>
                                    <input class="form-control" v-model="form_data.junction_box_to_rack2">
                                    <span class="input-group-addon" style="border-left: unset">mm</span>
                                    <span class="input-group-addon" v-if="form_data.junction_box_to_rack2">{{ Math.round(form_data.junction_box_to_rack2 * 0.0032808 * 100) / 100 }} ft</span>
                                </div>

                                <div class="input-group" v-if="form_data.junction_box_to_rack2">
                                    <span class="input-group-addon">Remark</span>
                                    <input class="form-control" v-model="form_data.junction_box_to_rack2_remark">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Of module per string</span>
                                    <input class="form-control" v-model="form_data.of_module_per_string">
                                    <span class="input-group-addon" style="font-size: 14px;font-weight: bold" v-if="string" v-text="string + ' ft'"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">支架</span>
                                    <select class="form-control" v-model="form_data.bracket_id">
                                        <option v-for="item in brackets" :value="item.id">
                                            <span>{{ item.name }} : {{ item.driver ? item.driver : 'unset' }}</span>
                                        </option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">End of extender</span>
                                    <select class="form-control" v-model="form_data.end_of_extender">
                                        <option value="0">UNKNOWN</option>
                                        <option value="1">ROW HEAD</option>
                                        <option value="2">CBX</option>
                                        <option value="3">INV</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Module to module extender</span>
                                    <input class="form-control" v-model="form_data.module_to_module_extender">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">串数</span>
                                    <select class="form-control" id="number-of-string" name="number_of_string" v-model="form_data.number_of_string" multiple="multiple">
                                        <option v-for="i in 50" :value="i">{{ i }}串</option>
                                    </select>

                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Remarks</span>
                                    <textarea class="form-control" rows="3" :style="form_data.junction_box_to_rack2 ? 'min-height: 168px' : 'min-height: 124px'" v-model="form_data.remarks"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div><button type="button" class="btn btn-primary pull-right" @click="search">查询</button></div>
                            </div>

                            <div class="col-md-12" v-if="typical.length">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>组成：组串长度范围</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,index) in typical">
                                        <td>{{ item.name }}</td>
                                        <td>
                                                <span v-for="harness in item.harnesses_selected">
                                                    <span class="label label-success">{{ harness.string }}：{{ harness.min_length }}～{{  harness.max_length }}</span>&nbsp;
                                                </span>
                                        </td>
                                        <td><input type="checkbox" @click="checkbox(index ,$event)" :checked="item.checked"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12" v-if="typical.length">
                                <div><button type="button" class="btn btn-success pull-right" @click="save">保存</button></div>
                            </div>

                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            typical:[],
            quotation_info:{},
            brackets:[],
            solar_panels:[],
            string:'',
            form_data:{
                quotation_id:'',
                solar_panels:[],
                of_module_per_string:'',
                style:'0',
                connector:'',
                fuse:'',
                junction_box_to_rack1:'',
                junction_box_to_rack1_remark:'',
                junction_box_to_rack2:'',
                junction_box_to_rack2_remark:'',
                number_of_string:[],
                typical:[],
                bracket_id:'',
                end_of_extender:'',
                module_to_module_extender:'',
                remarks:''
            },

            block_form_data:{
                quotation_id:'',
                name:'',
                block:[],
                total_typical: {},
                reference:'',
            },
        }
    },
    mounted(){
        $('#number-of-string').select2({
            placeholder : 'Please choose',
            allowClear: true, //选中项可清空
            maximumSelectionLength:10,
        }).on('change', (e) => {
            this.form_data.number_of_string = $('#number-of-string').val() == null ? [] : $('#number-of-string').val()
        })

        $('#solar-panels').select2({
            placeholder : 'Please choose',
            // allowClear: true, //选中项可清空
            maximumSelectionLength:10,
        }).on('change', (e) => {
            this.form_data.solar_panels = $('#solar-panels').val() == null ? [] : $('#solar-panels').val()
        })
    },
    props: [
        'quotation'
    ],
    created() {
        this.quotation_info = JSON.parse(this.quotation);
        this.form_data.quotation_id = this.quotation_info.id;
        this.block_form_data.quotation_id = this.quotation_info.id;

        axios.get('/admin/solar-panel-list').then(response => {
            this.solar_panels = response.data.data
        })

        axios.get('/admin/bracket-list').then(response => {
            this.brackets = response.data.data
        })

        this.$watch('form_data.solar_panels', ()=>{
            if(this.form_data.of_module_per_string != []){
                let max_length = 0;
                for(let j in this.form_data.solar_panels){
                    for(let i in this.solar_panels){
                        if(this.solar_panels[i]['id'] == this.form_data.solar_panels[j]){
                            let length = this.form_data.style =='0' ? this.solar_panels[i]['length'] : this.solar_panels[i]['width']
                            max_length = length > max_length ? length : max_length;
                        }
                    }
                }

                this.string = Math.ceil(this.form_data.of_module_per_string * max_length * 1.03 * 0.0032808)
            }else{
                this.string = ''
            }
        })

        this.$watch('form_data.of_module_per_string', ()=>{
            if(this.form_data.of_module_per_string != ''){
                let max_length = 0;
                for(let j in this.form_data.solar_panels){
                    for(let i in this.solar_panels){
                        if(this.solar_panels[i]['id'] == this.form_data.solar_panels[j]){
                            let length = this.form_data.style =='0' ? this.solar_panels[i]['length'] : this.solar_panels[i]['width']
                            max_length = length > max_length ? length : max_length;
                        }
                    }
                }

                this.string = Math.ceil(this.form_data.of_module_per_string * max_length * 1.03 * 0.0032808)
            }else{
                this.string = ''
            }
        })

        this.$watch('form_data.style', ()=>{
            this.form_data.solar_panels = []
            this.form_data.connector = ''
            this.form_data.fuse = ''
            this.form_data.junction_box_to_rack1= ''
            this.form_data.junction_box_to_rack2= ''
            this.form_data.of_module_per_string = ''
            this.typical = [];
        })

        this.$watch('form_data.of_module_per_string', () => {
            this.typical = [];
        })

        this.$watch('form_data.solar_panels', () => {
            this.typical = [];
        })

        this.$watch('form_data.number_of_string', () => {
            this.typical = [];
        })
    },
    methods: {
        deleteInfo(id, index){
            swal({
                title: '确定删除此 Typical ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/delete/typical/' + id,
                    }).then(response => {
                        swal(
                            "SUCCESS",
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.quotation_info.quotation_infos.splice(index, 1)
                        });
                    })
                }
            })
        },

        checkbox(index, e){
            this.typical[index]['checked'] = e.target.checked
        },

        search(){
            this.typical = [];

            axios({
                method: 'post',
                url: '/admin/projects/search/typical',
                data: {
                    solar_panels:this.form_data.solar_panels,
                    style:this.form_data.style,
                    of_module_per_string:this.form_data.of_module_per_string,
                    number_of_string:this.form_data.number_of_string,
                }
            }).then(response => {
                if (response.data.status) {
                    this.typical = response.data.data
                }
                // this.loading.project = false
            }).catch(error => {
                toastr.error(error.response.data.message)
            });
        },

        save(){
            this.form_data.typical = []
            for(let i in this.typical){
                if(this.typical[i]['checked'] == true){
                    this.form_data.typical.push(this.typical[i]['id'])
                }
            }
            if(this.form_data.typical.length <= 0){
                toastr.error('请选择 Typical')
            }else{
                axios({
                    method: 'post',
                    url: '/admin/projects/save/typical',
                    data: this.form_data
                }).then(response => {
                    if (response.data.status) {
                        swal('SUCCESS', '添加成功', 'success').then(() => {
                            location.reload()
                        })
                    }
                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
            }
        },

        finishTypical(){
            swal({
                title: '完成后无法继续添加 Typical，确定完成?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/finish/typical/'+this.form_data.quotation_id,
                        data: this.form_data
                    }).then(response => {
                        if (response.data.status) {
                            swal('SUCCESS', '操作成功', 'success').then(() => {
                                location.reload()
                            })
                        }
                    }).catch(error => {
                        toastr.error(error.response.data.message)
                    });
                }
            })
        },

        addBlock(){
            axios({
                method: 'post',
                url: '/admin/projects/save/block',
                data: this.block_form_data
            }).then(response => {
                if (response.data.status) {
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.reload()
                    })
                }
            }).catch(error => {
                toastr.error(error.response.data.message)
            });
        },

        deleteBlock(id){
            swal({
                title: '删除 Block 同时删除 Whip ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/delete/block/'+ id,
                    }).then(response => {
                        if (response.data.status) {
                            swal('SUCCESS', '删除成功', 'success').then(() => {
                                location.reload()
                            })
                        }
                    }).catch(error => {
                        toastr.error(error.response.data.message)
                    });
                }
            })
        },

        addBlockLine(){
            let arr = []
            for(let i = 0; i < this.quotation_info.typical.length; i++){
                arr[i] = ''
            }
            this.block_form_data.block.push(arr)
        },
        deleteBlockLine(){
            let res = this.block_form_data.block.pop()
            for(let i in res){
                this.block_form_data.total_typical[i] -=res[i];
            }
        },

        updateView(e, item){
            this.block_form_data.total_typical[item] = 0;
            this.block_form_data.block.forEach(block=>{
                if(Number(block[item])){
                    this.block_form_data.total_typical[item] += Number(block[item])
                }
            })

            this.$forceUpdate()
        },

    }
}
</script>

<style scoped>

.input-group{
    margin-bottom: 10px;
}

.mgl10{
    margin-left: 10px;
}

.select2-container {
    width: 100% !important;
}

.form-control {
    padding: 6px;
}

.form-control-text {
    padding: 6px;
    line-height: 34px;
}

.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding: 2px;
}

.table > tbody > tr > th, .table > tbody > tr > td {
    border-top: unset;
}

.has-number{
    font-weight: bold;
    border-color: #1a2226;
    color:#1a2226 !important;
}

.add-block-row:hover{
    background-color: unset;
}

</style>
