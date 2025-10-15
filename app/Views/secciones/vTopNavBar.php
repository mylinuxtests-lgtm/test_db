<?php  $session = \Config\Services::session();?>
<?php 

$perfil = $session->get('id_perfil');
?>
<div class="topnav shadow-sm " >
    <div class="container-fluid" >
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu "  >
       
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <!-- <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url();?>" id="topnav-dashboards" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
                        <a class="nav-link dropdown-toggle arrow-none" href="<?php echo base_url();?>" id="topnav-dashboards" >
                            <i class="uil-dashboard me-1"></i>Inicio<!-- <div class="arrow-down"></div> -->
                        </a>
                    </li>
                    <?php if($perfil == 1 || $perfil == 3):?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="<?= base_url('index.php/Comisiones/agregar') ?>" id="topnav-dashboards" >
                            <i class="uil-plus-circle me-1"></i>Agregar 
                        </a>
                    </li>      
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-catalogos" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-window me-1"></i>Catálogos <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-catalogos">
                            <a href="<?php echo  base_url()."index.php/Catalogos/listado_estructura"; ?>" class="dropdown-item">Estructura</a>
                            <a href="<?php echo  base_url()."index.php/Catalogos/listado_estructura"; ?>" class="dropdown-item">Puestos</a>
                            <a href="<?php echo  base_url()."index.php/Catalogos/listado_estructura"; ?>" class="dropdown-item">Tipo contrato</a>
                           <!--  <a href="<?= base_url("/index.php/Catalogos/formCatFuenteFinanciamiento")?>" class="dropdown-item">Fuente Financiamiento</a>  -->
                        </div>
                    </li>  
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</div>