<div class="row">
    <div class="col-lg-12">
        <div class="widget">
            <div class="widget-header">
                <h3><?php echo $page_subtitle ? $page_subtitle:'Edit Data'; ?></h3>
            </div>
            <div class="widget-content clearfix">
                <form id="MyForm" method="POST" class="form-validation">
                    <input type="hidden" id="mail-id" name="id" value="<?php echo $id ? $id:0; ?>" />
                    <input type="hidden" id="user-id" name="user_id" value="<?php echo $me->id; ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <input type="text" name="nomor_surat" class="form-control" value="<?php echo $item->nomor_surat; ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Surat</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" readonly="true" class="form-control datepicker" name="tanggal_surat" value="<?php echo $item->tanggal_surat; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Terima</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" readonly="true" class="form-control datepicker" name="tanggal_terima" value="<?php echo $item->tanggal_terima; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Pengirim</label>
                                <input type="text" name="pengirim" class="form-control" value="<?php echo $item->pengirim; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Penerima</label>
                                <input type="hidden" id="bidang-penerima" name="bidang" class="form-control" value="<?php echo $item->bidang; ?>" />
                                <select id="select-penerima" name="penerima" class="form-control" data-selected-id="<?php echo $item->penerima; ?>" style="width:100%;"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Perihal</label>
                                <input type="text" name="perihal" class="form-control" value="<?php echo $item->perihal; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control"><?php echo $item->keterangan; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-group-lg">
                        <button type="submit" id="btn-submit" class="btn btn-primary btn-large" data-loading-text="Wait...."><i class="fa fa-save"></i> Submit</button>
                        <a id="btn-cancel" class="btn btn-success btn-large" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Back</a>
                        <div class="pull-right">
                            <button type="button" id="btn-upload" class="btn btn-warning">Upload Dokumen <i class="fa fa-arrow-right"></i></button>
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
        _initSelectPenerima: function(){
            
            var $select2 = $('#select-penerima');
            var initial_id = $select2.attr('data-selected-id');
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
                    $('#bidang-penerima').val(selected.bidang ? selected.bidang.id:0);
                }
            }).on("select2:unselect", function (e){
                //_this.showInputTransactionValue(0);
                $('#bidang-penerima').val(0);
            });
            if (parseInt(initial_id)>0){
                var $option = $('<option selected>Loading...</option>').val(initial_id);
                $select2.append($option).trigger('change');
                $.ajax({ // make the request for the selected data object
                    type: 'GET',
                    url: '<?php echo get_action_url('service/user/select2'); ?>/' + initial_id,
                    dataType: 'json'
                }).then(function (data) {
                    // Here we should have the data object
                    $option.text(data.text).val(data.id); // update the text that is displayed (and maybe even the value)
                    $option.removeData(); // remove any caching data that might be associated
                    $select2.trigger('change'); // notify JavaScript components of possible changes
                });
            }
        },
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            
            $('form.form-validation').validate({
                rules: {
                    pengirim: {
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
                    var $btn = $('#btn-submit');
                    $btn.button('loading');
                    
                    var submitType = _this.mailId ? 'PUT' : 'POST';
                    $(form).ajaxSubmit({
                        type: submitType,
                        url: '<?php echo get_action_url('service/incoming/index'); ?>/'+_this.mailId,
                        dataType: 'json',
                        success: function(data){
                            $btn.button('reset');
                            if (data.status){
                                $('#mail-id').val(data.item.id);
                                _this.mailId = parseInt(data.item.id);
                                
                                alert('Surat masuk berhasil disimpan');
                                
                                $('#btn-upload').prop('disabled', _this.mailId ? false : true);
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            _this._initSelectPenerima();
            
            $('#btn-upload').prop('disabled', _this.mailId ? false : true).on('click', function(){
                var url = '<?php echo get_action_url('incoming/register/upload'); ?>/'+ $('#mail-id').val();
                window.location = url;
            });
            
            
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
</script>