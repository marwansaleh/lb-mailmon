<div class="row">
    <div class="col-lg-12">
        <div class="widget">
            <div class="widget-header">
                <h3><?php echo $page_subtitle ? $page_subtitle:'Kirim Disposisi'; ?></h3>
            </div>
            <div class="widget-content clearfix">
                <form id="MyForm" method="POST" class="form-validation">
                    <input type="hidden" id="mail-id" name="mail_id" value="<?php echo $mail->id; ?>" />
                    <input type="hidden" id="mail-type" name="mail_type" value="<?php echo MAIL_INCOMING; ?>" />
                    <input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>" />
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <input type="text" readonly="true" class="form-control" value="<?php echo $mail->nomor_surat; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Pengirim</label>
                                <input type="text" readonly="true" class="form-control" value="<?php echo $mail->pengirim; ?>">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>Perihal</label>
                                <input type="text" readonly="true" class="form-control" value="<?php echo $mail->perihal; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" id="btn-history" class="btn btn-link"><i class="fa fa-list"></i> Lihat Riwayat Disposisi</button>
                            <?php if ($mail->dokumen): ?>
                            <div class="btn-group btn">
                                <button type="button" class="btn btn-link dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Download"><i class="fa fa-download"></i> Dokumen Lampiran <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($mail->dokumen as $doc): ?>
                                    <li><a href="<?php echo $doc->download_url; ?>" target="blank"><?php echo $doc->orig_name; ?></a></li>
                                    <?php endforeach ; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="container-history" class="row hidden">
                        <div class="col-lg-12">
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
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-lg-12">
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
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-large" data-loading-text="Wait..."><i class="fa fa-save"></i> Kirim Disposisi</button>
                        <a id="btn-cancel" class="btn btn-success btn-large" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var Mail = {
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
            }).on("select2:select", function (e) {
                var selected = e.params.data;
                if (typeof selected !== "undefined") {
                    $row.find('.input-bidang').val(selected.bidang ? selected.bidang.id:0);
                }
            }).on("select2:unselect", function (e){
                //_this.showInputTransactionValue(0);
                $row.find('.input-bidang').val(0);
            });
        },
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            _this.mailType = $('#mail-type').val();
            _this.userId = parseInt($('#user-id').val());
            
            $('form.form-validation').validate({
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
                    
                    var $btn = $('#btn-submit');
                    $btn.button('loading');
                    $(form).ajaxSubmit({
                        type: 'POST',
                        url: '<?php echo get_action_url('service/disposisi'); ?>',
                        dataType: 'json',
                        clearForm: true,
                        success: function(data){
                            $btn.button('reset');
                            if (data.status){
                                $(".select-penerima").val('').trigger('change');
                                _this.loadDisposisi();
                                
                                alert('Surat masuk berhasil di-disposisikan');
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            $('#btn-history').on('click', function(){
                $('#container-history').toggleClass('hidden');
            });
            
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
                        s+= '<td>'+disposisi.waktu_terima+'</td>';
                        s+= '<td class="text-center">'+disposisi.status+'</td>';
                        s+= '</tr>';
                        
                        $table.append(s);
                    }
                }else{
                    $table.append('<tr><td colspan="6">Data disposisi tidak ada</td></tr>');
                }
            });
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
</script>