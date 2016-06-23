<div class="row">
    <div class="col-lg-12">
        <table id="myDataTable" class="table table-hover table-striped" role="grid">
            <thead>
                <tr role="row">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Bidang</th>
                    <th>Group</th>
                    <th>Cabang</th>
                    <th>NIK</th>
                    <th>Email</th>
                    <th class="text-right">#</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    var User = {
        dataTableObject: null,
        init: function(){
            var _this = this;
            
            this.dataTableObject = $('#myDataTable').DataTable({
                pageLength: 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                searching: true,
                dom: 'B<"clear">lfrtip',
                buttons:{
                    name: 'primary',
                    buttons: [
                        { text: '<i class="fa fa-plus"></i> New User', action: function(){
                            _this.createNew();
                        }},
                        { text: '<i class="fa fa-recycle"></i> Reload', action: function(){
                            _this.reloadDataTable();
                        }}
                    ]
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo config_item('service_url'); ?>/user",
                    dataSrc: "items"
                },
                columns:[
                    {data: "id", class:"text-center"},
                    {data: "username"},
                    {data: "nama"},
                    {data: "bidang", render: function(data,type,row){
                            if (data){
                                return data.nama;
                            }else{
                                return '-';
                            }
                    }},
                    {data: "grup", render: function(data,type,row){
                            if (data){
                                return data.nama;
                            }else{
                                return '-';
                            }
                    }},
                    {data: "wilayah", render: function(data,type,row){
                            if (data){
                                return data.nama;
                            }else{
                                return '-';
                            }
                    }},
                    {data: "nik"},
                    {data: "email"},
                    {data: "id", class: "text-right", render:function(data,type, row){
                            var result = '';
                            result += '<button type="button" class="btn btn-xs btn-success btn-edit" data-id="'+data+'" title="Edit"><i class="fa fa-pencil"></i></button>';
                            return result;
                    }}
                ]
            });
            
            $('#myDataTable').on('click','.btn-edit', function(){
                var id = $(this).attr('data-id');
                window.location = '<?php echo get_action_url('system/user/edit'); ?>/'+id;
            });
        },
        reloadDataTable: function(){
            if (this.dataTableObject){
                this.dataTableObject.ajax.reload(null,false);
            }
        },
        createNew: function(){
            window.location = '<?php echo get_action_url('system/user/edit'); ?>';
        }
    };
    $(document).ready(function(){
        User.init();
    });
    
</script>