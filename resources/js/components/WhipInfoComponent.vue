<template>
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">{{ quotation_info.name }}</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a :href="'/admin/projects/show/quotation/'+quotation_info.id" class="btn btn-sm btn-default" title="List">
                        <i class="fa fa-list"></i><span class="hidden-xs"> Back</span>
                    </a>
                </div>

                <div class="btn-group pull-right" style="margin-right: 5px">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#addWhip">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Add</span>
                    </button>
                </div>

            </div>
        </div>

        <form class="form-horizontal">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12" style="margin-bottom: 20px">
                        <div class="col-md-12">

                            <div class="input-group">
                                <div class="col-sm-8 w-200" style="display: flex;margin-left: -15px">
                                    <label class="radio-inline" id="option_cab" data-placement="right" style="display: flex;">
                                        <input type="radio" name="layout_of_whip" value="1" v-model="quotation_info.layout_of_whip"> CAB
                                    </label>
                                    <label class="radio-inline" id="option_trench" data-placement="right" style="margin-left: 15px; display: flex;">
                                        <input type="radio" name="layout_of_whip" value="2" v-model="quotation_info.layout_of_whip"> TRENCH
                                    </label>
                                </div>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Rowhead to CBX&nbsp;</span>
                                <div style="display: flex;align-items: center;">
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc1_1">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc1_2">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc1_3">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc1_4">
                                    <i class="text-gray" style="margin: auto 4px;font-size: 22px;font-weight: bold;">=</i>
                                    <span class="form-control" style="min-width: 50px">
                                            {{ parseInt(quotation_info.rtc1_1 ? quotation_info.rtc1_1 : 0) + parseInt(quotation_info.rtc1_2 ? quotation_info.rtc1_2 : 0) + parseInt(quotation_info.rtc1_3 ? quotation_info.rtc1_3 : 0) + parseInt(quotation_info.rtc1_4 ? quotation_info.rtc1_4 : 0) }}ft
                                        </span>
                                    <i class="fa fa-check" :class="quotation_info.rtc1 ? 'text-success' : 'text-gray'" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 100%" v-model="quotation_info.rtc1">
                                </div>
                            </div>

                            <div class="input-group" v-if="quotation_info.layout_of_whip == '2'">
                                <span class="input-group-addon">Rowhead to CBX'</span>
                                <div style="display: flex;align-items: center;">
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc2_1">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc2_2">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc2_3">
                                    <i class="fa fa-plus text-gray" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 50px" v-model="quotation_info.rtc2_4">
                                    <i class="text-gray" style="margin: auto 4px;font-size: 22px;font-weight: bold;">=</i>
                                    <span class="form-control" style="min-width: 50px">
                                            {{ parseInt(quotation_info.rtc2_1 ? quotation_info.rtc2_1 : 0) + parseInt(quotation_info.rtc2_2 ? quotation_info.rtc2_2 : 0) + parseInt(quotation_info.rtc2_3 ? quotation_info.rtc2_3 : 0) + parseInt(quotation_info.rtc2_4 ? quotation_info.rtc2_4 : 0) }}ft
                                        </span>
                                    <i class="fa fa-check" :class="quotation_info.rtc2 ? 'text-success' : 'text-gray'" style="margin: auto 4px"></i>
                                    <input class="form-control" style="width: 100%" v-model="quotation_info.rtc2">
                                </div>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Distance Between Poles</span>
                                <input class="form-control" v-model="quotation_info.distance_between_poles">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">Buffer</span>
                                <input class="form-control" v-model="quotation_info.buffer">
                                <span class="input-group-addon">%</span>
                            </div>


                        </div>

                        <div class="col-md-12">
                            <div><button type="button" class="btn btn-success pull-right" @click="saveSetting">保存</button></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="panel" :class="whips[0]['have_s'] == 1 ? 'panel-info' : 'panel-default'" v-for="whips in quotation_info.whips">
                        <div class="panel-heading" style="display: flex;justify-content: space-between;padding: 6px 23px">
                            <h3 class="panel-title" style="line-height: 28px">
                                <i v-if="whips[0]['component_comb']['image']" @click="showImage('/uploads/'+whips[0]['component_comb']['image'])" style="cursor: pointer" class="fa fa-image"></i>
                                <span v-if="whips[0]['have_s'] == 1">S </span>{{ whips[0]['component_comb']['name'] }}
                            </h3>
                            <a class="btn btn-default btn-sm" @click="showAddWhip(whips[0]['have_s'],whips[0]['component_comb_id'])"><i class="fa fa-plus text-success"></i></a>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover" style="margin-bottom: unset">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th v-if="whips[0]['have_s'] == 1">Form</th>
                                        <th v-if="whips[0]['have_s'] == 0">Multiple</th>
                                        <th>Rowhead to CBX</th>
                                        <th>Cable length(ft)</th>
                                        <th>
                                            Buffer<span style="margin-left: 2px" v-if="quotation_info.buffer">{{ quotation_info.buffer }}%</span>
                                        </th>
                                        <th>Total length</th>
                                        <th>Cost RMB</th>
                                        <th>Unit weight (kg)</th>
                                        <th style="width: 56px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="whip in whips">
                                        <td>{{ whip.id }}</td>
                                        <td v-if="whips[0]['have_s'] == 1">{{ whip.remarks }}</td>
                                        <td v-if="whips[0]['have_s'] == 0">{{ whip.multiple }}</td>
                                        <td>{{ whip.rtc }}</td>
                                        <td>{{ whip.cl }}</td>
                                        <td>{{ whip.buffer }}</td>
                                        <td>{{ whip.total_length }}</td>
                                        <td>{{ whip.cost }}</td>
                                        <td>{{ whip.weight }}</td>
                                        <td style="float: right"><a class="btn btn-sm btn-default table-field-remove" @click="deleteWhip(whip.id)"><i class="fa fa-trash text-danger"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        <div class="col-md-12">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" v-for="(item,index) in quotation_info.whip_block_body" :class="index==0?'active':''">
                    <a :href="'#whip_'+item.block_id" role="tab" data-toggle="tab" style="font-weight: bold"><span :class="item.saved ? '' : 'text-danger'">{{ item.name }}</span></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" :class="item_index==0?'active':''" :id="'whip_'+item.block_id" v-for="(item,item_index) in quotation_info.whip_block_body">

                    <div style="overflow: auto">
                        <table class="table table-hover" style="width: auto">
                            <thead>
                            <tr>
                                <th style="text-align: center;min-width: 50px">
                                    <p style="margin-bottom: unset">ID</p>
                                    <p style="margin-bottom: unset">Name</p>
                                    <p style="margin-bottom: unset">NO.</p>
                                    <p style="margin-bottom: unset">Length</p>
                                </th>
                                <th style="text-align: center;min-width: 30px" v-for="(whip,title_index) in quotation_info.whip_block_title">
                                    <p style="margin-bottom: unset">{{ whip.id }}</p>
                                    <p v-if="whip.have_s" style="margin-bottom: unset;color:#3c8dbc;">{{ 'S '+whip.name }}</p>
                                    <p v-else style="margin-bottom: unset;">{{ whip.name }}</p>
                                    <p style="margin-bottom: unset">{{ title_index + 1 }}</p>
                                    <p style="margin-bottom: unset">{{ whip.length }}</p>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(whip,whip_index) in item.whip">
                                    <th style="max-width: 70px;text-align: center;height: 20px">CBX-{{ whip_index + 1 }}</th>
                                    <td v-for="(i,index) in whip"  style="text-align: center">
                                        <span>
                                            <input style="max-width: 30px;text-align: center;height: 20px;" :class="whip[index]['value'] ? 'has-number':''" v-model="whip[index]['value']">
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <a class="btn" :class="item.saved ? 'btn-info' : 'btn-success'" style="margin-bottom: 20px" @click="saveWhipBlock(item_index)">{{ item.saved ? 'Update' : 'Create' }}</a>
                    </div>


                </div>
            </div>

        </div>
        <div style="clear: both"></div>


        <div class="modal fade in" id="addWhip">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Whip：<span class="po_client_no"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="col-md-12">

                                <div class="input-group">
                                    <div class="col-sm-8 w-200" style="display: flex;margin-left: -15px">
                                        <label style="display: flex;">无S&nbsp;<input name="have_s" type="radio" value="0" v-model="form_data.have_s"></label>
                                        <label style="margin-left: 15px; display: flex;">有S&nbsp;<input name="have_s" type="radio" value="1" v-model="form_data.have_s"></label>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon">零件组合</span>
                                    <select v-model="form_data.component_comb_id" class="form-control">
                                        <option v-for="comb in component_comb_list" :value="comb.id">{{ comb.name }}</option>
                                    </select>
                                </div>

                                <table class="table table-hover" style="margin-left: -8px;margin-bottom: unset">
                                    <tbody>
                                        <tr>
                                            <th v-if="form_data.have_s == '1'">Form</th>
                                            <th v-if="form_data.have_s == '1'">未知</th>
                                            <th v-if="form_data.have_s == '0'">Multiple</th>
                                            <th style="width: 60px">Action</th>
                                        </tr>
                                        <tr v-for="(list,index) in form_data.list">
                                            <template v-if="form_data.have_s == '1'">
                                                <td>
                                                    <input class="form-control" v-model="list.form">
                                                </td>
                                                <td >
                                                    <div class="input-group" style="margin-bottom: unset">
                                                        <input class="form-control" v-model="list.digital_a">
                                                        <span class="input-group-addon" style="border-left: unset;border-right: unset"><i class="fa fa-plus"></i></span>
                                                        <span class="input-group-addon">{{ quotation_info.distance_between_poles }}</span>
                                                        <span class="input-group-addon" style="border-left: unset;border-right: unset"><i class="fa fa-remove"></i></span>
                                                        <input class="form-control" v-model="list.digital_b">
                                                    </div>
                                                </td>
                                            </template>
                                            <td v-if="form_data.have_s == '0'">
                                                <input class="form-control" v-model="list.multiple" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-danger table-field-remove" @click="deleteList(index)"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" @click="addList"><i class="fa fa-plus"></i></button>
                            </div>

                            <div class="col-md-12">
                                <div><button type="button" class="btn btn-success pull-right" @click="saveWhip">保存</button></div>
                            </div>

                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div id="img-mask" style="display: none">
            <div class="mfp-bg mfp-ready" style="z-index: 8888"></div>
            <div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-ready" tabindex="-1" style="overflow: hidden auto;z-index: 9999">
                <div class="mfp-container mfp-s-ready mfp-image-holder">
                    <div class="mfp-content">
                        <div class="mfp-figure">
                            <button title="Close (Esc)" type="button" class="mfp-close" @click="closeImage">×</button>
                            <figure><img class="mfp-img" alt="undefined"
                                         src=""
                                         style="max-height: 596px;">
                                <figcaption>
                                    <div class="mfp-bottom-bar">
                                        <div class="mfp-title"></div>
                                        <div class="mfp-counter"></div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                    <div class="mfp-preloader">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            quotation_info:{},
            component_comb_list:[],
            form_data:{
                have_s:"0",
                quotation_id:'',
                component_comb_id:'',
                list:[]
            }
        }
    },
    mounted(){
        $('#option_cab').popover({
            trigger : 'hover',
            html:true,
            content:"<img style='width:300%' src='/images/option_cab.png'>"
        });

        $('#option_trench').popover({
            trigger : 'hover',//鼠标以上时触发弹出提示框
            html:true,//开启html 为true的话，data-content里就能放html代码了
            content:"<img style='width:300%' src='/images/option_trench.png'>"
        });
    },
    props: [
        'quotation'
    ],
    created() {
        this.quotation_info = JSON.parse(this.quotation);
        this.form_data.quotation_id = this.quotation_info.id;

        axios.get('/admin/component-comb-list').then(response => {
            this.component_comb_list = response.data.data
        })
    },
    methods: {
        closeImage(){
            $('#img-mask').css('display', 'none');
            $('#img-mask .mfp-img').attr('src', '');
        },
        showImage(url){
            $('#img-mask').css('display', 'block');
            $('#img-mask .mfp-img').attr('src', url);
        },
        addList(){
            this.form_data.list.push({form:'', multiple:'', digital_a:'', digital_b:''})
        },

        deleteList(index){
            this.form_data.list.splice(index,1)
        },

        showAddWhip(have_s,component_comb_id){
            this.form_data.list = []
            this.form_data.have_s = have_s
            this.form_data.component_comb_id = component_comb_id
            $('#addWhip').modal('show')
        },

        saveWhip(){
            axios({
                method: 'post',
                url: '/admin/projects/save/whip',
                data: this.form_data
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

        deleteWhip(id){
            swal({
                title: '确定删除该 Whip ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/delete/quotation/'+this.quotation_info.id+'/whip/'+ id,
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

        saveWhipBlock(index){
            swal({
                title: '确定保存?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/save/whip/block',
                        data:this.quotation_info.whip_block_body[index]
                    }).then(response => {
                        if (response.data.status) {
                            swal('SUCCESS', '保存成功', 'success').then(() => {
                                // location.reload()
                            })
                        }
                    }).catch(error => {
                        toastr.error(error.response.data.message)
                    });
                }
            })
        },

        saveSetting(){
            axios({
                method: 'post',
                url: '/admin/projects/save/setting/'+this.quotation_info.id,
                data: {
                    layout_of_whip:this.quotation_info.layout_of_whip,
                    rtc1:this.quotation_info.rtc1,
                    rtc1_1:this.quotation_info.rtc1_1,
                    rtc1_2:this.quotation_info.rtc1_2,
                    rtc1_3:this.quotation_info.rtc1_3,
                    rtc1_4:this.quotation_info.rtc1_4,
                    rtc2:this.quotation_info.rtc2,
                    rtc2_1:this.quotation_info.rtc2_1,
                    rtc2_2:this.quotation_info.rtc2_2,
                    rtc2_3:this.quotation_info.rtc2_3,
                    rtc2_4:this.quotation_info.rtc2_4,
                    distance_between_poles:this.quotation_info.distance_between_poles,
                    buffer:this.quotation_info.buffer
                }
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
    }
}
</script>

<style scoped>
.input-group{
    margin-bottom: 10px;
}

.has-number{
    font-weight: bold;
    border-color: #1a2226;
    color:#1a2226 !important;
}
</style>

