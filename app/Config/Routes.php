<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth\AuthController::index');
$routes->post('/login', 'Auth\AuthController::login');
$routes->get('/logout', 'Auth\AuthController::logout');
$routes->get('jurusan/options', 'Admin\JurusanController::options');
$routes->get('kelas/list', 'Admin\KelasController::list');
// Register
$routes->get('/register', 'Auth\AuthController::register');
$routes->post('/register/store', 'Auth\AuthController::storeRegister');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    
    //  route ke profile
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/update', 'Admin\ProfileController::update');
    $routes->post('profile/upload-photo', 'Admin\ProfileController::uploadPhoto');
    $routes->post('profile/upload-logo', 'Admin\ProfileController::uploadLogo');

    // route ke setting_sekolah
    $routes->get('setting-sekolah', 'Admin\SettingSekolahController::index');
    $routes->post('setting-sekolah/update', 'Admin\SettingSekolahController::update');
    $routes->post('setting-sekolah/upload-logo', 'Admin\SettingSekolahController::uploadLogo');

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
    $routes->group('tahunajaran', function ($routes) {
        $routes->get('/', 'Admin\TahunAjaranController::index');
        $routes->get('getData', 'Admin\TahunAjaranController::getData');
        $routes->get('show/(:num)', 'Admin\TahunAjaranController::show/$1');
        $routes->post('store', 'Admin\TahunAjaranController::store');
        $routes->post('update/(:num)', 'Admin\TahunAjaranController::update/$1');
        $routes->post('delete/(:num)', 'Admin\TahunAjaranController::delete/$1');
        $routes->post('setAktif/(:num)', 'Admin\TahunAjaranController::setAktif/$1');

    });
    $routes->group('semester', function ($routes) {
        $routes->get('/', 'Admin\SemesterController::index');
        $routes->get('getData', 'Admin\SemesterController::getData');
        $routes->get('show/(:num)', 'Admin\SemesterController::show/$1');
        $routes->post('store', 'Admin\SemesterController::store');
        $routes->post('update/(:num)', 'Admin\SemesterController::update/$1');
        $routes->post('delete/(:num)', 'Admin\SemesterController::delete/$1');
        $routes->post('setAktif/(:num)', 'Admin\SemesterController::setAktif/$1');

    });

    $routes->group('harilibur', static function ($routes) {
        $routes->get('/', 'Admin\HariLiburController::index');
        $routes->get('getData', 'Admin\HariLiburController::getData');
        $routes->get('show/(:num)', 'Admin\HariLiburController::show/$1');
        $routes->post('store', 'Admin\HariLiburController::store');
        $routes->post('update/(:num)', 'Admin\HariLiburController::update/$1');
        $routes->post('delete/(:num)', 'Admin\HariLiburController::delete/$1');

    });
    // route menuju perangkat
    $routes->group('perangkat', function ($routes) {
        $routes->get('/', 'Admin\PerangkatController::index');
        $routes->get('data', 'Admin\PerangkatController::getData');
        $routes->post('save', 'Admin\PerangkatController::save');
         $routes->get('detail/(:num)', 'Admin\PerangkatController::detail/$1');
        $routes->get('edit/(:num)', 'Admin\PerangkatController::edit/$1');
        $routes->post('delete/(:num)', 'Admin\PerangkatController::delete/$1');
        $routes->post('regenerate-token/(:num)', 'Admin\PerangkatController::regenerateToken/$1');
        $routes->post('reset-device/(:num)', 'Admin\PerangkatController::resetDevice/$1');
        $routes->post('toggle-status/(:num)', 'Admin\PerangkatController::toggleStatus/$1');
    });

      // Halaman utama
    $routes->get('kartu-nfc','Admin\KartuNfcController::index');

    // Data untuk DataTable
    $routes->get('kartu-nfc/data', 'Admin\KartuNfcController::data');

    // Detail kartu
    $routes->get('kartu-nfc/detail/(:num)',  'Admin\KartuNfcController::detail/$1');

    // Update status kartu
    $routes->post('kartu-nfc/status/(:num)','Admin\KartuNfcController::updateStatus/$1' );


    /*
|--------------------------------------------------------------------------
| Pengaturan Absensi
|--------------------------------------------------------------------------
*/
    $routes->group('pengaturanabsensi', static function ($routes) {

        $routes->get('/', 'Admin\PengaturanAbsensiController::index');
        $routes->get('getData', 'Admin\PengaturanAbsensiController::getData');
        $routes->get('show/(:num)', 'Admin\PengaturanAbsensiController::show/$1');
        $routes->post('store', 'Admin\PengaturanAbsensiController::store');
        $routes->post('update/(:num)', 'Admin\PengaturanAbsensiController::update/$1');
        $routes->post('delete/(:num)', 'Admin\PengaturanAbsensiController::delete/$1');
        $routes->post('setAktif/(:num)', 'Admin\PengaturanAbsensiController::setAktif/$1');
        $routes->get('getAktif', 'Admin\PengaturanAbsensiController::getAktif');

    });

});

// route ke API Device
    $routes->group('api/', function ($routes) {

        $routes->post('device/pair', 'Api\DeviceController::pair');

        $routes->post('device/authenticate', 'Api\DeviceController::authenticate');

        $routes->post('device/heartbeat', 'Api\DeviceController::heartbeat');

        $routes->post('device/unpair', 'Api\DeviceController::unpair');

        $routes->post('attendance', 'Api\AbsensiController::attendance');

        $routes->post('nfc/read', 'Api\NfcController::read');

        $routes->get('nfc/guru', 'Api\NfcController::guru');
        
        $routes->get('nfc/siswa', 'Api\NfcController::siswa');

        $routes->post('nfc/register-guru', 'Api\NfcController::registerGuru');

        $routes->post('nfc/register-siswa',  'Api\NfcController::registerSiswa');

    });
