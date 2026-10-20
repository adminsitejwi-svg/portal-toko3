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
use App\Controllers\Profile;
use App\Models\InventoryKantor;






/* =========================================================
 |  PUBLIC (tanpa login)
 ========================================================= */

$routes->get('/', [Login::class, 'index']);
$routes->get('/login', [Login::class, 'index']);
$routes->post('/login/auth', [Login::class, 'auth']);
$routes->get('logout', [Login::class, 'logout']);

// Webhook Telegram (hosting) — token rahasia ada di URL, lihat App\Controllers\TelegramWebhook
$routes->post('telegram-webhook/(:segment)', 'TelegramWebhook::handle/$1');

// Setup webhook lewat browser (tanpa SSH) — setara "php spark telegram:webhook set".
// Boleh dihapus lagi setelah dipakai sekali.
$routes->get('telegram-webhook-setup/(:segment)', 'TelegramWebhook::setup/$1');



/* =========================================================
 |  AREA TERPROTEKSI  (hanya perlu login)
 ========================================================= */
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/register', [Register::class, 'index']);
    $routes->post('/register/save', [Register::class, 'save']);

    $routes->get('forgot-password', 'Auth\Login::forgotPassword');
    $routes->post('forgot-password/update', 'Auth\Login::resetPassword');

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
    $routes->get('DCAdmin/show/(:num)', 'DCAdmin::show/$1');

    // Media Koneksi
    $routes->get('MediaKoneksi', [MediaKoneksi::class, 'index']);
    $routes->get('MediaKoneksi/create', 'MediaKoneksi::create');
    $routes->post('MediaKoneksi/save', 'MediaKoneksi::save');
    $routes->get('MediaKoneksi/delete/(:num)', 'MediaKoneksi::delete/$1');
    $routes->post('MediaKoneksi/update', 'MediaKoneksi::update');
    $routes->get('MediaKoneksi/show/(:num)', 'MediaKoneksi::show/$1');

    // Layanan JWI


    // Pemilik Project
    $routes->get('PemilikProject', [PemilikProject::class, 'index']);
    $routes->get('PemilikProject/create', 'PemilikProject::create');
    $routes->post('PemilikProject/save', 'PemilikProject::save');
    $routes->get('PemilikProject/delete/(:num)', 'PemilikProject::delete/$1');
    $routes->post('PemilikProject/update', 'PemilikProject::update');
    $routes->get('PemilikProject/show/(:num)', 'PemilikProject::show/$1');

    // Vendor
    $routes->get('Vendor', [Vendor::class, 'index']);
    $routes->get('Vendor/create', 'Vendor::create');
    $routes->post('vendor/save', 'Vendor::save');
    $routes->post('vendor/update', 'Vendor::update');
    $routes->get('Vendor/delete/(:num)', 'Vendor::delete/$1');
    $routes->get('Vendor/show/(:num)', 'Vendor::show/$1');

    $routes->get('VendorCelulllar', [VendorCelulllar::class, 'index']);
    $routes->get('VendorCelulllar/create', 'VendorCelulllar::create');
    $routes->post('VendorCelulllar/save', 'VendorCelulllar::save');
    $routes->post('VendorCelulllar/update', 'VendorCelulllar::update');
    $routes->get('VendorCelulllar/delete/(:num)', 'VendorCelulllar::delete/$1');
    $routes->get('VendorCelulllar/show/(:num)', 'VendorCelulllar::show/$1');

    // Layanan Vendor
    $routes->get('LayananVendor', [LayananVendor::class, 'index']);
    $routes->get('LayananVendor/create', 'LayananVendor::create');
    $routes->post('LayananVendor/save', 'LayananVendor::save');
    $routes->post('LayananVendor/update', 'LayananVendor::update');
    $routes->get('LayananVendor/delete/(:num)', 'LayananVendor::delete/$1');
    $routes->get('LayananVendor/show/(:num)', 'LayananVendor::show/$1');

    // Perangkat
    $routes->get('Perangkat', [Perangkat::class, 'index']);
    $routes->get('Perangkat/create', 'Perangkat::create');
    $routes->post('Perangkat/save', 'Perangkat::save');
    $routes->post('Perangkat/update', 'Perangkat::update');
    $routes->get('Perangkat/delete/(:num)', 'Perangkat::delete/$1');
    $routes->get('Perangkat/show/(:num)', 'Perangkat::show/$1');

    $routes->get('Jns_perangkat', [Jns_perangkat::class, 'index']);
    $routes->get('Jns_perangkat/create', 'Jns_perangkat::create');
    $routes->post('Jns_perangkat/save', 'Jns_perangkat::save');
    $routes->post('Jns_perangkat/update', 'Jns_perangkat::update');
    $routes->get('Jns_perangkat/delete/(:num)', 'Jns_perangkat::delete/$1');
    $routes->get('Jns_perangkat/show/(:num)', 'Jns_perangkat::show/$1');

    $routes->get('Alfamidi', [Alfamidi::class, 'index']);
    $routes->get('Alfamidi/create', 'Alfamidi::create');
    $routes->post('Alfamidi/save', 'Alfamidi::save');
    $routes->get('Alfamidi/delete/(:num)', 'Alfamidi::delete/$1');
    $routes->get('Alfamidi/edit/(:num)', 'Alfamidi::edit/$1');
    $routes->get('Alfamidi/view/(:num)', 'Alfamidi::view/$1');
    $routes->post('Alfamidi/update', 'Alfamidi::update');
    $routes->get('Alfamidi/file/(:segment)', 'Alfamidi::serveFile/$1');
    $routes->get('Alfamidi/getMapData', 'Alfamidi::getMapData');

    $routes->get('Lawson', [Lawson::class, 'index']);
    $routes->get('Lawson/create', 'Lawson::create');
    $routes->post('Lawson/save', 'Lawson::save');
    $routes->get('Lawson/delete/(:num)', 'Lawson::delete/$1');
    $routes->get('Lawson/edit/(:num)', 'Lawson::edit/$1');
    $routes->get('Lawson/view/(:num)', 'Lawson::view/$1');
    $routes->post('Lawson/update', 'Lawson::update');
    $routes->get('Lawson/file/(:segment)', 'Lawson::serveFile/$1');
    $routes->get('Lawson/getMapData', 'Lawson::getMapData');

    $routes->get('Alfamart', [Alfamart::class, 'index']);
    $routes->get('Alfamart/create', 'Alfamart::create');
    $routes->post('Alfamart/save', 'Alfamart::save');
    $routes->get('Alfamart/delete/(:num)', 'Alfamart::delete/$1');
    $routes->get('Alfamart/edit/(:num)', 'Alfamart::edit/$1');
    $routes->get('Alfamart/view/(:num)', 'Alfamart::view/$1');
    $routes->post('Alfamart/update', 'Alfamart::update');
    $routes->get('Alfamart/file/(:segment)', 'Alfamart::serveFile/$1');
    $routes->get('Alfamart/getMapData', 'Alfamart::getMapData');

    $routes->get('Map', [Map::class, 'index']);

    $routes->get('TypePerangkat', [TypePerangkat::class, 'index']);
    $routes->get('TypePerangkat/create', 'TypePerangkat::create');
    $routes->post('TypePerangkat/save', 'TypePerangkat::save');
    $routes->post('TypePerangkat/update', 'TypePerangkat::update');
    $routes->get('TypePerangkat/delete/(:num)', 'TypePerangkat::delete/$1');
    $routes->get('TypePerangkat/show/(:num)', 'TypePerangkat::show/$1');

    $routes->get('Pelanggan', [Pelanggan::class, 'index']);
    $routes->get('Pelanggan/create', 'Pelanggan::create');
    $routes->post('Pelanggan/save', 'Pelanggan::save');
    $routes->post('Pelanggan/update', 'Pelanggan::update');
    $routes->get('Pelanggan/delete/(:num)', 'Pelanggan::delete/$1');
    $routes->get('Pelanggan/show/(:num)', 'Pelanggan::show/$1');


    $routes->get('NomorInet', [NomorInet::class, 'index']);
    $routes->get('NomorInet/create', 'NomorInet::create');
    $routes->post('NomorInet/save', 'NomorInet::save');
    $routes->post('NomorInet/update', 'NomorInet::update');
    $routes->get('NomorInet/delete/(:num)', 'NomorInet::delete/$1');
    $routes->get('NomorInet/show/(:num)', 'NomorInet::show/$1');

    $routes->get('QuotaSIMCARD', [QuotaSIMCARD::class, 'index']);
    $routes->get('QuotaSIMCARD/create', 'QuotaSIMCARD::create');
    $routes->post('QuotaSIMCARD/save', 'QuotaSIMCARD::save');
    $routes->post('QuotaSIMCARD/update', 'QuotaSIMCARD::update');
    $routes->get('QuotaSIMCARD/delete/(:num)', 'QuotaSIMCARD::delete/$1');
    $routes->get('QuotaSIMCARD/show/(:num)', 'QuotaSIMCARD::show/$1');

    $routes->get('DataSI', [DataSI::class, 'index']);
    $routes->get('DataSI/create', 'DataSI::create');
    $routes->post('DataSI/save', 'DataSI::save');
    $routes->post('DataSI/update', 'DataSI::update');
    $routes->get('DataSI/delete/(:num)', 'DataSI::delete/$1');
    $routes->get('DataSI/edit/(:num)', 'DataSI::edit/$1');
    $routes->get('DataSI/show/(:num)', 'DataSI::show/$1');

    $routes->get('NMRInet', [NMRInet::class, 'index']);
    $routes->get('NMRInet/create', 'NMRInet::create');
    $routes->post('NMRInet/save', 'NMRInet::save');
    $routes->post('NMRInet/update', 'NMRInet::update');
    $routes->get('NMRInet/delete/(:num)', 'NMRInet::delete/$1');
    $routes->get('NMRInet/edit/(:num)', 'NMRInet::edit/$1');
    $routes->get('NMRInet/show/(:num)', 'NMRInet::show/$1');

    $routes->get('VPN', [VPN::class, 'index']);
    $routes->get('VPN/create', 'VPN::create');
    $routes->post('VPN/save', 'VPN::save');
    $routes->post('VPN/update', 'VPN::update');
    $routes->get('VPN/delete/(:num)', 'VPN::delete/$1');
    $routes->get('VPN/show/(:num)', 'VPN::show/$1');

    $routes->get('Profile', 'Profile::index');
    $routes->post('profile/delete', 'Profile::deleteAccount');

    $routes->get('Calendar',           'Calendar::index');
    $routes->get('Calendar/events',    'Calendar::events');
    $routes->post('Calendar/save',     'Calendar::save');
    $routes->post('Calendar/delete/(:num)', 'Calendar::delete/$1');
    $routes->post('Calendar/import',   'Calendar::import');
    $routes->get('Calendar/notes',  'Calendar::notes');
    $routes->post('Calendar/notes', 'Calendar::notes');

    $routes->get('Piket',                'Piket::index');
    $routes->get('Piket/events',         'Piket::events');
    $routes->post('Piket/save',          'Piket::save');
    $routes->post('Piket/delete/(:num)', 'Piket::delete/$1');
    $routes->post('Piket/import',        'Piket::import');


    $routes->get('InventoryKantor', 'InventoryKantor::index');
    $routes->get('InventoryKantor/create', 'InventoryKantor::create');
    $routes->post('InventoryKantor/save', 'InventoryKantor::save');
    $routes->post('InventoryKantor/update', 'InventoryKantor::update');
    $routes->get('InventoryKantor/delete/(:num)', 'InventoryKantor::delete/$1');
    $routes->get('InventoryKantor/show/(:num)', 'InventoryKantor::show/$1');

    $routes->get('RFO', 'RFO::index');
    $routes->get('RFO/create', 'RFO::create');
    $routes->post('RFO/save', 'RFO::save');
    $routes->get('RFO/edit/(:num)', 'RFO::edit/$1');
    $routes->post('RFO/update', 'RFO::update');
    $routes->get('RFO/delete/(:num)', 'RFO::delete/$1');
    $routes->get('RFO/view/(:num)', 'RFO::view/$1');
    $routes->post('RFO/email', 'RFO::email');
    $routes->get('RFO/email-log', 'RFO::emailLog');
    $routes->get('RFO/email-log/body/(:num)', 'RFO::emailLogBody/$1');
    $routes->get('RFO/pdf/(:num)', 'RFO::pdf/$1');
    $routes->get('RFO/email-preview/(:num)', 'RFO::emailPreview/$1');

    $routes->get('MDMaintenance', 'MDMaintenance::index');
    $routes->get('MDMaintenance/create', 'MDMaintenance::create');
    $routes->post('MDMaintenance/save', 'MDMaintenance::save');
    $routes->get('MDMaintenance/edit/(:num)', 'MDMaintenance::edit/$1');
    $routes->post('MDMaintenance/update', 'MDMaintenance::update');
    $routes->get('MDMaintenance/delete/(:num)', 'MDMaintenance::delete/$1');
    $routes->get('MDMaintenance/view/(:num)', 'MDMaintenance::view/$1');
    $routes->post('MDMaintenance/email', 'MDMaintenance::email');
    $routes->get('MDMaintenance/email-log', 'MDMaintenance::emailLog');
    $routes->get('MDMaintenance/email-log/body/(:num)', 'MDMaintenance::emailLogBody/$1');
    $routes->get('MDMaintenance/pdf/(:num)', 'MDMaintenance::pdf/$1');
    $routes->get('MDMaintenance/email-preview/(:num)', 'MDMaintenance::emailPreview/$1');

    $routes->get('MaintenanceReport', 'MaintenanceReport::index');
    $routes->post('MaintenanceReport/save', 'MaintenanceReport::save');
    $routes->post('MaintenanceReport/update', 'MaintenanceReport::update');
    $routes->get('MaintenanceReport/delete/(:num)', 'MaintenanceReport::delete/$1');

    // Daily Report SAT Group (menu sendiri di sidebar): tab Tiket FO Loss
    $routes->get('ReportSat', 'ReportSat::index');
    $routes->get('ReportSat/create', 'ReportSat::create');
    $routes->get('ReportSat/edit/(:num)', 'ReportSat::edit/$1');
    $routes->post('ReportSat/save', 'ReportSat::save');
    $routes->post('ReportSat/update', 'ReportSat::update');
    $routes->get('ReportSat/delete/(:num)', 'ReportSat::delete/$1');

    // Daily Report SAT Group: tab Resi Pengiriman
    $routes->get('ResiPengiriman', 'ResiPengiriman::index');
    $routes->get('ResiPengiriman/create', 'ResiPengiriman::create');
    $routes->get('ResiPengiriman/edit/(:num)', 'ResiPengiriman::edit/$1');
    $routes->post('ResiPengiriman/save', 'ResiPengiriman::save');
    $routes->post('ResiPengiriman/update', 'ResiPengiriman::update');
    $routes->get('ResiPengiriman/delete/(:num)', 'ResiPengiriman::delete/$1');

    // Data Center (menu sendiri di sidebar)
    $routes->get('DataCenter', 'DataCenter::index');
    $routes->get('DataCenter/create', 'DataCenter::create');
    $routes->get('DataCenter/edit/(:num)', 'DataCenter::edit/$1');
    $routes->post('DataCenter/save', 'DataCenter::save');
    $routes->post('DataCenter/update', 'DataCenter::update');
    $routes->get('DataCenter/delete/(:num)', 'DataCenter::delete/$1');

    // Data Interkoneksi (menu All NOC di sidebar)
    $routes->get('Interkoneksi', 'Interkoneksi::index');
    $routes->get('Interkoneksi/create', 'Interkoneksi::create');
    $routes->get('Interkoneksi/edit/(:num)', 'Interkoneksi::edit/$1');
    $routes->post('Interkoneksi/save', 'Interkoneksi::save');
    $routes->post('Interkoneksi/update', 'Interkoneksi::update');
    $routes->get('Interkoneksi/delete/(:num)', 'Interkoneksi::delete/$1');
    $routes->get('Interkoneksi/file/(:num)', 'Interkoneksi::file/$1');

    // Devices Backbone (menu All NOC di sidebar)
    $routes->get('DevicesBackbone', 'DevicesBackbone::index');
    $routes->get('DevicesBackbone/create', 'DevicesBackbone::create');
    $routes->get('DevicesBackbone/edit/(:num)', 'DevicesBackbone::edit/$1');
    $routes->post('DevicesBackbone/save', 'DevicesBackbone::save');
    $routes->post('DevicesBackbone/update', 'DevicesBackbone::update');
    $routes->get('DevicesBackbone/delete/(:num)', 'DevicesBackbone::delete/$1');

    // BTS (menu All NOC di sidebar)
    $routes->get('Bts', 'Bts::index');
    $routes->get('Bts/create', 'Bts::create');
    $routes->get('Bts/edit/(:num)', 'Bts::edit/$1');
    $routes->post('Bts/save', 'Bts::save');
    $routes->post('Bts/update', 'Bts::update');
    $routes->get('Bts/delete/(:num)', 'Bts::delete/$1');

    // Data Pelanggan (menu All NOC di sidebar, beda dengan menu Pelanggan lama)
    $routes->get('PelangganNoc', 'PelangganNoc::index');
    $routes->get('PelangganNoc/create', 'PelangganNoc::create');
    $routes->get('PelangganNoc/edit/(:num)', 'PelangganNoc::edit/$1');
    $routes->post('PelangganNoc/save', 'PelangganNoc::save');
    $routes->post('PelangganNoc/update', 'PelangganNoc::update');
    $routes->get('PelangganNoc/delete/(:num)', 'PelangganNoc::delete/$1');
    $routes->get('PelangganNoc/file/(:num)', 'PelangganNoc::file/$1');

    // Data Vendor (menu All NOC di sidebar, beda dengan menu Vendor Non Celullar)
    $routes->get('VendorNoc', 'VendorNoc::index');
    $routes->get('VendorNoc/create', 'VendorNoc::create');
    $routes->get('VendorNoc/edit/(:num)', 'VendorNoc::edit/$1');
    $routes->post('VendorNoc/save', 'VendorNoc::save');
    $routes->post('VendorNoc/update', 'VendorNoc::update');
    $routes->get('VendorNoc/delete/(:num)', 'VendorNoc::delete/$1');

    $routes->get('HistoryReport', 'HistoryReport::index');
    $routes->post('HistoryReport/save', 'HistoryReport::save');
    $routes->post('HistoryReport/update', 'HistoryReport::update');
    $routes->get('HistoryReport/delete/(:num)', 'HistoryReport::delete/$1');

    // Gangguan per report (tombol Settings di History Report)
    $routes->get('HistoryReport/gangguan/(:num)', 'Gangguan::index/$1');
    $routes->post('HistoryReport/gangguan/save', 'Gangguan::save');
    $routes->post('HistoryReport/gangguan/update', 'Gangguan::update');
    $routes->get('HistoryReport/gangguan/delete/(:num)', 'Gangguan::delete/$1');

    // Follow up per report (tab Follow Up di halaman gangguan)
    $routes->post('HistoryReport/followup/save', 'FollowUp::save');
    $routes->post('HistoryReport/followup/update', 'FollowUp::update');
    $routes->get('HistoryReport/followup/delete/(:num)', 'FollowUp::delete/$1');

    // Pengiriman per report (tab Pengiriman di halaman gangguan)
    $routes->post('HistoryReport/pengiriman/save', 'Pengiriman::save');
    $routes->post('HistoryReport/pengiriman/update', 'Pengiriman::update');
    $routes->get('HistoryReport/pengiriman/delete/(:num)', 'Pengiriman::delete/$1');

    // Maintenance per report (tab Maintenance di halaman gangguan)
    $routes->post('HistoryReport/maintenance/save', 'Maintenance::save');
    $routes->post('HistoryReport/maintenance/update', 'Maintenance::update');
    $routes->get('HistoryReport/maintenance/delete/(:num)', 'Maintenance::delete/$1');

    // Catatan per report (tab Catatan di halaman gangguan)
    $routes->post('HistoryReport/catatan/save', 'Catatan::save');
    $routes->post('HistoryReport/catatan/update', 'Catatan::update');
    $routes->get('HistoryReport/catatan/delete/(:num)', 'Catatan::delete/$1');

    // Aktivasi per report (tab Aktivasi di halaman gangguan)
    $routes->post('HistoryReport/aktivasi/save', 'Aktivasi::save');
    $routes->post('HistoryReport/aktivasi/update', 'Aktivasi::update');
    $routes->get('HistoryReport/aktivasi/delete/(:num)', 'Aktivasi::delete/$1');

    $routes->get('Interkoneksi', 'Interkoneksi::index');
    $routes->get('Interkoneksi/create', 'Interkoneksi::create');
    $routes->get('Interkoneksi/edit/(:num)', 'Interkoneksi::edit/$1');
    $routes->post('Interkoneksi/save', 'Interkoneksi::save');
    $routes->post('Interkoneksi/update', 'Interkoneksi::update');
    $routes->get('Interkoneksi/delete/(:num)', 'Interkoneksi::delete/$1');
    $routes->get('Interkoneksi/file/(:num)', 'Interkoneksi::file/$1');
});
