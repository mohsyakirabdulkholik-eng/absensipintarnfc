<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth\AuthController::index');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/logout', 'Auth\AuthController::logout');

$routes->get('/dashboard', 'Admin\DashboardController::index');


// $routes->group('admin', ['filter' => 'auth'], function ($routes) {
//     $routes->get('/dashboard', 'admin\DashboardController::index');
// });
