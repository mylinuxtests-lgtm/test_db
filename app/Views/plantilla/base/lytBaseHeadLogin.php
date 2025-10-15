<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Sistema de Administración de Oficios" name="description" />
        <meta content="ISAPEG" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">

        <!-- App css -->
        <link href="<?php echo base_url();?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="<?php echo base_url();?>assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />

        <script src="<?php echo base_url(); ?>assets/js/vendor.min.js"></script>
       
        <script type="text/javascript" src="<?php echo base_url();?>assets/sweetAlert2/sweetalert2.all.min.js"></script>
        <!-- <script src="<?= base_url("/js/general.js")?>"></script> -->
        <!-- third party js ends -->

        <?php if (isset($scripts)): foreach ($scripts as $js): ?>
            <script src="<?php echo base_url() . "assets/js/{$js}.js" ?>?version=<?php echo time() ?>" type="text/javascript"></script>
                <?php endforeach;
            endif;
        ?>       

    </head>

    <body class="authentication-bg pb-0" data-layout-config='{"darkMode":false}'>
        <!-- <script>            
            var base_url = "<?php echo base_url();?>";          
        </script>   -->
        <div id="base" data-base-url="<?php echo base_url();?>"></div>