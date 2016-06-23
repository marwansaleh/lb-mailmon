<?php if (!isset($error)): ?>
<form id="MyForm" method="POST" class="form-validation">
    <input type="hidden" id="mail-id" name="mail_id" value="<?php echo $mail->id; ?>" />
    <input type="hidden" id="mail-type" name="mail_type" value="<?php echo $disposisi->tipe; ?>" />
    <input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>" />
    <input type="hidden" id="status" name="status" value="<?php echo $disposisi->status; ?>" />
    <div class="widget">
        <div class="widget-header">
            <h3><i class="fa fa-list"></i> Histori</h3>
            <div class="btn-group widget-header-toolbar">
                <a href="#" title="Expand/Collapse" class="btn-borderless btn-toggle-expand"><i class="fa fa-chevron-up"></i></a>
                <a href="#" title="Remove" class="btn-borderless btn-remove"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="widget-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <p class="form-control-static">Pesan Disposisi Sebelumnya: <strong><?php echo $disposisi->keterangan ? $disposisi->keterangan : '--- Tidak ada ---'; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Pengirim</th>
                                <?php if ($disposisi->tipe==MAIL_OUTGOING): ?>
                                <th>Penerima</th>
                                <?php endif; ?>
                                <th>Perihal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $mail->nomor_surat; ?></td>
                                <td><?php echo $mail->pengirim; ?></td>
                                <?php if ($disposisi->tipe==MAIL_OUTGOING): ?>
                                <td><?php echo $mail->penerima; ?></td>
                                <?php endif; ?>
                                <td><?php echo $mail->perihal; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php if ($disposisi->tipe==MAIL_OUTGOING || $disposisi->tipe==MAIL_NODIN): ?>
                    <button type="button" id="btn-isisurat" class="btn btn-link"><i class="fa fa-envelope-o"></i> Lihat Isi Surat</button>
                    <button type="button" id="btn-history" class="btn btn-link"><i class="fa fa-list"></i> Lihat Riwayat Persetujuan</button>
                    <?php else: ?>
                    <button type="button" id="btn-history" class="btn btn-link"><i class="fa fa-list"></i> Lihat Riwayat Disposisi</button>
                    <?php endif; ?>
                    
                    <?php if ($mail->dokumen): ?>
                        <div class="btn-group btn">
                            <button type="button" class="btn btn-link dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download"><i class="fa fa-download"></i> Dokumen Lampiran <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <?php foreach ($mail->dokumen as $doc): ?>
                                    <li><a href="<?php echo $doc->download_url; ?>" target="blank"><?php echo $doc->orig_name; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($disposisi->tipe==MAIL_OUTGOING || $disposisi->tipe==MAIL_NODIN): ?>
            <div id="container-isisurat" class="row hidden">
                <div class="col-sm-12">
                    <div class="well well-sm" style="background-color: white;">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $mail->isi_surat; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div id="container-history" class="row hidden">
                <div class="col-sm-12">
                    <table id="myDataTable" class="table table-striped table-condensed table-dark-header small">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Pengirim</th>
                                <th style="width: 120px;">Tgl. kirim</th>
                                <th>Keterangan</th>
                                <th style="width: 120px;">Penerima</th>
                                <th style="width: 120px;">Tgl. Terima</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="well well-sm">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Disposisi ke</label>
                    <div id="container-penerima">
                        <div class="row row-penerima">
                            <div class="col-lg-12">
                                <input type="hidden" name="bidang" class="form-control input-bidang" />
                                <select id="select-penerima" name="penerima" class="form-control select-penerima" style="width:100%;"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group form-group-lg">
                <?php if ($disposisi->tipe==MAIL_INCOMING): ?>
                <button type="submit" class="btn btn-primary btn-large"><i class="fa fa-save"></i> Kirim Disposisi</button>
                <?php elseif ($disposisi->tipe==MAIL_OUTGOING || $disposisi->tipe==MAIL_NODIN): ?>
                    <button type="submit" id="btn-submit-out" class="hidden"></button>
                    <?php if ($disposisi->status == STATUS_OUT_APPROVAL || $disposisi->status == STATUS_NODIN_APPROVAL): ?>
                    <button type="button" id="btn-approve" class="btn btn-primary btn-large"><i class="fa fa-check-circle-o"></i> Disetujui</button>
                    <button type="button" id="btn-approvetosign" class="btn btn-success btn-large"><i class="fa fa-check"></i> Disetujui untuk Tandatangan</button>
                    <button type="button" id="btn-reject" class="btn btn-warning btn-large"><i class="fa fa-remove"></i> Revisi</button>
                    <?php elseif (($disposisi->status == STATUS_OUT_REVISION && $mail->status==STATUS_OUT_REVISION)||($disposisi->status == STATUS_NODIN_REVISION && $mail->status==STATUS_NODIN_REVISION)): ?>
                    <button type="button" id="btn-sendrevision" class="btn btn-primary btn-large"><i class="fa fa-share"></i> Kirim Perbaikan</button>
                    <?php elseif ($disposisi->status == STATUS_OUT_SIGNING || $disposisi->status == STATUS_NODIN_SIGNING): ?>
                    <button type="button" id="btn-sign" class="btn btn-primary btn-large"><i class="fa fa-check-circle-o"></i> Tandatangani</button>
                    <button type="button" id="btn-reject" class="btn btn-warning btn-large"><i class="fa fa-remove"></i> Revisi</button>
                    <?php endif ;?>
                <?php endif; ?>
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-large" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php else: ?>
<div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
<?php endif; ?>

