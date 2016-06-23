<form id="MyForm" method="POST" class="form-validation">
    <input type="hidden" id="template-id" name="id" value="<?php echo $item->id; ?>" />
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nama template</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $item->nama; ?>" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tipe</label>
                <select class="form-control" name="tipe">
                    <option value="<?php echo MAIL_OUTGOING; ?>" <?php echo $item->tipe==MAIL_OUTGOING?'selected':''; ?>>Surat Keluar</option>
                    <option value="<?php echo MAIL_NODIN; ?>" <?php echo $item->tipe==MAIL_NODIN?'selected':''; ?>>Nota Dinas</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#templateHeader">Template Header<i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
                </h4>
            </div>
            <div id="templateHeader" class="panel-collapse collapse in">
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
                    <a data-toggle="collapse" data-parent="#accordion" href="#templateContent" class="collapsed" aria-expanded="false">Template Content <i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
                </h4>
            </div>
            <div id="templateContent" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea name="content" class="form-control editor" rows="10"><?php echo $item->content; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#templateFooter" class="collapsed" aria-expanded="false">Template Footer <i class="fa fa-angle-down pull-right"></i><i class="fa fa-angle-up pull-right"></i></a>
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
    </div>
</form>

<script type="text/javascript">
    var Template = {
        templateId: 0,
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
            var submitType = _this.templateId ? 'PUT' : 'POST';
            $(form).ajaxSubmit({
                type: submitType,
                url: '<?php echo get_action_url('service/template/index'); ?>/'+_this.templateId,
                dataType: 'json',
                success: function(data){
                    if (data.status){
                        $('#template-id').val(data.item.id);
                        _this.templateId = parseInt(data.item.id);
                        
                        alert('Template berhasil disimpan');
                    }else{
                        alert(data.message);
                    }
                }
            });
        },
        init: function(){
            var _this = this;
            _this.templateId = parseInt($('#template-id').val());
            
            $('form.form-validation').validate(_this._formValidationOption());
            
            //init text editor
            _this._initEditor();
        }
    };
    $(document).ready(function(){
        Template.init();
    });
</script>