<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth\AuthController::index');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/logout', 'Auth\AuthController::logout');


$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');

     $routes->group('jurusan', function ($routes) {
        $routes->get('/', 'Admin\JurusanController::index');
        $routes->get('data', 'Admin\JurusanController::getData');
        $routes->post('store', 'Admin\JurusanController::store');
        $routes->get('show/(:num)', 'Admin\JurusanController::show/$1');
        $routes->post('update/(:num)', 'Admin\JurusanController::update/$1');
        $routes->delete('delete/(:num)', 'Admin\JurusanController::delete/$1');
    });
});