<script type="text/javascript">
    var Disposisi = {
        mailId: 0,
        mailType: null,
        userId: 0,
        _initSelectPenerima: function(){
            var $row = $('.row-penerima');
            var $select2 = $row.find('.select-penerima');
            
            $select2.select2({
                ajax: {
                    url: "<?php echo get_action_url('service/user/select2'); ?>",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                      return {
                        q: params.term || '', // search term
                        page: params.page || 1
                      };
                    },
                    cache: true
                },
                placeholder: 'Pilih nama penerima',
                //width: 'element',
                theme: 'bootstrap',
                allowClear: true,
                minimumInputLength: 1
            })
        },
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            _this.mailType = $('#mail-type').val();
            _this.userId = parseInt($('#user-id').val());
            
            $('form#MyForm').validate({
                rules: {
                    penerima: {
                        minlength: 1,
                        required: true
                    },
                    keterangan: {
                        minlength: 2,
                        required: true
                    }
                },
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                submitHandler: function(form){
                    if (!parseInt(form.penerima.value)){
                        alert('Penerima disposisi tidak boleh kosong');
                        return false;
                    }
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: '<?php echo get_action_url('service/disposisi'); ?>',
                        dataType: 'json',
                        clearForm: true,
                        success: function(data){
                            if (data.status){
                                $(".select-penerima").val('').trigger('change');
                                _this.loadDisposisi();
                                
                                if (Mail !== "undefined"){
                                    Mail.reloadDataTable();
                                }
                                
                                alert('Berhasil mengirim disposisi');
                                //if outgoing, we have to close the modal to avoid unwanted action
                                if (_this.mailType == 'outgoing'){
                                    $('#myDialogModal').modal('hide');
                                }
                                
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            /******* for outgoing only *********/
            $('#btn-approve').on('click', function(){
                $('input#status').val('<?php echo STATUS_OUT_APPROVED; ?>');
                $('#btn-submit-out').trigger('click');
            });
            $('#btn-approvetosign').on('click', function(){
                $('input#status').val('<?php echo STATUS_OUT_SIGNING; ?>');
                $('#btn-submit-out').trigger('click');
            });
            $('#btn-sign').on('click', function(){
                $('input#status').val('<?php echo STATUS_OUT_SIGNED; ?>');
                $('#btn-submit-out').trigger('click');
            });
            $('#btn-reject').on('click', function(){
                $('input#status').val('<?php echo STATUS_OUT_REVISION; ?>');
                $('#btn-submit-out').trigger('click');
            });
            $('#btn-sendrevision').on('click', function(){
                $('input#status').val('<?php echo STATUS_OUT_APPROVAL; ?>');
                $('#btn-submit-out').trigger('click');
            });
            $('#btn-isisurat').on('click', function(){
                $('#container-isisurat').toggleClass('hidden');
            });
            /********** end of outgoing *********/
            
            $('#btn-history').on('click', function(){
                $('#container-history').toggleClass('hidden');
            });
            
            /******  WIDGET ONLY *******/
            $('.widget .btn-remove').click(function(e){
                e.preventDefault();
                $(this).parents('.widget').fadeOut(300, function(){
                    $(this).remove();
                });
            });
            var affectedElement = $('.widget-content');
            $('.widget .btn-toggle-expand').clickToggle(
                function (e) {
                    e.preventDefault();

                    $(this).parents('.widget').find(affectedElement).slideUp(300);
                    $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
                },
                function (e) {
                    e.preventDefault();

                    $(this).parents('.widget').find(affectedElement).slideDown(300);
                    $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
                }
            );

            // widget focus
            $('.widget .btn-focus').clickToggle(
                    function (e) {
                        e.preventDefault();
                        $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                        $(this).parents('.widget').find('.btn-remove').addClass('link-disabled');
                        $(this).parents('.widget').addClass('widget-focus-enabled');
                        $('<div id="focus-overlay"></div>').hide().appendTo('body').fadeIn(300);

                    },
                    function (e) {
                        e.preventDefault();
                        $theWidget = $(this).parents('.widget');

                        $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
                        $theWidget.find('.btn-remove').removeClass('link-disabled');
                        $('body').find('#focus-overlay').fadeOut(function () {
                            $(this).remove();
                            $theWidget.removeClass('widget-focus-enabled');
                        });
                    }
            );
            /*********************************/
            
            _this._initSelectPenerima();
            
            if (_this.mailId){
                _this.loadDisposisi();
            }
        },
        loadDisposisi: function (){
            var _this = this;
            var $table = $('#myDataTable tbody');
            $table.empty();
            
            $.ajax({
                url: '<?php echo get_action_url('service/disposisi/history'); ?>/'+_this.mailId,
                data: {type: _this.mailType}
            }).then(function(data){
                if (data.item_count > 0){
                    for (var i in data.items){
                        var disposisi = data.items[i];
                        var s = '<tr>';
                        s+= '<td>'+disposisi.pengirim+'</td>';
                        s+= '<td>'+disposisi.waktu_kirim+'</td>';
                        s+= '<td>'+disposisi.keterangan+'</td>';
                        s+= '<td>'+disposisi.penerima+'</td>';
                        s+= '<td>'+(disposisi.waktu_terima ? disposisi.waktu_terima : '-')+'</td>';
                        s+= '<td class="text-center">'+disposisi.status+'</td>';
                        s+= '</tr>';
                        
                        $table.append(s);
                    }
                }else{
                    $table.append('<tr><td colspan="5">Data disposisi tidak ada</td></tr>');
                }
            });
        }
    };
    $(document).ready(function(){
        Disposisi.init();
    });
</script>
