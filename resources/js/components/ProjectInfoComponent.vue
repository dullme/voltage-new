<template>
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">Info</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a href="/admin/projects" class="btn btn-sm btn-default" title="List">
                        <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                    </a>
                </div>

                <div class="btn-group pull-right" style="margin-right: 5px">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#projectInfo">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Add</span>
                    </button>
                </div>



            </div>
        </div>

        <form class="form-horizontal">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Company</label>
                        <div class="col-sm-8">
                            <span class="form-control">{{ project_info.company.name }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <span class="form-control">{{ project_info.name }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-8">
                            <span class="form-control">{{ project_info.address }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total Quantity</label>
                        <div class="col-sm-8">
                            <span class="form-control">{{ project_info.total_quantity }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-for="info in project_info.project_infos" style="margin-top: 50px">
                    <div class="form-horizontal" style="border: 2px solid #e3e3e3;padding: 30px 20px;display: flow-root;">
                        <div class="col-md-12" style="margin-bottom: 20px">
                            <button class="btn btn-danger btn-xs pull-right" @click="deleteInfo(id)"><i class="fa fa-trash"></i></button>

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
                                <span class="form-control" v-if="info.style == '0'">{{ info.solar_panel.name }}(length:{{ info.solar_panel.length }})</span>
                                <span class="form-control" v-else>{{ info.solar_panel.name }}(width:{{ info.solar_panel.width }})</span>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">Connector</span>
                                <span class="form-control">{{ info.connector }}</span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Fuse</span>
                                <span class="form-control">{{ info.fuse }}</span>
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
                                    <select class="form-control" v-model="form_data.solar_panel_id">
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
            project_info:{},
            brackets:[],
            solar_panels:[],
            string:'',
            form_data:{
                project_id:'',
                solar_panel_id:'',
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
    },
    props: [
        'project'
    ],
    created() {
        this.project_info = JSON.parse(this.project);
        this.form_data.project_id = this.project_info.id;

        axios.get('/admin/solar-panel-list').then(response => {
            this.solar_panels = response.data.data
        })

        axios.get('/admin/bracket-list').then(response => {
            this.brackets = response.data.data
        })

        this.$watch('form_data.solar_panel_id', ()=>{
            if(this.form_data.of_module_per_string != ''){
                for(let i in this.solar_panels){
                    if(this.solar_panels[i]['id'] == this.form_data.solar_panel_id){
                        let length = this.form_data.style =='0' ? this.solar_panels[i]['length'] : this.solar_panels[i]['width']
                        this.string = Math.ceil(this.form_data.of_module_per_string * length * 1.03 * 0.0032808)
                    }
                }
            }else{
                this.string = ''
            }
        })

        this.$watch('form_data.of_module_per_string', ()=>{
            if(this.form_data.of_module_per_string != ''){
                for(let i in this.solar_panels){
                    if(this.solar_panels[i]['id'] == this.form_data.solar_panel_id){
                        let length = this.form_data.style =='0' ? this.solar_panels[i]['length'] : this.solar_panels[i]['width']
                        this.string = Math.ceil(this.form_data.of_module_per_string * length * 1.03 * 0.0032808)
                    }
                }
            }else{
                this.string = ''
            }
        })

        this.$watch('form_data.style', ()=>{
            this.form_data.solar_panel_id = ''
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

        this.$watch('form_data.solar_panel_id', () => {
            this.typical = [];
        })

        this.$watch('form_data.number_of_string', () => {
            this.typical = [];
        })
    },
    methods: {
        checkbox(index, e){
            this.typical[index]['checked'] = e.target.checked
        },

        search(){
            this.typical = [];

            axios({
                method: 'post',
                url: '/admin/projects/search/typical',
                data: {
                    solar_panel_id:this.form_data.solar_panel_id,
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

        deleteInfo(id){

        }

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

</style>
