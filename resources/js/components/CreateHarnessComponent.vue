<template>
    <div class="box box-info">

        <div class="box-header with-border">
            <h3 class="box-title">Create</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 5px">
                    <a href="/admin/harnesses" class="btn btn-sm btn-default" title="List">
                        <i class="fa fa-list"></i><span class="hidden-xs"> List</span>
                    </a>
                </div>

            </div>
        </div>

        <form class="form-horizontal">
            <div class="box-body">
                <div class="fields-group">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">Min length</label>
                            <div class="col-sm-8 w-200">
                                <input class="form-control" v-model="form_data.min_length">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">Max length</label>
                            <div class="col-sm-8 w-200">
                                <input class="form-control" v-model="form_data.max_length">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">有无 Fuse</label>
                            <div class="col-sm-8 w-200" style="display: flex">
                                <label style="display: flex">有<input name="have_fuse" type="radio" value="1" v-model="form_data.have_fuse"></label>
                                <label style="margin-left: 15px;display: flex">无<input name="have_fuse" type="radio" value="0" v-model="form_data.have_fuse"></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">String</label>
                            <div class="col-sm-8 w-200">
                                <input class="form-control" v-model="form_data.string">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">Outlet length</label>
                            <div class="col-sm-8 w-200">
                                <input class="form-control" v-model="form_data.outlet_length">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 asterisk control-label">Module</label>
                            <div class="col-sm-8 w-200">
                                <select class="form-control" v-model="form_data.module">
                                    <option value="1">JM</option>
                                    <option value="2">F4</option>
                                    <option value="3">F6</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Component</label>
                            <div class="col-sm-8" style="overflow: auto">
                                <table class="table table-hover" style="margin-bottom:unset">
                                    <thead>
                                        <tr>
                                            <th>Component</th>
                                            <th>Length</th>
                                            <th>Quantity</th>
                                            <th>Remarks</th>
                                            <th class="pull-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in form_data.components">
                                            <td>
                                                <select class="form-control" v-model="item.id" v-on:change="changeComponent(item.id, index)">
                                                    <option :value="component.id" v-for="component in components">
                                                        【{{ component.part_type }}】{{ component.name }}：{{ component.currency }}{{ component.price }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td><input :id="'component_'+index" type="number" class="form-control" v-model="item.length" disabled></td>
                                            <td><input type="number" class="form-control" v-model="item.quantity"></td>
                                            <td><input type="text" class="form-control" v-model="item.remarks"></td>
                                            <td style="vertical-align: middle;">
                                                <a class="btn btn-sm btn-danger pull-right" @click="deleteComponent(index)"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="pull-right" style="padding-right: 8px;margin-bottom: 20px; margin-top: 10px">
                                    <a @click="addComponent" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Remarks</label>
                            <div class="col-sm-8">
                                <textarea rows="5" class="form-control" style="resize: none;" v-model="form_data.remarks"></textarea>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <a @click="submit" class="btn btn-primary">Submit</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                components:[],
                form_data:{
                    have_fuse:'1',
                    min_length:'',
                    max_length:'',
                    string:'',
                    outlet_length:'',
                    module:'',
                    remarks:'',
                    components:[
                        {id:'', length:'', quantity:1, remarks:''}
                    ],
                },
            }
        },

        mounted(){},
        created() {
            axios.get('/admin/component-list').then(response => {
                this.components = response.data
            })
        },
        methods: {
            changeComponent(id, index){
              for (let i in this.components){
                  if(this.components[i]['id'] == id){
                      if(this.components[i]['type'] == 2 || this.components[i]['type'] == 3){
                          this.form_data.components[index]['length'] = 1
                          $("#component_"+index).attr("disabled",false);
                      }else{
                          this.form_data.components[index]['length'] = ''
                          $("#component_"+index).attr("disabled",true);
                      }
                  }
              }
            },

            //添加零件
            addComponent(){
                let end = this.form_data.components[this.form_data.components.length-1]
                if(end.id == ''){
                    swal ( "INFO" ,  "请选择零件" ,  "info" )
                    return
                }
                this.form_data.components.push({id:'', length:'', quantity:1, remarks:''});
            },

            //删除零件
            deleteComponent(index){
                if(this.form_data.components.length == 1){
                    swal ( "INFO" ,  "至少选择一个零件" ,  "info" )
                    return
                }
                this.form_data.components.splice(index, 1)
            },

            submit(){
                $('#loading').css('display', 'block');
                axios({
                    method: 'post',
                    url: '/admin/harnesses',
                    data: this.form_data
                }).then(response => {
                    $('#loading').css('display', 'none');
                    if(response.data.status){
                        swal('SUCCESS', '保存成功', 'success').then(() => {
                            this.form_data = {
                                min_length:'',
                                max_length:'',
                                string:'',
                                outlet_length:'',
                                module:'',
                                remarks:'',
                                components:[
                                    {id:'', length:1, quantity:1}
                                ],
                            }
                        })
                    }
                }).catch(error => {
                    $('#loading').css('display', 'none');
                    toastr.error(error.response.data.message)
                });
            },
        },
    }
</script>
<style>
    .w-200 {
        width: 200px;
    }
</style>
