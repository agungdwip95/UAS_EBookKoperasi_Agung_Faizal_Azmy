<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('users','UsersController@index');

$router->group(['prefix' => 'auth'], function() use ($router) {
    $router->post('/register','AuthController@register');
    $router->post('/login','AuthController@login');
});

// Anggota
$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('/Anggota', 'AnggotaController@index');
    $router->post('/Anggota', 'AnggotaController@store');
    $router->get('/Anggota/{id}', 'AnggotaController@show');
    $router->put('/Anggota/{id}', 'AnggotaController@update'); 
    $router->delete('/Anggota/{id}', 'AnggotaController@destroy');
});

// Petugas
$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('/Petugas', 'PetugasController@index');
    $router->post('/Petugas', 'PetugasController@store');
    $router->get('/Petugas/{id}', 'PetugasController@show');
    $router->put('/Petugas/{id}', 'PetugasController@update'); 
    $router->delete('/Petugas/{id}', 'PetugasController@destroy');
});

// Simpanan
$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('/Simpanan', 'SimpananController@index');
    $router->post('/Simpanan', 'SimpananController@store');
    $router->get('/Simpanan/{id}', 'SimpananController@show');
    $router->put('/Simpanan/{id}', 'SimpananController@update'); 
    $router->delete('/Simpanan/{id}', 'SimpananController@destroy');
});

// Pinjaman
$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('/Pinjaman', 'PinjamanController@index');
    $router->post('/Pinjaman', 'PinjamanController@store');
    $router->get('/Pinjaman/{id}', 'PinjamanController@show');
    $router->put('/Pinjaman/{id}', 'PinjamanController@update'); 
    $router->delete('/Pinjaman/{id}', 'PinjamanController@destroy');
});

// Angsuran
$router->group(['middleware' => ['auth']], function ($router) {
    $router->get('/Angsuran', 'AngsuranController@index');
    $router->post('/Angsuran', 'AngsuranController@store');
    $router->get('/Angsuran/{id}', 'AngsuranController@show');
    $router->put('/Angsuran/{id}', 'AngsuranController@update'); 
    $router->delete('/Angsuran/{id}', 'AngsuranController@destroy');
});
