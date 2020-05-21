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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return View::make('layouts.master');
    });

     /* CATEGORIA */
     Route::post('categoria/buscar', 'CategoriaController@buscar')->name('categoria.buscar');
     Route::get('categoria/eliminar/{id}/{listarluego}', 'CategoriaController@eliminar')->name('categoria.eliminar');
     Route::resource('categoria', 'CategoriaController', array('except' => array('show')));
     /* UNIDAD */
    Route::post('unidad/buscar', 'UnidadController@buscar')->name('unidad.buscar');
    Route::get('unidad/eliminar/{id}/{listarluego}', 'UnidadController@eliminar')->name('unidad.eliminar');
    Route::resource('unidad', 'UnidadController', array('except' => array('show')));
    
    /* MARCA */
    Route::post('marca/buscar', 'MarcaController@buscar')->name('marca.buscar');
    Route::get('marca/eliminar/{id}/{listarluego}', 'MarcaController@eliminar')->name('marca.eliminar');
    Route::resource('marca', 'MarcaController', array('except' => array('show')));

    /* PRODUCTO */
    Route::post('producto/buscar', 'ProductoController@buscar')->name('producto.buscar');
    Route::get('producto/eliminar/{id}/{listarluego}', 'ProductoController@eliminar')->name('producto.eliminar');
    Route::post('producto/buscarproducto', 'ProductoController@buscarproducto')->name('producto.buscarproducto');
    Route::resource('producto', 'ProductoController', array('except' => array('show')));
    Route::get('producto/excel', 'ProductoController@excel')->name('producto.excel');
    Route::get('producto/presentacion/{id}/{listarluego}', 'ProductoController@presentacion')->name('producto.presentacion');
    Route::post('producto/presentaciones', 'ProductoController@presentaciones')->name('producto.presentaciones');
    Route::post('producto/archivos','ProductoController@archivos')->name('producto.archivos');

});

