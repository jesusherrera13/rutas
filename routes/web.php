<?php

use Illuminate\Support\Facades\Route;

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

// Route::resource('/', UserController::class);
Route::get('/', 'UserController@dashboard');

Route::get('login', function () {

    return view('login');
});

Route::get('logout', 'LoginController@logout');

// Route::resource('dashboard', UsuarioController::class);
// Route::resource('dashboard', UserController::class);
Route::get('dashboard', 'UserController@dashboard');

Route::get('lockscreen', 'LoginController@lockscreen');

/*
Route::get('lockscreen', function () {

    return view('lockscreen');
});
*/

Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Route::post('login/store', 'LoginController@store');
Route::post('login/unlock', 'LoginController@unlock');
Route::post('sidebar', 'LoginController@sidebar');

// FEDERAL
Route::resource('distritos-federales', 'DistritoFederalController');
Route::post('distritos-federales', 'DistritoFederalController@getData');
Route::post('distrito-federal/store', 'DistritoFederalController@store');
Route::post('distrito-federal/update', 'DistritoFederalController@update');
// FEDERAL

// LOCAL
Route::resource('distritos-locales', 'DistritoLocalController');
Route::post('distritos-locales', 'DistritoLocalController@getData');
Route::post('distrito-local/store', 'DistritoLocalController@store');
Route::post('distrito-local/update', 'DistritoLocalController@update');
// LOCAL

// DISTRITOS_LIGUES
Route::post('distritos-ligues', 'DistritoLigueFederalController@getData');
// DISTRITOS_LIGUES

// LOCAL
Route::resource('secciones', 'SeccionController');
Route::get('secciones-data', 'SeccionController@getData');
Route::post('secciones', 'SeccionController@getData');
Route::post('seccion/store', 'SeccionController@store');
Route::post('seccion/update', 'SeccionController@update');
// LOCAL

// USUARIO
Route::resource('usuarios', 'UserController');
Route::post('usuarios', 'UserController@getData');
Route::post('usuario/store', 'UserController@store');
Route::post('usuario/update', 'UserController@update');
// USUARIO

// CONTACTO
Route::resource('contactos', 'ContactoController');
Route::get('referentes', 'ContactoController@getData');
Route::get('contactos-data', 'ContactoController@getData');
Route::post('contactos', 'ContactoController@getData');
Route::post('contacto/store', 'ContactoController@store');
Route::post('contacto/update', 'ContactoController@update');
Route::post('contacto/importar', 'ContactoController@importar');
Route::get('contactos-ssp', 'ContactoController@serverSideProcessing');
// CONTACTO

// ASENTAMIENTO
Route::resource('asentamientos', 'AsentamientoController');
Route::get('asentamientos', 'AsentamientoController@getData');
Route::post('asentamientos', 'AsentamientoController@getData');
Route::post('asentamiento/store', 'AsentamientoController@store');
Route::post('asentamiento/update', 'AsentamientoController@update');
// ASENTAMIENTO

// CASILLA
Route::resource('casillas', 'CasillaController');
Route::get('casillas-data', 'CasillaController@getData');
Route::post('casillas', 'CasillaController@getData');
Route::post('casilla/store', 'CasillaController@store');
Route::post('casilla/update', 'CasillaController@update');
Route::post('casilla/guardar', 'CasillaController@guardar');
Route::post('casilla/borrar', 'CasillaController@borrar');
// CASILLA

// REPRESENTANTE
Route::resource('representantes', 'CasillaRepresentanteController');
Route::get('representates-data', 'CasillaRepresentanteController@getData');
Route::post('representantes', 'CasillaRepresentanteController@getData');
Route::post('representante/store', 'CasillaRepresentanteController@store');
Route::post('representante/update', 'CasillaRepresentanteController@update');
Route::post('representante/guardar', 'CasillaRepresentanteController@guardar');
// REPRESENTANTE

// RUTA
Route::resource('rutas', 'RutaController');
Route::get('rutas-data', 'RutaController@getData');
Route::get('ruta-impresion/{id}', 'RutaController@impresion');
Route::post('rutas', 'RutaController@getData');
Route::post('ruta/store', 'RutaController@store');
Route::post('ruta/update', 'RutaController@update');
Route::post('ruta/guardar', 'RutaController@guardar');
// RUTA

// RUTA CASILLA
Route::resource('rutas-casillas', 'RutaCasillaController');
Route::get('rutas-casillas-data', 'RutaCasillaController@getData');
Route::post('rutas-casillas', 'RutaCasillaController@getData');
Route::post('ruta-casilla/store', 'RutaCasillaController@store');
Route::post('ruta-casilla/update', 'RutaCasillaController@update');
Route::post('ruta-casilla/guardar', 'RutaCasillaController@guardar');
Route::post('ruta-casilla/borrar', 'RutaCasillaController@borrar');
// RUTA CASILLA

// COORDINADOR
Route::resource('coordinadores', 'CoordinadorController');
// Route::get('referentes', 'CoordinadorController@getData');
Route::get('coordinadores-data', 'CoordinadorController@getData');
Route::post('coordinadores', 'CoordinadorController@getData');
Route::post('coordinadores-contactos', 'CoordinadorController@coordinadores');
Route::post('coordinador/store', 'CoordinadorController@store');
Route::post('coordinador/update', 'CoordinadorController@update');
Route::post('coordinador/guardar', 'CoordinadorController@guardar');
// COORDINADOR

// REFERENTE
Route::resource('referentes', 'ReferenteController');
// Route::get('referentes', 'ReferenteController@getData');
Route::get('referentes-data', 'ReferenteController@getData');
Route::post('referentes', 'ReferenteController@getData');
Route::post('referentes-contactos', 'ReferenteController@referentes');
Route::post('referente/store', 'ReferenteController@store');
Route::post('referente/update', 'ReferenteController@update');
Route::post('referente/guardar', 'ReferenteController@guardar');
// REFERENTE

// MODULO
Route::resource('modulos', 'ModuloController');
Route::post('modulos', 'ModuloController@getData');
Route::post('modulo/store', 'ModuloController@store');
Route::post('modulo/update', 'ModuloController@update');
// MODULO

// REPRESENTANTE_TIPO
Route::resource('representantes-tipos', 'RepresentanteTipoController');
Route::post('representantes-tipos', 'RepresentanteTipoController@getData');
Route::post('representante/store', 'RepresentanteTipoController@store');
Route::post('representante/update', 'RepresentanteTipoController@update');
// REPRESENTANTE_TIPO

Route::get('generate-pdf', 'PDFController@generatePDF');