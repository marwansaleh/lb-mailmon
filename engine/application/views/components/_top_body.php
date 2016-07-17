<div class="top-bar">
    <div class="container">
        <div class="row">
            <!-- logo -->
            <div class="col-md-2 logo">
                <a href="#"><img src="<?php echo get_asset_url('img/logo-lubuklinggau-bar.png') ?>" alt="Lubuklinggau" style="max-height: 28px;"></a>
            </div>
            <!-- end logo -->
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-12">
                        <div class="top-bar-right">
                            <!-- responsive menu bar icon -->
                            <a href="#" class="hidden-md hidden-lg main-nav-toggle"><i class="fa fa-bars"></i></a>
                            <!-- notification -->
                            <?php if ($notifications): ?>
                            <div class="notifications">
                                <ul>
                                    <!-- notification: unread disposisi -->
                                    <li class="notification-item general">
                                        <div class="btn-group">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-bell"></i><span class="count"><?php echo number_format(count($notifications)); ?></span>
                                                <span class="circle"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li class="notification-header">
                                                    <em>Anda memiliki <?php echo number_format(count($notifications)); ?> disposisi / persetujuan baru</em>
                                                </li>
                                                <?php foreach ($notifications as $notif): ?>
                                                <li class="inbox-item clearfix">
                                                    <a href="<?php echo get_action_url('disposisi'); ?>">
                                                        <div class="media">
                                                            <div class="media-left">
                                                                <?php if (isset($notif->pengirim->avatar) && $notif->pengirim->avatar): ?>
                                                                <img style="max-width: 28px;" class="media-object" src="<?php echo $notif->pengirim->avatar; ?>" alt="<?php echo $notif->pengirim->nama; ?>">
                                                                <?php else: ?>
                                                                <i class="fa fa-user"></i>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="media-body" title="<?php echo $notif->keterangan; ?>">
                                                                <h5 class="media-heading name"><?php echo $notif->pengirim->nama; ?></h5>
                                                                <p class="text"><?php echo strlen($notif->keterangan)>50 ? substr($notif->keterangan, 0, 50).' ...':$notif->keterangan; ?></p>
                                                                <span class="timestamp"><?php echo $notif->waktu_kirim; ?></span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- end notification: inbox -->
                                </ul>
                            </div>
                            <?php endif; ?>
                            <!-- logged user and the menu -->
                            <div class="logged-user">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                        <img style="max-width: 28px;" src="<?php echo $me->avatar ? $me->avatar : get_asset_url('img/user-avatar.png'); ?>" alt="User Avatar" class="avatar">
                                        <span class="name"><?php echo $me->nama; ?> [ <?php echo $me->bidang->nama; ?> ]</span> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="#" id="btn-change-password">
                                                <i class="fa fa-user"></i>
                                                <span class="text">Ganti Password</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" id="btn-change-avatar">
                                                <i class="fa fa-image"></i>
                                                <span class="text">Ganti Foto Profil</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('auth/logout'); ?>">
                                                <i class="fa fa-power-off"></i>
                                                <span class="text">Logout</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- end logged user and the menu -->
                        </div>
                        <!-- /top-bar-right -->
                    </div>
                </div>
                <!-- /row -->
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="MyChangePasswordDialog" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="labelDialog">Ubah Password</span></h4>
            </div>
            <div class="modal-body">
                <form id="MyFormChangePassword">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-sm">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-sm">
                                <label>Password Lama</label>
                                <input type="text" class="form-control" name="old_password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-sm">
                                <label>Password Baru</label>
                                <input type="text" class="form-control" name="new_password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Ganti Password</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Batal</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Change Profile Photo-->
<div class="modal fade" id="MyChangeProfilePhoto" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="labelDialog">Ubah Foto Profil</span></h4>
            </div>
            <div class="modal-body">
                <form id="MyFormChangeAvatar" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <img src="<?php echo $me->avatar; ?>" class="img-responsive avatar">
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-sm">
                                <label>Pilih Foto (*.jpg | *.png)</label>
                                <input type="file" class="form-control" name="avatar">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit Avatar</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Tutup</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var Auth = {
        init: function(){
            var _this = this;
            
            $('#MyFormChangePassword').validate({
                rules: {
                    username: {
                        minlength: 2,
                        required: true
                    },
                    old_password: {
                        minlength: 2,
                        required: true
                    },
                    new_password: {
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
                    $(form).ajaxSubmit({
                        clearForm: true,
                        type: 'POST',
                        url: '<?php echo get_action_url('service/user/changepassword'); ?>',
                        dataType: 'json',
                        success: function(data){
                            if (data.status){
                                alert('Password berhasil diubah');
                                $('#MyChangePasswordDialog').modal('hide');
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            $('#MyFormChangeAvatar').validate({
                rules: {
                    avatar: {
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
                    $(form).ajaxSubmit({
                        clearForm: true,
                        type: 'POST',
                        url: '<?php echo get_action_url('service/user/avatar'); ?>/<?php echo $me->id; ?>',
                        dataType: 'json',
                        success: function(data){
                            if (data.status){
                                $('img.avatar').attr('src', data.avatar);
                            }else{
                                alert(data.message);
                            }
                        }
                    });
                    
                    return false;
                }
            });
            
            $('#btn-change-password').on('click', function(){
                $('#MyChangePasswordDialog').modal('show');
            });
            
            $('#btn-change-avatar').on('click', function(){
                $('#MyChangeProfilePhoto').modal('show');
            });
        }
    };
    $(document).ready(function(){
        Auth.init();
    });
</script>