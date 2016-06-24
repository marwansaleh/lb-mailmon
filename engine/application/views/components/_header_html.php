<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->

    <head>
        <title><?php echo APP_TITLE; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="author" content="Marwan marwan.saleh@ymail.com">
        <!-- CSS -->
        <link href="<?php echo get_asset_url('css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('css/main.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('css/my-custom-styles.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('css/bootstrap-select/bootstrap-select.min.css') ?>" rel="stylesheet" type="text/css">
        <?php if (isset($themes) && $themes==THEME_DARK_TRANSPARENT): ?>
        <!-- black and transparent -->
        <link href="<?php echo get_asset_url('css/skins/transparent.css') ?>" rel="stylesheet" type="text/css">
        <!-- tree view -->
        <link href="<?php echo get_asset_url('js/plugins/tree/themes/default-dark/style.min.css') ?>" rel="stylesheet" type="text/css">
        <?php else: ?>
        <!-- tree view -->
        <link href="<?php echo get_asset_url('js/plugins/tree/themes/default/style.min.css') ?>" rel="stylesheet" type="text/css">
        <?php endif; ?>
        
        <!-- select2 -->
        <link href="<?php echo get_asset_url('js/plugins/select2/4.0.2/select2.min.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('js/plugins/select2/4.0.2/select2-bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
        
        <!-- DataTables -->
        <link href="<?php echo get_asset_url('DataTables/extensions/Buttons/css/buttons.dataTables.min.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('DataTables/extensions/Buttons/css/buttons.bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('DataTables/extensions/Select/css/select.dataTables.min.css') ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset_url('DataTables/extensions/Select/css/select.bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
        
        <!--PrintArea-->
        <link href="<?php echo get_asset_url('js/plugins/printArea/jquery.printarea.css') ?>" rel="stylesheet" type="text/css">
        <!--[if lte IE 9]>
                <link href="<?php echo get_asset_url('css/main-ie.css') ?>" rel="stylesheet" type="text/css"/>
                <link href="<?php echo get_asset_url('css/main-ie-part2.css') ?>" rel="stylesheet" type="text/css"/>
        <![endif]-->
        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_asset_url('ico/bsm-favicon144x144.png'); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_asset_url('ico/bsm-favicon114x114.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_asset_url('ico/bsm-favicon72x72.png') ?>">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo get_asset_url('ico/bsm-favicon57x57.png'); ?>">
        <link rel="shortcut icon" href="<?php echo get_asset_url('ico/favicon.png') ?>">
        
        <script src="<?php echo get_asset_url('js/jquery/jquery-2.1.0.min.js') ?>"></script>
        <script src="<?php echo get_asset_url('js/main.js') ?>"></script>
        <style type="text/css">
            /*.no-js { display: none; }*/
        </style>
    </head>
    
    <body class="dashboard2">
        <noscript>
        <h2 style="color: red; margin: 10px; text-align: center;">
            Warning !!<br>
            You don't have javascript enabled.  Application shall not continue without it.<br>
            Please enable in your browser javascript setting.</h2>
        </noscript>
        