<div class="login-box center-block">
    <div class="logo text-center">
        <a href="#"><img src="<?php echo get_asset_url('img/logo-lubuklinggau.png') ?>" alt=""></a>
    </div>
    <form class="form-horizontal" role="form" method="post" action="<?php echo $submit_url; ?>">
        <?php if (isset($referrer)): ?>
        <input type="hidden" name="referrer" value="<?php echo $referrer; ?>">
        <?php endif; ?>
        <p class="title">Masukkan username dan password</p>
        <?php if ($this->session->flashdata('message')): ?>
        <?php echo create_alert_box($this->session->flashdata('message'), $this->session->flashdata('message_type')); ?>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="username" class="control-label sr-only">Username</label>
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="text" placeholder="username" id="username" name="username" class="form-control" value="<?php echo $remember ? $remember->username :''; ?>">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                </div>
            </div>
        </div>
        <label for="password" class="control-label sr-only">Password</label>
        <div class="form-group">
            <div class="col-sm-12">
                <div class="input-group">
                    <input type="password" placeholder="password" id="password" name="password" class="form-control" value="<?php echo $remember ? $remember->password :''; ?>">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                </div>
            </div>
        </div>
        <label class="fancy-checkbox">
            <input type="checkbox" name="remember" value="remember" <?php echo $remember ? 'checked="checked"' :''; ?>>
            <span>Ingatkan saya berikutnya</span>
        </label>
        <button class="btn btn-custom-primary btn-lg btn-block btn-auth"><i class="fa fa-arrow-circle-o-right"></i> Login</button>
    </form>
</div>