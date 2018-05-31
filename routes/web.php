<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index');

Route::get('/pelanggan/tambah', 'PelangganController@create');
Route::get('/pelanggan', 'PelangganController@index');
Route::get('/pembayaran', 'PembayaranController@index');

Route::get('/pembayaran/tambah', function () {
    return view('pembayaran.view');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
