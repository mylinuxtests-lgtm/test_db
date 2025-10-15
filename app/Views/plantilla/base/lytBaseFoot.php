        </div>         
    </div>
 <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="footer-links d-none d-md-block">
                                    <a href="javascript: void(0);"><img src="<?= base_url("/assets/images/Logo_DGP-V1.png")?>" height="35px"> </a>
                                  
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">DIRECCIÓN GENERAL DE PLANEACIÓN</a>
                                    <a href="javascript: void(0);">DEPARTAMENTO DE DESARROLLO DE SISTEMAS WEB</a>
                                    <a href="javascript: void(0);"><?= date("Y") ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div><!--content-page-->

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

           
        </div>
        <!-- END wrapper -->
    <!-- third party js -->
       
        <!-- bundle -->
        <script src="<?php echo base_url("assets/js/app.min.js"); ?>"></script>

        <!-- dragula js-->
        <!--<script src="<?php //echo base_url();?>/assets/js/vendor/dragula.min.js"></script>-->
        
        <!-- demo app -->
        <!--<script src="<?php echo base_url(); ?>/assets/js/pages/demo.dashboard.js"></script>-->
        <!--<script src="<?php //echo base_url();?>/assets/js/ui/component.dragula.js"></script>-->
        <!-- end demo js-->        
        <script src="<?= base_url("assets/fontawesome_6/js/all.js")?>"></script>        
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-icons.css" rel="stylesheet" type="text/css" />

        <!-- datepicker locales -->
        <script src="<?=base_url("assets/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js")?>" charset="UTF-8"></script>
        
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        </script>
        
        <!--modal checksum-->
        <div id="mdl_checksum" class="mdl_checksum modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="mdl_content_checksum"></div>
            </div>
        </div>
        <script type="text/javascript">
            function show_modal_checksum() {
                $('#mdl_content_checksum').load( base_url + '/index.php/Checksum/');
                $("#mdl_checksum").modal('show');
            }
        </script>
    </body>
</html>