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
                        <label class="col-sm-2 control-label">Size Dc</label>
                        <div class="col-sm-8">
                            <span class="form-control">{{ project_info.size_dc }}</span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-2 col-xs-3" v-for="(item,index) in project_info.quotations">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <a class="pull-right" style="margin-right: 4px" @click="deleteQuotation(item.id, index)"><i class="fa fa-trash"></i></a>
                                <div class="inner">
                                    <h3>{{ item.name }}</h3>
                                    <p>Total quantity:{{ item.total_quantity }}</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a :href="'/admin/projects/show/quotation/'+item.id" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>



        <div class="modal fade in" id="projectInfo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Quotation：<span class="po_client_no"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">Name</span>
                                    <input class="form-control" v-model="form_data.name">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Total Quantity</span>
                                    <input class="form-control" v-model="form_data.total_quantity">
                                </div>
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
            project_info:{},
            form_data:{
                project_id:'',
                name:'',
                total_quantity:''
            },
        }
    },
    mounted(){

    },
    props: [
        'project'
    ],
    created() {
        this.project_info = JSON.parse(this.project);
        this.form_data.project_id = this.project_info.id;
    },
    methods: {
        deleteQuotation(id, index){
            swal({
                title: '确定删除此 Quotation ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((isConfirm) => {
                if (isConfirm.value == true) {
                    axios({
                        method: 'post',
                        url: '/admin/projects/delete/quotation/' + id,
                    }).then(response => {
                        swal(
                            "SUCCESS",
                            response.data.message,
                            'success'
                        ).then(() => {
                            this.project_info.quotations.splice(index, 1)
                        });
                    }).catch(error => {
                        swal(
                            "WARNING",
                            error.response.data.message,
                            'warning'
                        )
                    });
                }
            })
        },

        save(){
            axios({
                method: 'post',
                url: '/admin/projects/save/quotation',
                data: this.form_data
            }).then(response => {
                if (response.data.status) {
                    swal('SUCCESS', '添加成功', 'success').then(() => {
                        this.project_info.quotations.push(response.data.data)
                        this.form_data.name = ''
                        this.form_data.total_quantity = ''
                        $('#projectInfo').modal('hide')
                    })
                }
            }).catch(error => {
                toastr.error(error.response.data.message)
            })
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

</style>
