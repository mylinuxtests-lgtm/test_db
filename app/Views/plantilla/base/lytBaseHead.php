<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $title;?></title>
      <!--   <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta content="Sistema de Administración de Oficios" name="description" />
        <meta content="ISAPEG" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">
        
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" type="text/css" />
        <!-- third party css -->
        <link href="<?php echo base_url(); ?>assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- third party css end -->

        <!-- App css -->
        <link href="<?php echo base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="<?php echo base_url(); ?>assets/css/app-creative-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
        <link rel="stylesheet" href="<?= base_url("assets/fontawesome_6/css/all.css")?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/fileinput5/css/fileinput.css">
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-icons.css" rel="stylesheet" type="text/css" />

        <script src="<?php echo base_url("assets/js/vendor.min.js"); ?>"></script>        

       <!--  <script type="text/javascript" src="<?php echo base_url();?>/assets/fileinput5/js/fileinput.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/fileinput5/js/locales/es.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/fileinput5/themes/fas/theme.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url();?>/assets/sweetAlert2/sweetalert2.all.min.js"></script>
        
        <!--Bootstrap table-->
        <link href="<?php echo (base_url('assets/bootstrap-table-master/dist_/bootstrap-table.min.css'));?>" rel="stylesheet">
        <script src="<?php echo base_url('assets/bootstrap-table-master/dist_/bootstrap-table.min.js');?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-table-master/dist_/tableExport.min.js');?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-table-master/dist_/bootstrap-table-locale-all.min.js');?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-table-master/dist_/extensions/export/bootstrap-table-export.min.js');?>"></script>
        <script src="<?php echo base_url('assets/js/vendor/drag_select.min.js');?>"></script>
        <script src='<?php echo base_url();?>assets/js/dragula.min.js'></script>
        
        <!-- Parsley -->
        <script src="<?= base_url("assets/parsley_2_9/dist/parsley.js")?>"></script>
        <script src="<?= base_url("assets/parsley_2_9/dist/i18n/es.js")?>"></script>
        <!-- DomPurify-->
        <script src="<?= base_url("assets/DOMPurify/purify.min.js")?>"></script>

        <?php if (isset($scripts)): foreach ($scripts as $js): ?>
            <script src="<?php echo base_url() . "assets/js/{$js}.js" ?>?version=<?php echo time() ?>" type="text/javascript"></script>
                <?php endforeach;
            endif;
        ?>          

    </head>
    
<!-- <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'> -->
<body class="loading" data-layout="topnav" data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}' >
<!-- Begin page -->

       <!--  <script>            
            var base_url = "<?php echo base_url();?>";          
        </script>   -->        
       <!--  <style>
            .parsley-error
            {
            color: #B94A48 !important;
            background-color: #F2DEDE !important;
            border: 1px solid #EED3D7 !important;
            }
            .parsley-errors-list
            {
            color: red
            }
        </style> -->
        <div id="base" data-base-url="<?php echo base_url();?>"></div>
        <div class="wrapper">
            <div class="content-page">
                <div class="content"> 
                    <?php echo view('secciones/vNavBar'); ?>           
                    <?php echo view('secciones/vTopNavBar'); ?>
        
         <!--  <div class="content-page">  -->
            <br>
       <div class="container-fluid">
            