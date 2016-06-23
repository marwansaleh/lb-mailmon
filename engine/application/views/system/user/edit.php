<div class="row">
    <div class="col-lg-12">
        <div class="widget">
            <div class="widget-header">
                <h3><?php echo $page_subtitle ? $page_subtitle:'Edit Data'; ?></h3>
            </div>
            <div class="widget-content clearfix">
                <form id="MyForm" method="POST" class="form-validation">
                    <input type="hidden" id="item-id" name="id" value="<?php echo $id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <input type="checkbox" name="change_password" value="1"> Change
                                    </div>
                                    <input type="text" class="form-control" name="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Grup User</label>
                                <select class="form-control" name="grup"></select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Bidang / Bagian</label>
                                <select class="form-control" name="bidang"></select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" class="form-control" name="nik">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="mobile">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Telpon</label>
                                <input type="text" class="form-control" name="telepon">
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
    var User = {
        userId: 0,
        init: function(){
            var _this = this;
            _this.userId = parseInt($('#item-id').val());
            
            $('form.form-validation').validate({
                rules: {
                    nama: {
                        minlength: 2,
                        required: true
                    },
                    username: {
                        minlength: 2,
                        required: true
                    },
                    nik: {
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
                    var submitType = _this.userId ? 'PUT' : 'POST';
                    $(form).ajaxSubmit({
                        type: submitType,
                        url: '<?php echo get_action_url('service/user/save'); ?>/'+_this.userId,
                        dataType: 'json',
                        success: function(data){
                            if (data.status){
                                $('#item-id').val(data.item.id);
                                _this.userId = parseInt(data.item.id);
                                
                                alert('User berhasil disimpan');
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            _this.loadUserSupportData(_this.userId);
        },
        loadUserSupportData: function(loadUserId){
            var _this = this;
            
            $.ajax({
                url: '<?php echo get_action_url('service/user/support'); ?>',
                type: 'GET',
                dataType: 'json'
            }).then(function(data){
                //display each data to each supported elements
                var $select_grup = $('select[name="grup"]');
                var $select_bidang = $('select[name="bidang"]');
                var $select_wilayah = $('select[name="wilayah"]');
                
                $select_grup.empty().append('<option value="0">--Pilih Grup--</option>');
                for (var i in data.grup){
                    var grup = data.grup[i];
                    $select_grup.append('<option value="'+grup.id+'">'+grup.nama+'</option>');
                }
                
                $select_bidang.empty().append('<option value="0">--Pilih Bidang--</option>');
                for (var i in data.bidang){
                    var bidang = data.bidang[i];
                    $select_bidang.append('<option value="'+bidang.id+'">'+bidang.nama+'</option>');
                }
                
                if (loadUserId){
                    _this.loadUser(loadUserId);
                }
            });
        },
        loadUser: function(userId){
            $.ajax({
                url: '<?php echo get_action_url('service/user/user'); ?>/'+userId,
                type: 'GET',
                dataType: 'json'
            }).then(function(data){
                if (data.status){
                    var user = data.item;
                    $('input[name="nama"]').val(user.nama);
                    $('input[name="username"]').val(user.username);
                    $('select[name="grup"]').val(user.grup ? user.grup.id: 0);
                    $('select[name="bidang"]').val(user.bidang ? user.bidang.id : 0);
                    $('input[name="nik"]').val(user.nik);
                    $('input[name="email"]').val(user.email);
                    $('input[name="mobile"]').val(user.mobile);
                    $('input[name="telepon"]').val(user.telepon);
                }
            });
        }
    };
    $(document).ready(function(){
        User.init();
    });
</script>