<style type="text/css">
    .btn {padding: 1px 5px;}
</style>
<input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>">
<div class="row">
    <div class="col-lg-12">
        <table id="myDataTable" class="table table-hover table-striped" role="grid" style="width: 100%;">
            <thead>
                <tr role="row">
                    <th>#</th>
                    <th>Tgl.Surat</th>
                    <th>No.Surat</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Perihal</th>
                    <th class="text-center">Status</th>
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
                <h4 class="modal-title"><span id="labelDialog">Lihat Surat Masuk</span></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var Mail = {
        userId: 0,
        refreshTime: 60000,
        timer: null,
        dataTableObject: null,
        init: function(){
            var _this = this;
            _this.userId = parseInt($('#user-id').val());
            
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
                    url: "<?php echo get_action_url('service/incoming/index'); ?>",
                    data: {userId: _this.userId},
                    dataSrc: "items"
                },
                columns:[
                    {data: "dokumen", render: function(data){
                            var result = '';
                            if (data){
                                result+= '<div class="btn-group">';
                                result+= '<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download"><i class="fa fa-download"></i> <span class="caret"></span></button>';
                                result+= '<ul class="dropdown-menu">';
                                for(var i in data){
                                    var doc = data[i];
                                    result += '<li><a href="'+doc.download_url+'" target="blank">'+doc.orig_name+'</a></li>';
                                }
                                result += '</ul></div>';
                            }
                            return result;
                    }},
                    {data: "tanggal_surat"},
                    {data: "nomor_surat"},
                    {data: "pengirim"},
                    {data: "penerima", render: function(data){
                            return data.nama;
                    }},
                    {data: "perihal"},
                    {data: "status", class: "text-center"},
                    {data: "id", class:"text-right", render: function(data,type,row){
                            var result = '';
                            result += '<button class="btn btn-info btn-xs btn-view" data-id="'+data+'" title="Lihat"><i class="fa fa-eye"></i></button>';
                            if (row.editable){
                                result += '<a class="btn btn-success btn-xs" href="<?php echo site_url('incoming/register/edit'); ?>/'+data+'" title="Edit"><i class="fa fa-pencil"></i></a>';
                            }
                            if (row.disposisable){
                                result += '<a class="btn btn-primary btn-xs" href="<?php echo site_url('incoming/register/disposisi'); ?>/'+data+'" title="Disposisi"><i class="fa fa-share"></i></a>';
                            }
                            
                            return result;
                    }}
                ]
            });
            
            $('#myDataTable').on('click','.btn-view', function(){
                var item_id = $(this).attr('data-id');
                
                var $modal = $('#myDialogModal');
                $modal.find('.modal-body').load('<?php echo get_action_url('incoming/register/view'); ?>/'+item_id, function(){
                    $modal.modal('show');
                    _this.reloadDataTable();
                });
            });
            
            $('#myDialogModal').on('hide.bs.modal', function (event) {
                _this.reloadDataTable();
            });
            
            this.timer = setTimeout(function(){ _this.reloadDataTable() }, _this.refreshTime);
        },
        reloadDataTable: function(){
            var _this = this;
            if (this.dataTableObject){
                this.dataTableObject.ajax.reload(null,false);
                
                //reset timer
                if (this.timer){
                    clearTimeout(this.timer);
                }
                this.timer = setTimeout(function(){ _this.reloadDataTable() }, _this.refreshTime);
            }
        },
        createNew: function(){
            window.location = '<?php echo get_action_url('incoming/register/edit'); ?>';
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
    
</script>