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

    // Data Rak DC (menu All NOC di sidebar): Data Center, Data Rak, Data Perangkat & Visual Rak dalam satu halaman
    $routes->get('DataRakDC', 'DataRakDC::index');
    $routes->post('DataRakDC/dc/save', 'DataRakDC::saveDc');
    $routes->post('DataRakDC/dc/update', 'DataRakDC::updateDc');
    $routes->get('DataRakDC/dc/delete/(:num)', 'DataRakDC::deleteDc/$1');
    $routes->post('DataRakDC/rak/save', 'DataRakDC::saveRak');
    $routes->post('DataRakDC/rak/update', 'DataRakDC::updateRak');
    $routes->get('DataRakDC/rak/delete/(:num)', 'DataRakDC::deleteRak/$1');
    $routes->post('DataRakDC/perangkat/save', 'DataRakDC::savePerangkat');
    $routes->post('DataRakDC/perangkat/update', 'DataRakDC::updatePerangkat');
    $routes->get('DataRakDC/perangkat/delete/(:num)', 'DataRakDC::deletePerangkat/$1');

    // NetMon (menu All NOC di sidebar): kartu link website monitoring, form berupa pop up
    $routes->get('NetMon', 'NetMon::index');
    $routes->post('NetMon/save', 'NetMon::save');
    $routes->post('NetMon/update', 'NetMon::update');
    $routes->get('NetMon/delete/(:num)', 'NetMon::delete/$1');

    // Menu All NOC yang belum dibuat: tampil halaman "Segera Hadir" (nama & ikon di Config/AllNoc.php)
    // (saat halamannya jadi, ganti handler route-nya & ubah 'soon' di Config/AllNoc.php)
    $routes->get('FtpClient', 'ComingSoon::show/FtpClient');
    $routes->get('IpPrivate', 'ComingSoon::show/IpPrivate');
    $routes->get('SuratTugas', 'ComingSoon::show/SuratTugas');

    // Data IP Public (menu Data Teknis NOC): Judul (per perusahaan) -> Sub Judul -> Subnet, form berupa pop up
    $routes->get('IpPublic', 'IpPublic::index');
    $routes->post('IpPublic/judul/save', 'IpPublic::saveJudul');
    $routes->post('IpPublic/judul/update', 'IpPublic::updateJudul');
    $routes->get('IpPublic/judul/delete/(:num)', 'IpPublic::deleteJudul/$1');
    $routes->post('IpPublic/subjudul/save', 'IpPublic::saveSubjudul');
    $routes->post('IpPublic/subjudul/update', 'IpPublic::updateSubjudul');
    $routes->get('IpPublic/subjudul/delete/(:num)', 'IpPublic::deleteSubjudul/$1');
    $routes->post('IpPublic/subnet/save', 'IpPublic::saveSubnet');
    $routes->post('IpPublic/subnet/update', 'IpPublic::updateSubnet');
    $routes->get('IpPublic/subnet/delete/(:num)', 'IpPublic::deleteSubnet/$1');

    // Data Router (menu Data Teknis NOC di sidebar): form berupa pop up, Nama Router / SN / IP tidak boleh dobel
    $routes->get('DataRouter', 'DataRouter::index');
    $routes->post('DataRouter/save', 'DataRouter::save');
    $routes->post('DataRouter/update', 'DataRouter::update');
    $routes->get('DataRouter/delete/(:num)', 'DataRouter::delete/$1');

    // Data Switch (menu Data Teknis NOC di sidebar): form berupa pop up, Nama Switch / IP / SN tidak boleh dobel
    $routes->get('DataSwitch', 'DataSwitch::index');
    $routes->post('DataSwitch/save', 'DataSwitch::save');
    $routes->post('DataSwitch/update', 'DataSwitch::update');
    $routes->get('DataSwitch/delete/(:num)', 'DataSwitch::delete/$1');

    // Data OLT (menu Data Teknis NOC di sidebar): form berupa pop up, Nama OLT / IP / SN tidak boleh dobel
    $routes->get('DataOlt', 'DataOlt::index');
    $routes->post('DataOlt/save', 'DataOlt::save');
    $routes->post('DataOlt/update', 'DataOlt::update');
    $routes->get('DataOlt/delete/(:num)', 'DataOlt::delete/$1');

    // KMZ (menu Data Teknis NOC di sidebar): upload file .kmz / .kml maks. 30 MB, info file tercatat otomatis
    $routes->get('Kmz', 'Kmz::index');
    $routes->post('Kmz/upload', 'Kmz::upload');
    $routes->get('Kmz/download/(:num)', 'Kmz::download/$1');
    $routes->get('Kmz/delete/(:num)', 'Kmz::delete/$1');

    // Data ODC & ODP (menu Data Teknis NOC di sidebar): tab Wilayah / ODC / ODP, kode & nama ODC / ODP otomatis per wilayah
    $routes->get('DataOdcOdp', 'DataOdcOdp::index');
    $routes->post('DataOdcOdp/wilayah/save', 'DataOdcOdp::saveWilayah');
    $routes->post('DataOdcOdp/wilayah/update', 'DataOdcOdp::updateWilayah');
    $routes->get('DataOdcOdp/wilayah/delete/(:num)', 'DataOdcOdp::deleteWilayah/$1');
    $routes->post('DataOdcOdp/odc/save', 'DataOdcOdp::saveOdc');
    $routes->post('DataOdcOdp/odc/update', 'DataOdcOdp::updateOdc');
    $routes->get('DataOdcOdp/odc/delete/(:num)', 'DataOdcOdp::deleteOdc/$1');
    $routes->post('DataOdcOdp/odp/save', 'DataOdcOdp::saveOdp');
    $routes->post('DataOdcOdp/odp/update', 'DataOdcOdp::updateOdp');
    $routes->get('DataOdcOdp/odp/delete/(:num)', 'DataOdcOdp::deleteOdp/$1');
    $routes->post('DataOdcOdp/pelanggan/save', 'DataOdcOdp::savePelanggan');
    $routes->post('DataOdcOdp/pelanggan/update', 'DataOdcOdp::updatePelanggan');
    $routes->get('DataOdcOdp/pelanggan/delete/(:num)', 'DataOdcOdp::deletePelanggan/$1');

    // Monthly Report: tambah / edit lewat pop up, Duration dihitung otomatis dari Start - Finish Time
    $routes->get('MonthlyReport', 'MonthlyReport::index');
    $routes->post('MonthlyReport/save', 'MonthlyReport::save');
    $routes->post('MonthlyReport/update', 'MonthlyReport::update');
    $routes->get('MonthlyReport/delete/(:num)', 'MonthlyReport::delete/$1');

    // Survey Report: tampilan & fitur seperti RFO (form, View, PDF terkunci, kirim email + riwayat),
    // perusahaan & logo sama dengan RFO; struktur dari Survey Report Form Yesnet.docx
    $routes->get('SurveyReport', 'SurveyReport::index');
    $routes->get('SurveyReport/create', 'SurveyReport::create');
    $routes->post('SurveyReport/save', 'SurveyReport::save');
    $routes->get('SurveyReport/edit/(:num)', 'SurveyReport::edit/$1');
    $routes->post('SurveyReport/update', 'SurveyReport::update');
    $routes->get('SurveyReport/delete/(:num)', 'SurveyReport::delete/$1');
    $routes->get('SurveyReport/view/(:num)', 'SurveyReport::view/$1');
    $routes->get('SurveyReport/pdf/(:num)', 'SurveyReport::pdf/$1');
    $routes->get('SurveyReport/foto/(:num)/(:segment)', 'SurveyReport::foto/$1/$2');
    $routes->post('SurveyReport/email', 'SurveyReport::email');
    $routes->get('SurveyReport/email-log', 'SurveyReport::emailLog');
    $routes->get('SurveyReport/email-log/body/(:num)', 'SurveyReport::emailLogBody/$1');
    $routes->get('SurveyReport/email-preview/(:num)', 'SurveyReport::emailPreview/$1');

    // Installation Report: tampilan & fitur sama dengan Survey Report (form, View, PDF terkunci, kirim email + riwayat);
    // struktur dari Installation Report Form Yesnet (1).docx
    $routes->get('InstallationReport', 'InstallationReport::index');
    $routes->get('InstallationReport/create', 'InstallationReport::create');
    $routes->post('InstallationReport/save', 'InstallationReport::save');
    $routes->get('InstallationReport/edit/(:num)', 'InstallationReport::edit/$1');
    $routes->post('InstallationReport/update', 'InstallationReport::update');
    $routes->get('InstallationReport/delete/(:num)', 'InstallationReport::delete/$1');
    $routes->get('InstallationReport/view/(:num)', 'InstallationReport::view/$1');
    $routes->get('InstallationReport/pdf/(:num)', 'InstallationReport::pdf/$1');
    $routes->get('InstallationReport/foto/(:num)/(:segment)', 'InstallationReport::foto/$1/$2');
    $routes->post('InstallationReport/email', 'InstallationReport::email');
    $routes->get('InstallationReport/email-log', 'InstallationReport::emailLog');
    $routes->get('InstallationReport/email-log/body/(:num)', 'InstallationReport::emailLogBody/$1');
    $routes->get('InstallationReport/email-preview/(:num)', 'InstallationReport::emailPreview/$1');

    // Troubleshoot Report: tampilan & fitur sama dengan Survey Report (form, View, PDF terkunci, kirim email + riwayat);
    // struktur dari Troubleshooting Report Form Yesnet (1).docx
    $routes->get('TroubleshootReport', 'TroubleshootReport::index');
    $routes->get('TroubleshootReport/create', 'TroubleshootReport::create');
    $routes->post('TroubleshootReport/save', 'TroubleshootReport::save');
    $routes->get('TroubleshootReport/edit/(:num)', 'TroubleshootReport::edit/$1');
    $routes->post('TroubleshootReport/update', 'TroubleshootReport::update');
    $routes->get('TroubleshootReport/delete/(:num)', 'TroubleshootReport::delete/$1');
    $routes->get('TroubleshootReport/view/(:num)', 'TroubleshootReport::view/$1');
    $routes->get('TroubleshootReport/pdf/(:num)', 'TroubleshootReport::pdf/$1');
    $routes->get('TroubleshootReport/foto/(:num)/(:segment)', 'TroubleshootReport::foto/$1/$2');
    $routes->post('TroubleshootReport/email', 'TroubleshootReport::email');
    $routes->get('TroubleshootReport/email-log', 'TroubleshootReport::emailLog');
    $routes->get('TroubleshootReport/email-log/body/(:num)', 'TroubleshootReport::emailLogBody/$1');
    $routes->get('TroubleshootReport/email-preview/(:num)', 'TroubleshootReport::emailPreview/$1');

    // Preventive Maintenance: tampilan & fitur sama dengan Survey Report (form, View, PDF terkunci, kirim email + riwayat);
    // struktur dari Maintenance Report Form Yesnet.docx
    $routes->get('PreventiveMaintenance', 'PreventiveMaintenance::index');
    $routes->get('PreventiveMaintenance/create', 'PreventiveMaintenance::create');
    $routes->post('PreventiveMaintenance/save', 'PreventiveMaintenance::save');
    $routes->get('PreventiveMaintenance/edit/(:num)', 'PreventiveMaintenance::edit/$1');
    $routes->post('PreventiveMaintenance/update', 'PreventiveMaintenance::update');
    $routes->get('PreventiveMaintenance/delete/(:num)', 'PreventiveMaintenance::delete/$1');
    $routes->get('PreventiveMaintenance/view/(:num)', 'PreventiveMaintenance::view/$1');
    $routes->get('PreventiveMaintenance/pdf/(:num)', 'PreventiveMaintenance::pdf/$1');
    $routes->get('PreventiveMaintenance/foto/(:num)/(:segment)', 'PreventiveMaintenance::foto/$1/$2');
    $routes->post('PreventiveMaintenance/email', 'PreventiveMaintenance::email');
    $routes->get('PreventiveMaintenance/email-log', 'PreventiveMaintenance::emailLog');
    $routes->get('PreventiveMaintenance/email-log/body/(:num)', 'PreventiveMaintenance::emailLogBody/$1');
    $routes->get('PreventiveMaintenance/email-preview/(:num)', 'PreventiveMaintenance::emailPreview/$1');

    // Menu Activation (SPK, BAST, PKS & Contract) yang belum dibuat: tampil halaman "Segera Hadir"
    $routes->get('Spk', 'Spk::index');
    $routes->post('Spk/upload', 'Spk::upload');
    $routes->get('Spk/lihat/(:num)', 'Spk::lihat/$1');
    $routes->get('Spk/download/(:num)', 'Spk::download/$1');
    $routes->get('Spk/delete/(:num)', 'Spk::delete/$1');
    $routes->get('BastCorporate', 'BastCorporate::index');
    $routes->post('BastCorporate/upload', 'BastCorporate::upload');
    $routes->get('BastCorporate/lihat/(:num)', 'BastCorporate::lihat/$1');
    $routes->get('BastCorporate/download/(:num)', 'BastCorporate::download/$1');
    $routes->get('BastCorporate/delete/(:num)', 'BastCorporate::delete/$1');
    $routes->get('BastAlfamidi', 'BastAlfamidi::index');
    $routes->post('BastAlfamidi/upload', 'BastAlfamidi::upload');
    $routes->get('BastAlfamidi/lihat/(:num)', 'BastAlfamidi::lihat/$1');
    $routes->get('BastAlfamidi/download/(:num)', 'BastAlfamidi::download/$1');
    $routes->get('BastAlfamidi/delete/(:num)', 'BastAlfamidi::delete/$1');
    $routes->get('BastLawson', 'BastLawson::index');
    $routes->post('BastLawson/upload', 'BastLawson::upload');
    $routes->get('BastLawson/lihat/(:num)', 'BastLawson::lihat/$1');
    $routes->get('BastLawson/download/(:num)', 'BastLawson::download/$1');
    $routes->get('BastLawson/delete/(:num)', 'BastLawson::delete/$1');
    $routes->get('BastAlfamart', 'BastAlfamart::index');
    $routes->post('BastAlfamart/upload', 'BastAlfamart::upload');
    $routes->get('BastAlfamart/lihat/(:num)', 'BastAlfamart::lihat/$1');
    $routes->get('BastAlfamart/download/(:num)', 'BastAlfamart::download/$1');
    $routes->get('BastAlfamart/delete/(:num)', 'BastAlfamart::delete/$1');
    $routes->get('BastDandan', 'BastDandan::index');
    $routes->post('BastDandan/upload', 'BastDandan::upload');
    $routes->get('BastDandan/lihat/(:num)', 'BastDandan::lihat/$1');
    $routes->get('BastDandan/download/(:num)', 'BastDandan::download/$1');
    $routes->get('BastDandan/delete/(:num)', 'BastDandan::delete/$1');
    $routes->get('PksContract', 'PksContract::index');
    $routes->post('PksContract/upload', 'PksContract::upload');
    $routes->get('PksContract/lihat/(:num)', 'PksContract::lihat/$1');
    $routes->get('PksContract/download/(:num)', 'PksContract::download/$1');
    $routes->get('PksContract/delete/(:num)', 'PksContract::delete/$1');

    // Data VLAN (menu All NOC di sidebar): VLAN + mapping perangkat, form berupa pop up
    $routes->get('Vlan', 'Vlan::index');
    $routes->post('Vlan/save', 'Vlan::save');
    $routes->post('Vlan/update', 'Vlan::update');
    $routes->get('Vlan/delete/(:num)', 'Vlan::delete/$1');

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

    $routes->get('DataCctv', 'DataCctv::index');
    $routes->post('DataCctv/saveNvr', 'DataCctv::saveNvr');
    $routes->post('DataCctv/updateNvr', 'DataCctv::updateNvr');
    $routes->get('DataCctv/deleteNvr/(:num)', 'DataCctv::deleteNvr/$1');

    $routes->post('DataCctv/saveCctv', 'DataCctv::saveCctv');
    $routes->post('DataCctv/updateCctv', 'DataCctv::updateCctv');
    $routes->get('DataCctv/deleteCctv/(:num)', 'DataCctv::deleteCctv/$1');
});
