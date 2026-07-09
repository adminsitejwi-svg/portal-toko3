<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

use App\Controllers\Auth\Register;
use App\Controllers\Auth\Login;
use App\Controllers\DashboardManager;
use App\Controllers\DCAdmin;
use App\Controllers\MediaKoneksi;
use App\Controllers\PemilikProject;
use App\Controllers\Vendor;
use App\Controllers\LayananVendor;
use App\Controllers\Perangkat;
use App\Controllers\Jns_perangkat;
use App\Controllers\Alfamidi;
use App\Controllers\Lawson;
use App\Controllers\Alfamart;
use App\Controllers\Map;
use App\Controllers\TypePerangkat;
use App\Controllers\Pelanggan;
use App\Controllers\NomorInet;
use App\Controllers\QuotaSIMCARD;
use App\Controllers\DataSI;
use App\Controllers\NMRInet;
use App\Controllers\Logs;
use App\Controllers\VendorCelulllar;
use App\Controllers\VPN;




/* =========================================================
 |  PUBLIC (tanpa login)
 ========================================================= */

$routes->get('/', [Login::class, 'index']);
$routes->get('/login', [Login::class, 'index']);
$routes->post('/login/auth', [Login::class, 'auth']);
$routes->get('logout', [Login::class, 'logout']);

$routes->get('/register', [Register::class, 'index']);
$routes->post('/register/save', [Register::class, 'save']);

$routes->get('forgot-password', 'Auth\Login::forgotPassword');
$routes->post('forgot-password/update', 'Auth\Login::resetPassword');

