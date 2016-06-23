<div class="row">
    <div class="col-lg-12">
        <div class="widget">
            <div class="widget-header">
                <h3><?php echo $page_subtitle ? $page_subtitle:'Upload Dokumen'; ?></h3>
            </div>
            <div class="widget-content clearfix">
                <form id="myUpload" method="POST" class="form-validation" enctype="multipart/form-data">
                    <input type="hidden" id="mail-id" name="id" value="<?php echo $mail->id; ?>" />
                    <input type="hidden" id="mail-tipe" name="type" value="<?php echo MAIL_NODIN; ?>" />
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <input type="text" readonly="true" class="form-control" value="<?php echo $mail->nomor_surat; ?>">
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="form-group">
                                <label>Perihal</label>
                                <input type="text" readonly="true" class="form-control" value="<?php echo $mail->perihal; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><span id="uppload-label">Pilih File</span></label>
                                <input type="file" id="file-upload" name="userfile" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="well well-sm">
                        <table class="table table-striped table-condensed table-dark-header" id="tableMyData">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No.</th>
                                    <th>Nama File</th>
                                    <th>Deskripsi</th>
                                    <th class="text-right">File Size</th>
                                    <th class="text-right">#</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="form-group form-group-lg">
                        <a id="btn-cancel" class="btn btn-success btn-large" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Back</a>
                        <div class="pull-right">
                            <a id="btn-finish" class="btn btn-primary btn-large" href="<?php echo $finish_url; ?>"><i class="fa fa-home"></i> Selesai</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var Mail = {
        mailId: 0,
        mailType: 'incoming',
        _label: '',
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            _this.mailType = $('#mail-tipe').val();
            
            $('#file-upload').on('change', function(){
                if ($(this).val()){
                    $('form#myUpload').submit();
                }
            });
            //prepare upload dokumen
            $('form#myUpload').ajaxForm({
                url: '<?php echo get_action_url('service/attachment/upload'); ?>',
                type: 'POST',
                dataType: 'json',
                resetForm: true,
                beforeSubmit: function(){
                    _this._label = $('#uppload-label').text();
                    $('#uppload-label').text('Uploading file...');
                },
                success: function(data) {
                    if (data.status){
                        _this.loadData();
                    }else{
                        alert(data.message);
                    }
                    
                    $('#uppload-label').text(_this._label);
                }
            }); 
            
            $('#tableMyData').on('click','.btn-remove', function(){
                var upload_id = $(this).attr('data-id');
                if (confirm('Hapus file lampiran ini ?')){
                    $.ajax({
                        type: 'DELETE',
                        url: '<?php echo get_action_url('service/attachment/uploaddelete'); ?>/'+upload_id,
                        dataType: 'json',
                    }).then(function(data){
                        if (data.status){
                            _this.loadData();
                        }else{
                            alert(data.message);
                        }
                    });
                }
            });
            
            $('#tableMyData').on('click', '.btn-download', function(){
                var url = $(this).attr('data-url');
                var wnd = window.open("<?php echo get_action_url('download/index') ?>/"+url);
            });
            
            _this.loadData();
        },
        loadData: function(){
            var _this = this;
            
            $.ajax({
                url: '<?php echo get_action_url('service/attachment/bymail'); ?>/'+_this.mailId+'/'+_this.mailType,
                type: 'GET',
                dataType: 'json'
            }).then(function(data){
                var $table = $('#tableMyData tbody');
                $table.empty();
                
                if (data.item_count > 0){
                    for(var i in data.items){
                        var upload = data.items[i];
                        var s = '<tr>';
                        s+= '<td class="text-center">'+upload.nomor+'.</td>';
                        s+= '<td>'+upload.file_name+'</td>';
                        s+= '<td>'+upload.orig_name+'</td>';
                        s+= '<td class="text-right"><span class="number">'+upload.file_size+'</span>kb</td>';
                        s+= '<td class="text-right">';
                            s+='<button type="button" class="btn btn-xs btn-danger btn-remove" data-id="'+upload.id+'"><i class="fa fa-remove"></i></button>';
                            s+='<button type="button" class="btn btn-xs btn-success btn-download" data-url="'+upload.download_url+'"><i class="fa fa-download"></i></button>';
                        s+= '</td>';
                        s+= '</tr>';
                        $table.append(s);
                    }
                }
            });
            $('span.number').number(true);
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
</script>