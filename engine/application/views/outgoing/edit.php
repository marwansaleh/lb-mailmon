<form id="MyForm" method="POST" class="form-validation">
    <input type="hidden" id="mail-id" name="id" value="<?php echo $item->id; ?>" />
    <input type="hidden" id="user-id" name="user_id" value="<?php echo $item->pengirim ? $item->pengirim : $me->id; ?>">
    <input type="hidden" id="bidang-id" name="bidang_pengirim" value="<?php echo $item->bidang_pengirim ? $item->bidang_pengirim : $me->bidang->id; ?>">
    <div class="widget">
        <div class="widget-header">
            <h3><i class="fa fa-list"></i> Metadata &amp; Atribut Surat</h3>
            <div class="btn-group widget-header-toolbar">
                <a href="#" title="Expand/Collapse" class="btn-borderless btn-toggle-expand"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="widget-content">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Nomor Surat</label>
                        <div class="input-group">
                            <input type="text" readonly="readonly" id="nomor-surat" name="nomor_surat" class="form-control" value="<?php echo $item->nomor_surat; ?>">
                            <div class="input-group-btn">
                                <button type="button" id="btn-gen-nomor" class="btn btn-warning <?php echo $item->nomor_surat ? 'disabled':''; ?>" data-loading-text="Wait..." <?php echo $item->nomor_surat ? 'disabled="true"':''; ?>>Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Tanggal Surat</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" readonly="true" class="form-control datepicker" name="tanggal_surat" value="<?php echo $item->tanggal_surat; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Tipe Surat</label>
                        <select class="form-control" name="tipe_surat">
                            <?php foreach ($tipe_surat as $ts): ?>
                            <option value="<?php echo $ts->id; ?>" <?php echo $item->tipe_surat==$ts->id?'selected':''; ?>><?php echo $ts->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Sifat Surat</label>
                        <select class="form-control" name="sifat_surat">
                            <?php foreach ($sifat_surat as $ss): ?>
                            <option value="<?php echo $ss->id; ?>" <?php echo $item->sifat_surat==$ss->id?'selected':''; ?>><?php echo $ss->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label>Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="<?php echo $item->perihal; ?>" />
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Asal Surat</label>
                        <select class="form-control" name="sandi">
                            <?php foreach ($pilihan_sandi as $sandi): ?>
                            <option value="<?php echo $sandi->sandi; ?>" <?php echo $item->sandi==$sandi->sandi?'selected':''; ?>><?php echo $sandi->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Penerima</label>
                        <input type="text" name="penerima" class="form-control" value="<?php echo $item->penerima; ?>" />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Signer</label>
                        <select id="select-signer" name="signer" class="form-control" data-selected-id="<?php echo $item->signer; ?>" style="width:100%;"></select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Tujuan</label>
                        <select class="form-control" name="tipe_tujuan">
                            <option value="<?php echo DEST_EXTERNAL; ?>" <?php echo $item->tipe_tujuan==DEST_EXTERNAL?'selected':''; ?>>Eksternal</option>
                            <option value="<?php echo DEST_INTERNAL; ?>" <?php echo $item->tipe_tujuan==DEST_INTERNAL?'selected':''; ?>>Internal</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Persetujuan Direksi</label>
                        <select class="form-control" name="persetujuan_direksi">
                            <option value="0" <?php echo $item->persetujuan_direksi==0?'selected':''; ?>>Tidak</option>
                            <option value="1" <?php echo $item->persetujuan_direksi==1?'selected':''; ?>>Ya</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <textarea name="isi_surat" class="form-control editor" rows="15"><?php echo $item->isi_surat; ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#templateHeader" class="collapsed" aria-expanded="false">Header <i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
                </h4>
            </div>
            <div id="templateHeader" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="header" class="form-control editor" rows="10"><?php echo $item->header; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#templateFooter" class="collapsed" aria-expanded="false">Footer <i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
                </h4>
            </div>
            <div id="templateFooter" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="footer" class="form-control editor" rows="10"><?php echo $item->footer; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#templatePageStyle" class="collapsed" aria-expanded="false">Page Styling <i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
                </h4>
            </div>
            <div id="templatePageStyle" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($pagestyles as $style=>$defvalue): ?>
                        <div class="col-sm-3">
                            <label><?php echo $style; ?></label>
                            <?php if ($style=='orientation'): ?>
                            <select class="form-control" name="orientation">
                                <option value="portrait" <?php echo $item->pagestyle && $item->pagestyle->orientation=='portrait' ? 'selected':'';  ?>>Portrait</option>
                                <option value="landscape" <?php echo $item->pagestyle && $item->pagestyle->orientation=='landscape' ? 'selected':'';  ?>>Landscape</option>
                            </select>
                            <?php else: ?>
                            <input type="text" class="form-control" name="<?php echo $style; ?>" value="<?php echo $item->pagestyle && isset($item->pagestyle->$style) ? $item->pagestyle->$style :$defvalue; ?>">
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group form-group-lg">
        <button type="submit" class="btn btn-primary btn-large"><i class="fa fa-save"></i> Submit</button>
        <a id="btn-cancel" class="btn btn-success btn-large" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Back</a>
        <div class="pull-right">
            <button type="button" id="btn-upload" class="btn btn-warning">Upload Dokumen <i class="fa fa-arrow-right"></i></button>
        </div>
    </div>
