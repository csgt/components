<?php
	Route::group(['middeleware' => ['auth','cancerbero'], 'namespace' => 'Csgt\Components\Http\Controllers'], function() {
		Route::resource('usuarios', 'usuariosController');
		Route::resource('roles', 'rolesController');
		Route::resource('logaccesos','logController');
	});

	Route::group(['middleware' => ['auth','god'], 'namespace' => 'Csgt\Components\Http\Controllers'], function() {
		Route::resource('cancerbero/modulos', 'modulosController');
	});