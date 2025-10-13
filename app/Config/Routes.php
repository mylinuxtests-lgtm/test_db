<?php

use App\Controllers\ComisionController;
use App\Controllers\AuthController;

// Rutas de autenticación
$routes->get('/', [AuthController::class, 'login']);
$routes->get('/login', [AuthController::class, 'login']);
$routes->post('/auth/login', [AuthController::class, 'processLogin']);
$routes->get('/logout', [AuthController::class, 'logout']);

// Rutas de comisiones
$routes->group('comision', function($routes) {
    $routes->get('/', [ComisionController::class, 'index']); // Lista principal
    $routes->get('crear', [ComisionController::class, 'crear']);
    $routes->post('guardar', [ComisionController::class, 'guardar']);
    $routes->get('getEmpleadoData/(:num)', [ComisionController::class, 'getEmpleadoData']);
    $routes->get('getMunicipioData/(:num)', [ComisionController::class, 'getMunicipioData']);
});