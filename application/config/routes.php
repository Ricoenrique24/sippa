<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'Login';
$route['beranda'] = 'Beranda';
$route['unit'] = 'Unit';
$route['prodi'] = 'Unit';
$route['referensi'] = 'Referensi';
$route['data-master-kriteria'] = 'Master/kriteria';
$route['data-master-subkriteria'] = 'Master/subkriteria';
$route['pagu-anggaran-jurusan'] = 'Pagu_jurusan';
$route['prodi/(:any)'] = 'Prodi/index/$1';

// Jurusan Route
$route['tambah-simulasi-jurusan']           = 'Pagu_jurusan/add_simulasi_jurusan';
$route['hasil-simulasi-jurusan/(:any)']     = 'Pagu_jurusan/hasil_simulasi_jurusan/$1';
$route['edit-simulasi-jurusan/(:any)']      = 'Pagu_jurusan/edit_simulasi_jurusan/$1';
$route['export-simulasi-jurusan/(:any)']    = 'Pagu_jurusan/export_simulasi_jurusan/$1';
$route['cari-prodi/(:any)']                 = 'Pagu_jurusan/cari_prodi/$1';
$route['hitung-prodi/(:any)']               = 'Pagu_jurusan/hitung_prodi/$1';
$route['cek-data/(:any)/(:any)/(:any)']     = 'Pagu_jurusan/cek_data/$1/$2/$3';

// Non-Jurusan Route
$route['pagu-anggaran-nonjurusan'] = 'Pagu_nonjurusan';
$route['tambah-simulasi-nonjurusan'] = 'Pagu_nonjurusan/add_simulasi_jurusan';
$route['hasil-simulasi-nonjurusan/(:any)'] = 'Pagu_nonjurusan/hasil_simulasi_jurusan/$1';
$route['edit-simulasi-nonjurusan/(:any)'] = 'Pagu_nonjurusan/edit_simulasi_jurusan/$1';
$route['export-simulasi-nonjurusan/(:any)'] = 'Pagu_nonjurusan/export_simulasi_jurusan/$1';