/* =========================================================
 |  AREA TERPROTEKSI  (hanya perlu login)
 ========================================================= */
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard-manager', [DashboardManager::class, 'index']);
    $routes->get('Logs', [Logs::class, 'index']);

    // halaman settings
    $routes->get('settings', 'SettingsController::index');
    $routes->get('settings/delete/(:num)', 'SettingsController::delete/$1');


    // DC
    $routes->get('DCAdmin', [DCAdmin::class, 'index']);
    $routes->get('DCAdmin/create', 'DCAdmin::create');
    $routes->post('DCAdmin/save', 'DCAdmin::save');
    $routes->get('DCAdmin/delete/(:num)', 'DCAdmin::delete/$1');
    $routes->post('DCAdmin/update', 'DCAdmin::update');

    // Media Koneksi
    $routes->get('MediaKoneksi', [MediaKoneksi::class, 'index']);
    $routes->get('MediaKoneksi/create', 'MediaKoneksi::create');
    $routes->post('MediaKoneksi/save', 'MediaKoneksi::save');
    $routes->get('MediaKoneksi/delete/(:num)', 'MediaKoneksi::delete/$1');
    $routes->post('MediaKoneksi/update', 'MediaKoneksi::update');

    // Layanan JWI


    // Pemilik Project
    $routes->get('PemilikProject', [PemilikProject::class, 'index']);
    $routes->get('PemilikProject/create', 'PemilikProject::create');
    $routes->post('PemilikProject/save', 'PemilikProject::save');
    $routes->get('PemilikProject/delete/(:num)', 'PemilikProject::delete/$1');
    $routes->post('PemilikProject/update', 'PemilikProject::update');

    // Vendor
    $routes->get('Vendor', [Vendor::class, 'index']);
    $routes->get('Vendor/create', 'Vendor::create');
    $routes->post('vendor/save', 'Vendor::save');
    $routes->post('vendor/update', 'Vendor::update');
    $routes->get('Vendor/delete/(:num)', 'Vendor::delete/$1');

    $routes->get('VendorCelulllar', [VendorCelulllar::class, 'index']);
    $routes->get('VendorCelulllar/create', 'VendorCelulllar::create');
    $routes->post('VendorCelulllar/save', 'VendorCelulllar::save');
    $routes->post('VendorCelulllar/update', 'VendorCelulllar::update');
    $routes->get('VendorCelulllar/delete/(:num)', 'VendorCelulllar::delete/$1');

    // Layanan Vendor
    $routes->get('LayananVendor', [LayananVendor::class, 'index']);
    $routes->get('LayananVendor/create', 'LayananVendor::create');
    $routes->post('LayananVendor/save', 'LayananVendor::save');
    $routes->post('LayananVendor/update', 'LayananVendor::update');
    $routes->get('LayananVendor/delete/(:num)', 'LayananVendor::delete/$1');

    // Perangkat
    $routes->get('Perangkat', [Perangkat::class, 'index']);
    $routes->get('Perangkat/create', 'Perangkat::create');
    $routes->post('Perangkat/save', 'Perangkat::save');
    $routes->post('Perangkat/update', 'Perangkat::update');
    $routes->get('Perangkat/delete/(:num)', 'Perangkat::delete/$1');

    $routes->get('Jns_perangkat', [Jns_perangkat::class, 'index']);
    $routes->get('Jns_perangkat/create', 'Jns_perangkat::create');
    $routes->post('Jns_perangkat/save', 'Jns_perangkat::save');
    $routes->post('Jns_perangkat/update', 'Jns_perangkat::update');
    $routes->get('Jns_perangkat/delete/(:num)', 'Jns_perangkat::delete/$1');

    $routes->get('Alfamidi', [Alfamidi::class, 'index']);
    $routes->get('Alfamidi/create', 'Alfamidi::create');
    $routes->post('Alfamidi/save', 'Alfamidi::save');
    $routes->get('Alfamidi/delete/(:num)', 'Alfamidi::delete/$1');
    $routes->get('Alfamidi/edit/(:num)', 'Alfamidi::edit/$1');
    $routes->post('Alfamidi/update', 'Alfamidi::update');
    $routes->get('Alfamidi/file/(:segment)', 'Alfamidi::serveFile/$1');
    $routes->get('Alfamidi/getMapData', 'Alfamidi::getMapData');

    $routes->get('Lawson', [Lawson::class, 'index']);
    $routes->get('Lawson/create', 'Lawson::create');
    $routes->post('Lawson/save', 'Lawson::save');
    $routes->get('Lawson/delete/(:num)', 'Lawson::delete/$1');
    $routes->get('Lawson/edit/(:num)', 'Lawson::edit/$1');
    $routes->post('Lawson/update', 'Lawson::update');
    $routes->get('Lawson/file/(:segment)', 'Lawson::serveFile/$1');
    $routes->get('Lawson/getMapData', 'Lawson::getMapData');

    $routes->get('Alfamart', [Alfamart::class, 'index']);
    $routes->get('Alfamart/create', 'Alfamart::create');
    $routes->post('Alfamart/save', 'Alfamart::save');
    $routes->get('Alfamart/delete/(:num)', 'Alfamart::delete/$1');
    $routes->get('Alfamart/edit/(:num)', 'Alfamart::edit/$1');
    $routes->post('Alfamart/update', 'Alfamart::update');
    $routes->get('Alfamart/file/(:segment)', 'Alfamart::serveFile/$1');
    $routes->get('Alfamart/getMapData', 'Alfamart::getMapData');

    $routes->get('Map', [Map::class, 'index']);

    $routes->get('TypePerangkat', [TypePerangkat::class, 'index']);
    $routes->get('TypePerangkat/create', 'TypePerangkat::create');
    $routes->post('TypePerangkat/save', 'TypePerangkat::save');
    $routes->post('TypePerangkat/update', 'TypePerangkat::update');
    $routes->get('TypePerangkat/delete/(:num)', 'TypePerangkat::delete/$1');

    $routes->get('Pelanggan', [Pelanggan::class, 'index']);
    $routes->get('Pelanggan/create', 'Pelanggan::create');
    $routes->post('Pelanggan/save', 'Pelanggan::save');
    $routes->post('Pelanggan/update', 'Pelanggan::update');
    $routes->get('Pelanggan/delete/(:num)', 'Pelanggan::delete/$1');



    $routes->get('NomorInet', [NomorInet::class, 'index']);
    $routes->get('NomorInet/create', 'NomorInet::create');
    $routes->post('NomorInet/save', 'NomorInet::save');
    $routes->post('NomorInet/update', 'NomorInet::update');
    $routes->get('NomorInet/delete/(:num)', 'NomorInet::delete/$1');

    $routes->get('QuotaSIMCARD', [QuotaSIMCARD::class, 'index']);
    $routes->get('QuotaSIMCARD/create', 'QuotaSIMCARD::create');
    $routes->post('QuotaSIMCARD/save', 'QuotaSIMCARD::save');
    $routes->post('QuotaSIMCARD/update', 'QuotaSIMCARD::update');
    $routes->get('QuotaSIMCARD/delete/(:num)', 'QuotaSIMCARD::delete/$1');

    $routes->get('DataSI', [DataSI::class, 'index']);
    $routes->get('DataSI/create', 'DataSI::create');
    $routes->post('DataSI/save', 'DataSI::save');
    $routes->post('DataSI/update', 'DataSI::update');
    $routes->get('DataSI/delete/(:num)', 'DataSI::delete/$1');
    $routes->get('DataSI/edit/(:num)', 'DataSI::edit/$1');

    $routes->get('NMRInet', [NMRInet::class, 'index']);
    $routes->get('NMRInet/create', 'NMRInet::create');
    $routes->post('NMRInet/save', 'NMRInet::save');
    $routes->post('NMRInet/update', 'NMRInet::update');
    $routes->get('NMRInet/delete/(:num)', 'NMRInet::delete/$1');
    $routes->get('NMRInet/edit/(:num)', 'NMRInet::edit/$1');

    $routes->get('VPN', [VPN::class, 'index']);
    $routes->get('VPN/create', 'VPN::create');
    $routes->post('VPN/save', 'VPN::save');
    $routes->post('VPN/update', 'VPN::update');
    $routes->get('VPN/delete/(:num)', 'VPN::delete/$1');
    $routes->get('VPN/show/(:num)', 'VPN::show/$1');
});
