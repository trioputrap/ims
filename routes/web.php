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
Route::get('/pembayaran', 'PembayaranController@index')->name('pembayaran.index');
Route::get('/pembayaran/tambah', 'PembayaranController@create');
Route::get('/pembayaran/{pelanggan_id}', 'PembayaranController@getYear');
Route::get('/pembayaran/{pelanggan_id}/{year}/months', 'PembayaranController@getMonth');
Route::get('/pembayaran/total/{id_pelanggan}/{year}/{bulan}', 'PembayaranController@getTotalInvoice');
Route::patch('/pembayaran/update/{id}', 'PembayaranController@update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
