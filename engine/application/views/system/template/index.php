<div class="row">
    <div class="col-lg-12">
        <table id="myDataTable" class="table table-hover table-striped" role="grid" style="width: 100%;">
            <thead>
                <tr role="row">
                    <th>Nama</th>
                    <th>Header</th>
                    <th>Footer</th>
                    <th class="text-right">#</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myDialogModal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="labelDialog">Lihat Surat</span></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var Template = {
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
                        { text: '<i class="fa fa-plus"></i> New Template', action: function(){
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
                    url: "<?php echo get_action_url('service/template'); ?>",
                    dataSrc: "items"
                },
                columns:[
                    {data: "nama"},
                    {data: "header"},
                    {data: "footer"},
                    {data: "id", class:"text-right", render: function(data,type,row){
                            var result = '<a class="btn btn-success btn-xs" href="<?php echo site_url('system/template/edit'); ?>/'+data+'" title="Edit"><i class="fa fa-pencil"></i></a>';
                            result += '<button type="button" class="btn btn-warning btn-xs btn-delete" data-id="'+data+'" title="Delete"><i class="fa fa-remove"></i></button>';
                            
                            return result;
                    }}
                ]
            });
            
            $('#myDataTable').on('click','.btn-delete', function(){
                var item_id = $(this).attr('data-id');
                
                if (confirm('Hapus template terpilih ?')){
                    $.ajax({
                        type: 'DELETE',
                        url: '<?php echo get_action_url('service/template/delete'); ?>/'+item_id,
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
            var _this = this;
            if (this.dataTableObject){
                this.dataTableObject.ajax.reload(null,false);
            }
        },
        createNew: function(){
            window.location = '<?php echo get_action_url('system/template/edit'); ?>';
        }
    };
    $(document).ready(function(){
        Template.init();
    });
    
</script>