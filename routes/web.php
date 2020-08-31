<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

// $router->get('/key', function(){
// 	return str_random(32);
// });

$router->post('/login', 'userController@login');
$router->post('/register', 'userController@register');
$router->post('/toko', 'userController@tambah_toko');
$router->get('/toko_supplied', 'userController@toko_supplied');
$router->get('/toko_belum_suplai', 'userController@toko_belum_suplai');
$router->get('/produk', 'userController@produk');
$router->post('/cek_telepon', 'userController@cek_telepon');
$router->post('/belum_bayar_suplai/{id_user}', 'userController@belum_bayar_suplai');
$router->post('/suplai_lunas/{id_user}', 'userController@suplai_lunas');
$router->post('/suplai', 'userController@suplai');
$router->post('/pemasukan', 'userController@pemasukan');

$router->post('/target/{id_user}', 'userController@target');

$router->get('/selisih_target/{id_user}', 'userController@selisih_target');

$router->post('/profile', 'userController@profile');

$router->post('/tampil_profil', 'userController@tampil_profil');

$router->post('/edit_toko', 'userController@edit_toko');

$router->post('/data_suplai/{id_user}/{id_toko}', 'userController@data_suplai');

$router->post('/produk_terlaris', 'userController@produk_terlaris');

$router->post('/jumlah_penjualan', 'userController@jumlah_penjualan');

