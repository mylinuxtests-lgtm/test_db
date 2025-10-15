<?php $session = \Config\Services::session(); ?>


                <div class="navbar-custom topnav-navbar navColor">
                        <div class="container-fluid">
                            <!-- LOGO -->
                            <a href="<?php echo base_url()?>" class="topnav-logo">
                                <span class="topnav-logo-lg">
                                    <img src="<?php echo base_url()?>assets/images/logo2.png" alt="" width="90" height="50">
                                </span>
                                <span class="topnav-logo-sm">
                                    <img src="<?php echo base_url()?>assets/images/logo2.png" alt="" width="90" height="50">
                                </span>
                            </a>

                            <ul class="list-unstyled topbar-menu float-end mb-0 navColor">

                                <li class="dropdown notification-list d-xl-none">
                                    <!-- <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <i class="dripicons-search noti-icon"></i>
                                    </a> -->
                                    <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                        <form class="p-3">
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                        </form>
                                    </div>
                                </li>
    
                                <li class="dropdown notification-list">
                                    <a class="nav-link dropdown-toggle nav-user arrow-none me-0 navColor" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="account-user-avatar"> 
                                            <img src="<?php echo base_url();?>/<?php if($session->get('foto_perfil')!=''){echo $session->get('foto_perfil');}else{ $random= rand(1,8); echo "assets/images/gto.png"; }?>"  class="rounded-circle">
                                        </span>
                                        <span>
                                            <span class="account-user-name mt-2"><?php echo $session->get('usuario');?></span>
                                        </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                                      
                                        <!-- item-->
                                        <a href="<?php echo base_url()?>index.php/Login/cerrar" class="dropdown-item notify-item">
                                            <i class="mdi mdi-logout me-1"></i>
                                            <span>Salir</span>
                                        </a>
    
                                    </div>
                                </li>

                            </ul>
                            <a class="navbar-toggle"  data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <div class="app-search dropdown ">
                                    <div id="titulo">
                                        <h3>Sistema de Administración de Oficios <?= VERSION_SISTEMA ?></h3>
                                    </div>
                            </div>
                            
                        </div>
                    </div>