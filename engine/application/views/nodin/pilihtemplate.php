<?php if (!isset($error)): ?>
<div class="list-group">
    <?php foreach ($templates as $tmpl): ?>
    <a href="<?php echo get_action_url('nodin/register/edit/0/'.$tmpl->id); ?>" class="list-group-item">
        <?php echo $tmpl->nama; ?>
    </a>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
<?php endif; 
