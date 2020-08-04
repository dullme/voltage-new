<template>
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">Create</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a href="/admin/typicals" class="btn btn-sm btn-default" title="List">
                        <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                    </a>
                </div>

                <div class="btn-group pull-right" style="margin-right: 5px">
                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#harnessInfo">
                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Harness Info</span>
                    </button>
                </div>

            </div>
        </div>

        <form class="form-horizontal">
            <div class="box-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Color</th>
                        <th>String</th>
                        <th>剩余可摆放数</th>
                        <th>Length</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(harness,key) in harnesses_selected">
                        <td><input :id="key+'_radio_harness'" type="radio" @click="updateCurrentHarness(key)" name="radio_harness"></td>
                        <td><label style="cursor: pointer" :for="key+'_radio_harness'">{{ harness.name }}</label></td>
                        <td>
                            <a class="cube" :style="'background-color:'+ harness.color">
                                <span>{{ harness.harness_key}}</span>
                            </a>
                        </td>
                        <td>{{ harness.string }}</td>
                        <td>{{ harness.remaining }}</td>
                        <td>{{ harness.min_length }} ~ {{ harness.max_length }}</td>
                        <td>{{ harness.remarks }}</td>
                        <td>
                            <a style="cursor: pointer" @click="deleteHarness(key)"><i
                                class="fa fa-remove text-danger"></i></a>
                        </td>
                    </tr>
                    <tr v-if="harnesses_selected.length == 0">
                        <td colspan="8" class="empty-grid" style="padding: 100px;text-align: center;color: #999999">
                            <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" p-id="2076" width="128" height="128"
                                 style="fill: #e9e9e9;">
                                <path
                                    d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z"
                                    p-id="2077"></path>
                            </svg>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <div class="box-footer" v-if="harnesses_selected.length">
                    <div class="col-md-12">
                        <div style="display: flex;align-items: center;padding: 20px 0">
                            <label>几行：</label>
                            <select style="height: 34px;padding: 6px 12px;" v-model="hang">
                                <option :value="i" v-for="i in 50">{{ i }}</option>
                            </select>
                            <label style="margin-left: 20px">几列：</label>
                            <select style="height: 34px;padding: 6px 12px;" v-model="lie">
                                <option :value="i" v-for="i in 100">{{ i }}</option>
                            </select>

                            <label style="margin-left: 20px">有没有保险丝：</label>
                            <select style="height: 34px;padding: 6px 12px;" v-model="pos_neg">
                                <option value="fuse">Fuse</option>
                                <option value="nofuse">Nofuse</option>
                            </select>

                            <label style="margin-left: 20px">余量：</label>
                            <input class="form-control" style="width: 100px" v-model="margin">&nbsp;%

                            <button v-if="this.stages.length" type="button" style="margin-left: 20px"
                                    class="btn btn-sm btn-danger" @click="createStage">重新生成行列
                            </button>
                            <button v-else type="button" style="margin-left: 20px" class="btn btn-sm btn-primary"
                                    @click="createStage">生成行列
                            </button>
                        </div>
                    </div>
                    <div v-if="stages.length" style="clear: both">
                        <div class="panel panel-default">
                            <div class="panel-heading">

                                <span v-if='current_harness_key != undefined'>
                                    当前选中颜色：<i style="margin-right: 10px" class="fa fa-circle"
                                              :style="'color: '+ this.harnesses_selected[this.current_harness_key]['color']"></i>
                                    {{ this.harnesses_selected[this.current_harness_key]['remaining'] }}
                                </span>
                                <span v-else>请选择 Harness</span>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover stages" style="width: auto">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">NO.</p>
                                        </th>
                                        <th style="text-align: center;" v-for="(i,index) in motors">
                                            <p style="margin-bottom: unset;cursor: pointer">{{ i.name }}</p>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">长度</p>
                                        </th>
                                        <th style="text-align: center;" v-for="(i,index) in motors">
                                            <p style="margin-bottom: unset;cursor: pointer"
                                               :class="i.number == 0 ? 'color_gray' :''"
                                               @click="setMotor(index)">{{ i.number }}</p>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(stage,s_index) in stages">
                                        <th style="max-width: 70px;text-align: center;height: 20px">{{ s_index + 1 }}
                                        </th>
                                        <td v-for="(item,i_index) in stage"
                                            style="text-align: center;vertical-align: middle">
                                            <a v-if="i_index%2 == 0" class="cube"
                                               :style="item['color'] ? 'background-color:'+item['color'] : ''"
                                               @click="changeCube(s_index, i_index)">
                                                <span v-if="item['motor']" class="cube cube_motor is_motor">{{ item['harness_key'] }}</span>
                                                <span v-else >{{ item['harness_key'] }}</span>
                                            </a>
                                            <a v-else style="display: flex;justify-content: center">
                                                <span class="cube cube_motor"
                                                      :class="item['motor']?'is_motor':''"></span>
                                            </a>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <button type="button" @click="stageSave">保存当前格局</button>
                            </div>
                        </div>
                    </div>


                    <!-- 保存的格局Fuse START -->
                    <div style="clear: both" v-if="saved_data.fuse.pos_neg">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span>有没有保险丝：{{ saved_data.fuse.pos_neg == 'fuse' ? 'Fuse' : 'Nofuse' }}</span>
                                <button @click="deleteSavedData('fuse')" class="btn btn-xs btn-danger pull-right"
                                        type="button"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover stages" style="width: auto">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">NO.</p>
                                        </th>
                                        <th style="text-align: center;" v-for="(i,index) in saved_data.fuse.motors">
                                            <p style="margin-bottom: unset;cursor: pointer">{{ i.name }}</p>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">长度</p>
                                        </th>
                                        <th style="text-align: center;" v-for="(i,index) in saved_data.fuse.motors">
                                            <p style="margin-bottom: unset;cursor: pointer"
                                               :class="i.number == 0 ? 'color_gray' :''">{{ i.number }}</p>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(stage,s_index) in saved_data.fuse.stages">
                                        <th style="max-width: 70px;text-align: center;height: 20px">{{ s_index + 1 }}
                                        </th>
                                        <td v-for="(item,i_index) in stage"
                                            style="text-align: center;vertical-align: middle">
                                            <a v-if="i_index%2 == 0" class="cube"
                                               :style="item['color'] ? 'background-color:'+item['color'] : ''">
                                                <span v-if="item['motor']" class="cube cube_motor is_motor">{{ item['harness_key'] }}</span>
                                                <span v-else >{{ item['harness_key'] }}</span>
                                            </a>
                                            <a v-else style="display: flex;justify-content: center">
                                                <span class="cube cube_motor"
                                                      :class="item['motor']?'is_motor':''"></span>
                                            </a>

                                        </td>
                                        <td>
                                            <span v-for="fuse in saved_data.fuse.res[s_index]">
                                                <i class="fa fa-circle"
                                                   :style="'font-size:12px;color: '+fuse.color"></i>
                                                {{ fuse.length + fuse.length * parseInt(margin)/100 }}
                                            </span>
                                        </td>
                                        <td><input v-if="saved_data.fuse.res[s_index].length" type="checkbox" @click="checkbox('fuse', s_index ,$event)"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- 保存的格局Fuse END -->


                    <!-- 保存的格局Nofuse START -->
                    <div style="clear: both" v-if="saved_data.nofuse.pos_neg">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span>有没有保险丝：{{ saved_data.nofuse.pos_neg == 'fuse' ? 'Fuse' : 'Nofuse' }}</span>
                                <button @click="deleteSavedData('nofuse')" class="btn btn-xs btn-danger pull-right"
                                        type="button"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover stages" style="width: auto">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">NO.</p>
                                        </th>
                                        <th style="text-align: center;" v-for="i in saved_data.nofuse.motors">
                                            <p style="margin-bottom: unset;cursor: pointer">{{ i.name }}</p>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">
                                            <p style="margin-bottom: unset">长度</p>
                                        </th>
                                        <th style="text-align: center;" v-for="i in saved_data.nofuse.motors">
                                            <p style="margin-bottom: unset;cursor: pointer"
                                               :class="i.number == 0 ? 'color_gray' :''">{{ i.number }}</p>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(stage,s_index) in saved_data.nofuse.stages">
                                        <th style="max-width: 70px;text-align: center;height: 20px">{{ s_index + 1 }}
                                        </th>
                                        <td v-for="(item,i_index) in stage"
                                            style="text-align: center;vertical-align: middle">
                                            <a v-if="i_index%2 == 0" class="cube"
                                               :style="item['color'] ? 'background-color:'+item['color'] : ''">
                                                <span v-if="item['motor']" class="cube cube_motor is_motor">{{ item['harness_key'] }}</span>
                                                <span v-else >{{ item['harness_key'] }}</span>
                                            </a>
                                            <a v-else style="display: flex;justify-content: center">
                                                <span class="cube cube_motor"
                                                      :class="item['motor']?'is_motor':''"></span>
                                            </a>

                                        </td>
                                        <td>
                                            <span v-for="nofuse in saved_data.nofuse.res[s_index]">
                                                <i class="fa fa-circle"
                                                   :style="'font-size:12px;color: '+nofuse.color"></i>
                                                {{ nofuse.length + parseInt(margin) }}
                                            </span>
                                        </td>
                                        <td><input v-if="saved_data.nofuse.res[s_index].length" type="checkbox" @click="checkbox('nofuse', s_index ,$event)"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- 保存的格局Nofuse END -->

                    <div class="btn-group" v-if="saved_data.fuse.res || saved_data.nofuse.res"><a class="btn btn-primary" @click="submit">Submit</a></div>

                </div>

            </div>
        </form>

        <div class="modal fade in" id="harnessInfo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Harness Info：<span class="po_client_no"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 asterisk control-label">串数</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" v-model="string">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 asterisk control-label">主串长度</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" v-model="length">
                                    </div>
                                    <button type="button" class="btn btn-primary" @click="searchHarness">查询</button>
                                </div>
                            </div>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>String</th>
                                <th>Length</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(harness,key) in harnesses">
                                <td>{{ harness.name }}</td>
                                <td>{{ harness.string }}</td>
                                <td>{{ harness.min_length }} ~ {{ harness.max_length }}</td>
                                <td>{{ harness.remarks }}</td>
                                <td>
                                    <a style="cursor: pointer" @click="addHarness(key)" v-if="harness.checked == false"><i
                                        class="fa fa-plus text-primary"></i></a>
                                    <a v-else><i class="fa fa-check text-success"></i></a>
                                </td>
                            </tr>
                            <tr v-if="harnesses.length == 0">
                                <td colspan="5" class="empty-grid"
                                    style="padding: 100px;text-align: center;color: #999999">
                                    <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                         xmlns="http://www.w3.org/2000/svg" p-id="2076" width="128" height="128"
                                         style="fill: #e9e9e9;">
                                        <path
                                            d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z"
                                            p-id="2077"></path>
                                    </svg>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
                saved_data: {
                    'fuse': {
                        'ids':[],
                    },
                    'nofuse': {
                        'ids':[],
                    }
                },
                pos_neg: 'fuse',
                current_harness_key: undefined,
                harnesses: [],
                harnesses_selected: [],
                string: '',
                length: '',
                stages: [],//舞台
                motors: [],//电机
                hang: '',
                lie: '',
                margin:0,
                color: [
                    '#00204a', '#5e63b6', '#ca82f8',
                    '#17b978', '#ff6f3c', '#00adb5',
                    '#b61aae', '#ff9a00', '#ff165d',
                    '#cdb30c', '#62760c', '#535204',
                    '#523906', '#848ccf', '#93b5e1',
                    '#ffe4e4', '#be5683', '#838383',
                    '#7fdbda', '#ade498', '#ede682',
                    '#febf63', '#fa1616', '#12cad6',
                ]
            }
        },

        mounted() {
        },
        created() {
            axios.get('/admin/component-list').then(response => {
                this.components = response.data
            })
        },
        methods: {
            searchHarness() {
                let ids = [];
                if (this.harnesses_selected.length) {
                    this.harnesses_selected.forEach((item, key) => {
                        ids[key] = item['id'];
                    })
                }
                axios({
                    method: 'post',
                    url: '/admin/search-harness',
                    data: {
                        string: this.string,
                        length: this.length,
                        ids: ids
                    }
                }).then(response => {
                    this.harnesses = response.data.data
                }).catch(error => {
                    toastr.error(error.response.data.message)
                });
            },

            addHarness(key) {
                swal({
                    title: '请输入数量',
                    input: 'number',
                    type: 'info',
                }).then((value) => {
                    let number = parseInt(value.value)
                    if (number <= 0 || number > 99 || isNaN(number)) {
                        swal('ERROR', '数量必须为整数', 'error')
                    } else {
                        let need_add = true;
                        this.harnesses[key]['checked'] = true;
                        this.harnesses_selected.forEach((item) => {
                            if (item.id == this.harnesses[key]['id']) {
                                need_add = false;
                            }
                        })

                        if (need_add) { //第一次添加 Harness
                            let color = this.color.pop()
                            for (let i = 0; i < number; i++) {
                                let harness = {
                                    checked:this.harnesses[key]['checked'],
                                    id:this.harnesses[key]['id'],
                                    max_length:this.harnesses[key]['max_length'],
                                    min_length:this.harnesses[key]['min_length'],
                                    name:this.harnesses[key]['name'],
                                    remarks:this.harnesses[key]['remarks'],
                                    string:this.harnesses[key]['string'],
                                    remaining : this.harnesses[key]['string'],
                                    harness_key : i,
                                    color : color
                                }

                                this.harnesses_selected.push(harness)
                            }
                            toastr.success('添加成功')
                        } else { //已经存在harness
                            swal('INFO', '已经添加过了', 'info')
                        }
                    }
                });
            },

            deleteHarness(key) {
                this.current_harness_key = undefined
                this.saved_data = {'fuse': {ids:[]}, 'nofuse': {ids:[]}}

                this.createStage()
                this.harnesses.forEach((item, index) => {
                    if (item.id == this.harnesses_selected[key]['id']) {
                        this.harnesses[index]['checked'] = false;
                    }
                })
                let id = this.harnesses_selected[key]['id']
                let new_selected = [];
                for(let index in this.harnesses_selected){
                    if(this.harnesses_selected[index]['id'] != id){
                        new_selected.push(this.harnesses_selected[index])
                    }
                }

                this.harnesses_selected = new_selected
                $('input[name=radio_harness]').attr("checked",false);
                this.$forceUpdate()
                swal('SUCCESS', '删除成功，舞台已被重新初始化', 'success')
            },

            updateCurrentHarness(key) {
                this.current_harness_key = key
            },

            //创建舞台
            createStage() {
                //重置剩余可摆放数
                for (let i = 0; i < this.harnesses_selected.length; i++) {
                    let is_used = false

                    let all_ids = this.saved_data.fuse.ids.concat(this.saved_data.nofuse.ids)
                    for(let id in all_ids){
                        if(all_ids[id] == this.harnesses_selected[i]['id']){
                            is_used = true
                        }
                    }

                    if(is_used){
                        this.harnesses_selected[i]['remaining'] = 0
                    }else{
                        this.harnesses_selected[i]['remaining'] = this.harnesses_selected[i]['string']
                    }
                }

                this.stages = [];
                this.motors = [];
                let lie = this.lie * 2 - 1;
                for (let i = 0; i < this.hang; i++) {
                    this.stages[i] = {}
                    for (let j = 0; j < lie; j++) {
                        let only_motor = j % 2 == 0 ? false : true
                        this.$set(this.stages[i], j, {
                            harness_key: undefined,
                            id: '',
                            color: '',
                            motor: false,
                            only_motor: only_motor,
                            motor_number: 0
                        })
                    }
                }

                let j = 1
                let name = 1
                for (let i = 0; i < lie; i++) {
                    name = (i + 1) % 2 == 0 ? '●' : j++
                    this.motors[i] = {name: name, motor: false, number: 0}
                }

                this.$forceUpdate()
            },

            setMotor(index) {
                if (this.motors[index]['motor']) { //清除设置
                    this.motors[index]['motor'] = false
                    this.motors[index]['number'] = 0
                    for (let i = 0; i < this.stages.length; i++) {
                        this.stages[i][index]['motor'] = false
                        this.stages[i][index]['motor_number'] = 0
                    }
                    this.$forceUpdate()
                } else {//设置电机长度
                    swal({
                        title: '请输入电机长度',
                        input: 'number',
                        type: 'info',
                    }).then((value) => {
                        let number = parseInt(value.value)
                        if (number <= 0 || number > 99 || isNaN(number)) {
                            swal('ERROR', '电机长度为0～99的整数', 'error')
                        } else {
                            this.motors[index]['motor'] = true
                            this.motors[index]['number'] = number
                            for (let i = 0; i < this.stages.length; i++) {
                                this.stages[i][index]['motor'] = true
                                this.stages[i][index]['motor_number'] = number
                            }
                            swal('SUCCESS', '成功设置电机长度为' + number, 'success')
                            this.$forceUpdate()
                        }
                    });
                }
            },

            //修改方块
            changeCube(s_index, i_index) {
                if (i_index % 2 == 0) {
                    if (this.current_harness_key == undefined) {
                        toastr.info('请先选择 Harness')
                    } else if (this.stages[s_index][i_index]['harness_key'] != this.harnesses_selected[this.current_harness_key]['harness_key'] && this.harnesses_selected[this.current_harness_key]['remaining'] == 0) {
                        toastr.info('剩余可摆放数不足')
                    } else {
                        let real_count = this.harnesses_selected[this.current_harness_key]['string']
                        //如果已经被当前颜色填充 去掉当前颜色 并增加可填充剩余数
                        if (this.stages[s_index][i_index]['id'] == this.harnesses_selected[this.current_harness_key]['id'] && this.stages[s_index][i_index]['harness_key'] == this.harnesses_selected[this.current_harness_key]['harness_key']) {
                            this.stages[s_index][i_index]['harness_key'] = undefined
                            this.stages[s_index][i_index]['id'] = ''
                            this.stages[s_index][i_index]['color'] = ''
                            this.harnesses_selected[this.current_harness_key]['remaining'] += 1
                            //防止超过实际可填充数量
                            if (this.harnesses_selected[this.current_harness_key]['remaining'] > real_count) {
                                this.harnesses_selected[this.current_harness_key]['remaining'] = real_count
                            }

                            //当前有其他颜色存在
                        } else if (this.stages[s_index][i_index]['id'] == '') {
                            this.stages[s_index][i_index]['harness_key'] = this.harnesses_selected[this.current_harness_key]['harness_key']
                            this.stages[s_index][i_index]['id'] = this.harnesses_selected[this.current_harness_key]['id']
                            this.stages[s_index][i_index]['color'] = this.harnesses_selected[this.current_harness_key]['color']
                            this.harnesses_selected[this.current_harness_key]['remaining'] -= 1
                            //防止为负数
                            if (this.harnesses_selected[this.current_harness_key]['remaining'] < 0) {
                                this.harnesses_selected[this.current_harness_key]['remaining'] = 0
                            }
                            //可以填充
                        } else {
                            toastr.warning('当前格子已被填充')
                        }
                        this.$forceUpdate()
                    }
                }
            },

            deleteSavedData(str) {
                this.saved_data[str] = { 'ids':[] }
                this.createStage()
            },

            stageSave() {
                let ids = [];

                for (let i = 0; i < this.harnesses_selected.length; i++) {
                    let real_count = this.harnesses_selected[i]['string']
                    if (this.harnesses_selected[i]['remaining'] != 0 && this.harnesses_selected[i]['remaining'] != real_count) {
                        swal('ERROR', '请把剩余点数填充完', 'error')
                        return false
                    }

                    if(this.harnesses_selected[i]['remaining'] != real_count){
                        ids.push(this.harnesses_selected[i]['id'])
                    }
                }

                ids = this.unique22(ids)
                for(let id in ids){
                    for (let i = 0; i < this.harnesses_selected.length; i++) {
                        if (this.harnesses_selected[i]['id'] == ids[id] && this.harnesses_selected[i]['remaining'] != 0) {
                            swal('ERROR', '请把剩余点数填充完', 'error')
                            return false
                        }
                    }
                }


                let is_empty = true;
                for (let i = 0; i<this.stages.length; i++){
                    for(let key in this.stages[i]){
                        if(this.stages[i][key]['id'] != ''){
                            is_empty = false;
                        }
                    }
                }

                if(is_empty){
                    swal('ERROR', '请填充格子', 'error')
                    return false
                }

                $('#loading').css('display', 'block');
                axios({
                    method: 'post',
                    url: '/admin/stage-save',
                    data: {
                        pos_neg: this.pos_neg,
                        stages: this.stages,
                        motors: this.motors
                    }
                }).then(response => {
                    $('#loading').css('display', 'none');
                    if (response.data.status) {
                        swal('SUCCESS', '添加成功', 'success').then(() => {
                            this.saved_data[response.data.data['pos_neg']] = response.data.data
                            this.$forceUpdate()
                            this.createStage()
                        })
                    }
                }).catch(error => {
                    $('#loading').css('display', 'none');
                    toastr.error(error.response.data.message)
                });
            },

            submit(){
                for (let i = 0; i < this.harnesses_selected.length; i++) {
                    if (this.harnesses_selected[i]['remaining'] != 0) {
                        swal('ERROR', '必须把所有点数填充完', 'error')
                        return false
                    }
                }

                $('#loading').css('display', 'block');
                axios({
                    method: 'post',
                    url: '/admin/stage-submit',
                    data: {
                        harnesses_selected: this.harnesses_selected, //被选择的 Harness
                        fuse: this.saved_data.fuse, //有保险丝
                        nofuse: this.saved_data.nofuse, //没有保险丝
                        motors: this.motors, //电机位置
                        margin: this.margin //余量
                    }
                }).then(response => {
                    $('#loading').css('display', 'none');
                    if (response.data.status) {
                        swal('SUCCESS', '添加成功', 'success').then(() => {
                            this.saved_data[response.data.data['pos_neg']] = response.data.data


                            this.saved_data= {
                                'fuse': {
                                    'ids':[],
                                },
                                'nofuse': {
                                    'ids':[],
                                }
                            }
                            this.pos_neg= 'fuse'
                            this.current_harness_key= undefined
                            this.harnesses= []
                            this.harnesses_selected= []
                            this.string= ''
                            this.length= ''
                            this.stages= []//舞台
                            this.motors= []//电机
                            this.hang= ''
                            this.lie= ''
                            this.margin=0
                            this.$forceUpdate()
                        })
                    }
                }).catch(error => {
                    $('#loading').css('display', 'none');
                    toastr.error(error.response.data.message)
                });
            },

            checkbox(name, index, e){
                this.saved_data[name]['check_list'][index] = e.target.checked
            },

            unique22(arr){
                let x = new Set(arr);
                return [...x];
            }
        },
    }
</script>
<style>
    .w-200 {
        width: 200px;
    }

    .cube {
        display: flex;
        width: 25px;
        height: 25px;
        border: 1px solid #000000;
        cursor: pointer;
        justify-content: center;
        align-items: center;
        font-size: 25px;
        color: #FFFFFF;
    }

    .cube_motor {
        border-color: #dadada;
        width: 3px;
    }

    .border_white {
        border-color: #FFFFFF;
    }

    .border_gray {
        border-color: #dadada;
    }

    .color_gray {
        color: #dadada;
    }

    .border_black {
        border-color: #000000;
    }

    .is_motor {
        border-color: #000000;
        background-color: #000000;
    }

    .table-hover > tbody > tr:hover .border_white {
        border-color: #f5f5f5;
    }

    .stages > tbody > tr > td,
    .stages > tbody > tr > th,
    .stages > tfoot > tr > td,
    .stages > tfoot > tr > th,
    .stages > thead > tr > td,
    .stages > thead > tr > th {
        padding: 3px;
    }
</style>
