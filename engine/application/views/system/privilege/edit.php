<div class="row">
    <div class="col-lg-12">
        <div class="widget">
            <div class="widget-header">
                <h3><?php echo $page_subtitle ? $page_subtitle:'Edit Data'; ?></h3>
            </div>
            <div class="widget-content clearfix">
                <form id="MyForm" method="POST" class="form-validation">
                    <input type="hidden" id="item-id" name="id" value="<?php echo $item->id; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Module</label>
                                <select class="form-control" id="select-module" name="module">
                                    <?php foreach ($modules as $module): ?>
                                    <option value="<?php echo $module->id; ?>" <?php echo $module->id==$item->module?'selected':''; ?>><?php echo strtoupper($module->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Parent Menu</label>
                                <select class="form-control" id="select-parent" name="parent" data-selected-id="<?php echo $item->parent; ?>"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Caption</label>
                                <input type="text" name="caption" class="form-control" value="<?php echo $item->caption; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo $item->title; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Link Url</label>
                                <input type="text" name="link" class="form-control" value="<?php echo $item->link; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Icon</label>
                                <input type="text" name="icon" class="form-control" value="<?php echo $item->icon; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>Hidden</label>
                                <select class="form-control" name="hidden">
                                    <option value="0" <?php echo $item->hidden==0?'selected':''; ?>>No</option>
                                    <option value="1" <?php echo $item->hidden==1?'selected':''; ?>>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <button type="submit" class="btn btn-primary btn-large"><i class="fa fa-save"></i> Submit</button>
                        <a id="btn-cancel" class="btn btn-success btn-large" href="<?php echo $back_url; ?>"><i class="fa fa-backward"></i> Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var Menu = {
        menuId: 0,
        init: function(){
            var _this = this;
            _this.menuId = parseInt($('#item-id').val());
            
            $('form.form-validation').validate({
                rules: {
                    caption: {
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
                    var submitType = _this.menuId ? 'PUT' : 'POST';
                    $(form).ajaxSubmit({
                        type: submitType,
                        url: '<?php echo get_action_url('service/menu/index'); ?>/'+_this.menuId,
                        dataType: 'json',
                        success: function(data){
                            if (data.status){
                                $('#item-id').val(data.item.id);
                                _this.menuId = parseInt(data.item.id);
                                
                                alert('Menu berhasil disimpan');
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            $('#select-module').on('change', function(){
                _this.loadParentMenu($(this).val());
            });
            
            _this.loadParentMenu($('#select-module').val());
        },
        loadParentMenu: function(module){
            var $select = $('#select-parent');
            $select.empty();
            
            $.ajax({
                url: '<?php echo get_action_url('service/menu/bymodule'); ?>/'+module,
                type: 'GET',
                dataType: 'json'
            }).then(function(data){
                if (data.status && data.item_count > 0){
                    for (var i in data.items){
                        var menu = data.items[i];
                        $select.append('<option value="'+menu.id+'"'+(menu.id==parseInt($select.attr('data-selected-id'))?' selected':'')+'>'+menu.caption+'</option>');
                    }
                }
                
                $select.prepend('<option value="0">--No parent--</option>');
            });
        }
    };
    $(document).ready(function(){
        Menu.init();
    });
</script>