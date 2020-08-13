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
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Junction box to rack'</span>
                                    <input class="form-control" v-model="form_data.junction_box_to_rack2">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Of module per string</span>
                                    <input class="form-control" v-model="form_data.of_module_per_string">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">String</span>
                                    <div style="background-color: #eee;" class="form-control" v-text="string ? string + 'ft' : ''"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">支架</span>
                                    <select class="form-control">
                                        <option>1</option>
                                        <option>2</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Drive</span>
                                    <input class="form-control" value="123123" disabled>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">End of extender</span>
                                    <input class="form-control">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Module to module extender</span>
                                    <input class="form-control">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">串数</span>
                                    <select class="form-control" id="number-of-string" name="number_of_string" v-model="form_data.number_of_string" multiple="multiple">
                                        <option v-for="i in 50" :value="i">{{ i }}串</option>
                                    </select>

                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">Remarks</span>
                                    <textarea class="form-control" rows="3" style="min-height: 78px"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div><button type="button" class="btn btn-primary pull-right" @click="search">查询</button></div>
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
            project_info:{},
            solar_panels:[],
            string:'',
            form_data:{
                solar_panel_id:'',
                of_module_per_string:'',
                style:'0',
                connector:'',
                fuse:'',
                junction_box_to_rack1:'',
                junction_box_to_rack2:'',
                number_of_string:[],
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
        'project',
    ],
    created() {
        this.project_info = JSON.parse(this.project);

        axios.get('/admin/solar-panel-list').then(response => {
            this.solar_panels = response.data.data
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
        })
    },
    methods: {
        search(){
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
                    swal(
                        "SUCCESS",
                        response.data.message,
                        'success'
                    ).then(function () {
                        location.href='/admin/projects'
                    })
                }
                // this.loading.project = false
            }).catch(error => {
                toastr.error(error.response.data.message)
            });
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