</form>

<script type="text/javascript">
    var Mail = {
        mailId: 0,
        _formValidationOption: function(){
            var _this = this;
            return {
                    ignore: [],
                    rules: {
                        perihal: {
                            minlength: 2,
                            required: true
                        },
                        penerima: {
                            minlength: 2,
                            required: true
                        },
                        signer: {
                            minlength: 1,
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
                    //save the editor before implementing ajax save
                    tinymce.triggerSave();
                    //save the form using ajax
                    _this.saveAjaxForm(form);
                    //avoid default form submitted
                    return false;
                }
            };
        },
        _initSelectSigner: function(){
            
            var $select2 = $('#select-signer');
            var initial_id = $select2.attr('data-selected-id');
            $select2.select2({
                ajax: {
                    url: "<?php echo config_item('service_url') .'user/select2'; ?>",
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
                placeholder: 'Pilih nama penandatangan',
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
                    url: '<?php echo config_item('service_url') .'user/select2'; ?>/' + initial_id,
                    dataType: 'json'
                }).then(function (data) {
                    // Here we should have the data object
                    $option.text(data.text).val(data.id); // update the text that is displayed (and maybe even the value)
                    $option.removeData(); // remove any caching data that might be associated
                    $select2.trigger('change'); // notify JavaScript components of possible changes
                });
            }
        },
        _initEditor: function(){
            var _this = this;
            tinymce.init({
                selector: 'textarea.editor',
                cache_suffix: '?v=4.1.6',
                theme: 'modern',
                plugins : 'advlist searchreplace visualblocks code autolink link image imagetools table lists charmap print preview fullscreen textcolor hr insertdatetime',
                toolbar: 'undo redo | bold italic alignleft aligncenter alignright alignjustify styleselect bullist numlist | forecolor backcolor | link image | fullscreen',
                insertdatetime_dateformat: "%d-%m-%Y",
                paste_as_text: true,
                paste_word_valid_elements: 'b,strong,i,em,h1,h2',
                paste_retain_style_properties: "color font-size",
                external_filemanager_path:"<?php echo get_asset_url('js/plugins/filemanager'); ?>/",
                external_plugins: { "filemanager" : "<?php echo get_asset_url('js/plugins/filemanager/plugin.min.js'); ?>"},
                filemanager_title:"Filemanager" ,
                relative_urls: false,
                file_browser_callback: function(field_name, url, type, win) {
                    //win.document.getElementById(field_name).value = 'my browser value';
                    console.log('field_name:'+field_name+', url:'+url+', type:'+type+', win:'+win);
                }
            });
        },
        saveAjaxForm: function(form){
            var _this = this;
            var submitType = _this.mailId ? 'PUT' : 'POST';
            $(form).ajaxSubmit({
                type: submitType,
                url: '<?php echo get_action_url('service/outgoing/index'); ?>/'+_this.mailId,
                dataType: 'json',
                success: function(data){
                    if (data.status){
                        $('#mail-id').val(data.item.id);
                        _this.mailId = parseInt(data.item.id);
                        
                        $('#btn-upload').prop('disabled', _this.mailId ? false : true);
                        if ($('#nomor-surat').val()){
                            $('#btn-gen-nomor').prop('disabled',true);
                        }else{
                            $('#btn-gen-nomor').prop('disabled',false);
                        }
                        alert('Surat keluar berhasil disimpan');
                    }else{
                        alert(data.message);
                    }
                }
            });
        },
        init: function(){
            var _this = this;
            _this.mailId = parseInt($('#mail-id').val());
            
            $('form.form-validation').validate(_this._formValidationOption());
            
            _this._initSelectSigner();
            
            //init text editor
            _this._initEditor();
            
            $('#btn-upload').prop('disabled', _this.mailId ? false : true).on('click', function(){
                var url = '<?php echo get_action_url('outgoing/register/upload'); ?>/'+ $('#mail-id').val();
                window.location = url;
            });
            
            /* button generate nomor surat */
            var button_gen_disabled = true;
            if (_this.mailId){
                button_gen_disabled = false;
            }
            if ($('#nomor-surat').val()){
                button_gen_disabled = true;
            }
            $('#btn-gen-nomor').prop('disabled', button_gen_disabled).on('click', function(){
                var $btn = $(this);
                $btn.button('loading');
                $.ajax({
                    url: '<?php echo get_action_url('service/outgoing/gen_nomor'); ?>',
                    type: 'POST',
                    data: {mail: _this.mailId},
                    dataType: 'json'
                }).then(function(data){
                    $btn.button('reset');
                    
                    if (data.status){
                        $btn.parents('.input-group').find('input[name="nomor_surat"]').val(data.nomor_surat);
                        
                        //hide the button control
                        $btn.prop('disabled', true);
                    }
                });
            });
        }
    };
    $(document).ready(function(){
        Mail.init();
    });
</script>