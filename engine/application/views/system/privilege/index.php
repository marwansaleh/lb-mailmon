<div class="row">
    <div class="col-lg-12">
        <table id="myDataTable" class="table table-hover table-striped" role="grid">
            <thead>
                <tr role="row">
                    <th>Akses</th>
                    <th>Module</th>
                    <th class="""text-center">Icon</th>
                    <th>Link</th>
                    <th class="text-center">Hidden</th>
                    <th class="text-right">#</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    var Menu = {
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
                        { text: '<i class="fa fa-plus"></i> New Mail', action: function(){
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
                    url: "<?php echo get_action_url('service/menu/index'); ?>",
                    data: {userId: _this.userId},
                    dataSrc: "items"
                },
                columns:[
                    {data: "caption"},
                    {data: "module.name"},
                    {data: "icon", class:"text-center"},
                    {data: "link"},
                    {data: "hidden", class: "text-center", render: function(data){
                            if (data){
                                return '<i class="fa fa-check"></i>';
                            }else{
                                return '<i class="fa fa-remove"></i>';
                            }
                    }},
                    {data: "id", class:"text-right", render: function(data,type,row){
                            var result = '';
                            result += '<a class="btn btn-success btn-xs" href="<?php echo site_url('system/menu/edit'); ?>/'+data+'" title="Edit"><i class="fa fa-pencil"></i></a>';
                            result += '<button type="button" class="btn btn-danger btn-xs btn-remove" data-id="'+data+'" title="Delete"><i class="fa fa-remove"></i></button>';
                            
                            return result;
                    }}
                ]
            });
            
            $('#myDataTable').on('click','.btn-remove', function(){
                var id = $(this).attr('data-id');
                if (confirm('Hapus menu berikut ini ? ')){
                    $.ajax({
                        url: '<?php echo get_action_url('service/menu/index'); ?>/'+id,
                        type: 'DELETE',
                        dataType: 'json'
                    }).then(function(data){
                        if (data.status){
                            _this.reloadDataTable();
                        }else{
                            alert(data.message);
                        }
                    });
                }
            });
        },
        reloadDataTable: function(){
            if (this.dataTableObject){
                this.dataTableObject.ajax.reload(null,false);
            }
        },
        createNew: function(){
            window.location = '<?php echo get_action_url('system/menu/edit'); ?>';
        }
    };
    $(document).ready(function(){
        Menu.init();
    });
    
</script>