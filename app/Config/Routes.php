<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth\AuthController::index');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/logout', 'Auth\AuthController::logout');
$routes->get('jurusan/options', 'Admin\JurusanController::options');
$routes->get('kelas/list', 'Admin\KelasController::list');

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
     $routes->group('kelas', function ($routes) {
        $routes->get('/', 'Admin\KelasController::index');
        $routes->get('data', 'Admin\KelasController::getData');
        $routes->post('store', 'Admin\KelasController::store');
        
        $routes->get('show/(:num)', 'Admin\KelasController::show/$1');
        $routes->post('update/(:num)', 'Admin\KelasController::update/$1');
        $routes->delete('delete/(:num)', 'Admin\KelasController::delete/$1');
    });

        $routes->group('guru', function ($routes) {

        $routes->get('/', 'Admin\GuruController::index');
        $routes->get('data', 'Admin\GuruController::getData');
        $routes->post('store', 'Admin\GuruController::store');
        $routes->get('show/(:num)', 'Admin\GuruController::show/$1');
        $routes->post('update/(:num)', 'Admin\GuruController::update/$1');
        $routes->delete('delete/(:num)', 'Admin\GuruController::delete/$1');

    });
        $routes->group('siswa', function ($routes) {

        $routes->get('/', 'Admin\SiswaController::index');
        $routes->get('data', 'Admin\SiswaController::getData');
        $routes->post('store', 'Admin\SiswaController::store');
        $routes->get('show/(:num)', 'Admin\SiswaController::show/$1');
        $routes->post('update/(:num)', 'Admin\SiswaController::update/$1');
        $routes->delete('delete/(:num)', 'Admin\SiswaController::delete/$1');

    });
});
