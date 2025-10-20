<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);
$routes->get('/', 'Login::index');
$routes->post('Login/validar_usuario', 'Login::validar_usuario');
$routes->get('Inicio', 'Comisiones::index');
$routes->get('Comisiones', 'Comisiones::index');
$routes->get('Login/cerrar', 'Login::cerrar');
$routes->get('Catalogos/listado_estructura', 'Catalogos::listado_estructura');
$routes->post('Catalogos/getEstructura', 'Catalogos::getEstructura');
$routes->get('Comisiones/agregar', 'Comisiones::agregar');
$routes->post('Comisiones/guardar', 'Comisiones::guardar');
$routes->post('getComisiones', 'Comisiones::getComisiones');
$routes->get('Comisiones/exportar_csv/(:num)', 'Comisiones::exportar_csv/$1');
$routes->get('Comisiones/exportar_csv', 'Comisiones::exportar_csv');
$routes->post('getcomision', 'Getcomision::index');
$routes->get('Comisiones/exportar_csv/(:num)', 'Comisiones::exportar_csv/$1');
$routes->get('index.php', 'Comisiones::index');
$routes->get('Comisiones/descargar_csv_temp/(:any)', 'Comisiones::descargar_csv_temp/$1');
$routes->get('Catalogos/listado_puestos', 'Catalogos::listado_puestos');
$routes->post('Catalogos/getPuestos', 'Catalogos::getPuestos');
$routes->post('Catalogos/guardaPuesto', 'Catalogos::guardaPuesto');
$routes->post('Catalogos/eliminarPuesto', 'Catalogos::eliminarPuesto');
$routes->get('Catalogos/listado_tipo_contrato', 'Catalogos::listado_tipo_contrato');
$routes->post('Catalogos/getTipoContrato', 'Catalogos::getTipoContrato');
$routes->post('Catalogos/guardaTipoContrato', 'Catalogos::guardaTipoContrato');
$routes->post('Catalogos/eliminarTipoContrato', 'Catalogos::eliminarTipoContrato');


/* $routes->get('Agregar/(:any)','Forms::index/$1',['filter' => 'perfil:1,3']);
$routes->get('Agregar/','Forms::index',['filter' => 'perfil:1,3']);
$routes->post('/forms/municipios', 'Forms::getMunicipiosByEstado');
$routes->post('forms/estados', 'Forms::getEstadosByPais');
$routes->post('forms/guardarFolio', 'Forms::guardarFolio');
$routes->post('principal/getFolios', 'Principal::getFolios');
$routes->post('forms/deleteSolicitud', 'Forms::eliminarSolicitud');
$routes->post('forms/getSolicitud', 'Forms::getSolicitud');
$routes->get('catalogos/servidor','Catalogos::index',['filter' => 'perfil:1,3']);
$routes->get('catalogos/dependencia','Catalogos::dependencia',['filter' => 'perfil:1,3']);
$routes->post('getServidoresPublicos', 'Catalogos::getServidoresPublicos');
$routes->post('catalogos/guardaServidor', 'Catalogos::guardaServidor');
$routes->post('eliminarServidor', 'Catalogos::eliminarServidor',['filter' => 'perfil:1']);
$routes->post('getDependencias', 'Catalogos::getDependencias');
$routes->post('eliminarDependencia', 'Catalogos::eliminarDependencia',['filter' => 'perfil:1']);
$routes->post('catalogos/guardaDependencia', 'Catalogos::guardaDependencia'); */