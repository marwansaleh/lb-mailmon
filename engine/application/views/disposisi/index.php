<input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>">
<div class="row">
    <div class="col-lg-12">
        <table id="myDataTable" class="table table-hover table-striped" role="grid" style="width: 100%;">
            <thead>
                <tr role="row">
                    <th>Tanggal Kirim</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Keterangan</th>
                    <th class="text-center">Status</th>
                    <th class="text-right" style="min-width: 60px;">#</th>
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
                <h4 class="modal-title"><span id="labelDialog">Kirim Disposisi</span></h4>
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
                        { text: '<i class="fa fa-recycle"></i> Reload', action: function(){
                            _this.reloadDataTable();
                        }}
                    ]
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo get_action_url('service/disposisi/index'); ?>",
                    data: {userId: _this.userId},
                    dataSrc: "items"
                },
                columns:[
                    {data: "waktu_kirim"},
                    {data: "pengirim"},
                    {data: "penerima"},
                    {data: "keterangan"},
                    {data: "status", class:"text-center"},
                    {data: "id", class:"text-right", render: function(data,type,row){
                            var result = '';
                            if (row.viewable){
                                result += '<button class="btn btn-info btn-xs btn-view" data-id="'+data+'" data-tipe="'+row.tipe+'" title="Lihat"><i class="fa fa-eye"></i></button>';
                            }
                            if (row.acceptable){
                                result += '<button class="btn btn-success btn-xs btn-accept" data-id="'+data+'" data-tipe="'+row.tipe+'" title="Diterima"><i class="fa fa-check"></i> Terima</button>';
                            }else{
                                if (row.to_sign){
                                    result += '<button type="button" class="btn btn-primary btn-xs btn-sign" data-id="'+row.mail.id+'" data-tipe="'+row.tipe+'" title="Untuk ditandatangani"><i class="fa fa-check-square-o"></i></button>';
                                }else if (row.disposisable){
                                    result += '<button class="btn btn-success btn-xs btn-disposisi" data-id="'+data+'" data-tipe="'+row.tipe+'" title="Disposisi"><i class="fa fa-share"></i></button>';
                                }
                            }
                            
                            return result;
                    }}
                ]
            });
            
            this.timer = setTimeout(function(){ _this.reloadDataTable() }, _this.refreshTime);
            
            $('#myDataTable').on('click','.btn-accept', function(){
                var item_id = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo get_action_url('service/disposisi/accept'); ?>/'+item_id,
                    type: 'POST',
                    dataType: 'json'
                }).then(function(data){
                    if (data.status){
                        _this.reloadDataTable();
                    }else{
                        alert(data.message);
                    }
                });
            });
            $('#myDataTable').on('click','.btn-sign', function(){
                var item_id = $(this).attr('data-id');
                var item_tipe = $(this).attr('data-tipe');
                
                var $modal = $('#myDialogModal');
                $modal.find('#labelDialog').text('Persetujuan & Penandatanganan');
                var url = '<?php echo get_action_url('outgoing/register/sign'); ?>';
                if (item_tipe=='nodin'){
                    url = '<?php echo get_action_url('nodin/register/sign'); ?>';
                }
                $modal.find('.modal-body').load(url+'/'+item_id, function(){
                    $modal.modal('show');
                    _this.reloadDataTable();
                });
            });
            $('#myDataTable').on('click','.btn-disposisi', function(){
                var item_id = $(this).attr('data-id');
                var mail_type = $(this).attr('data-tipe');
                var $modal = $('#myDialogModal');
                
                if (mail_type == 'incoming'){
                    $modal.find('#labelDialog').text('Kirim Disposisi [ Surat Masuk ]');
                }else if (mail_type=='outgoing'){
                    $modal.find('#labelDialog').text('Kirim Disposisi [ Surat Keluar ]');
                }else if (mail_type=='nodin'){
                    $modal.find('#labelDialog').text('Kirim Disposisi [ Nota Dinas ]');
                }
                $modal.find('.modal-body').load('<?php echo get_action_url('disposisi/disposisi'); ?>/'+item_id, function(){
                    $modal.modal('show');
                    _this.reloadDataTable();
                });
            });
            
            $('#myDataTable').on('click','.btn-view', function(){
                var item_id = $(this).attr('data-id');
                var mail_type = $(this).attr('data-tipe');
                var $modal = $('#myDialogModal');
                
                if (mail_type=='incoming'){
                    $modal.find('#labelDialog').text('Lihat Disposisi [ Surat Masuk ]');
                }else if (mail_type=='outgoing'){
                    $modal.find('#labelDialog').text('Lihat Disposisi [ Surat Keluar ]');
                }else if (mail_type=='nodin'){
                    $modal.find('#labelDialog').text('Lihat Disposisi [ Nota Dinas ]');
                }
                $modal.find('.modal-body').load('<?php echo get_action_url('disposisi/view'); ?>/'+item_id, function(){
                    $modal.modal('show');
                    _this.reloadDataTable();
                });
            });
            
            $('#myDialogModal').on('hide.bs.modal', function (event) {
                _this.reloadDataTable();
            });
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
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
    
</script>