<?php
	Route::group(array('before' => array('auth','cancerbero')), function() {
		Route::resource('usuarios', 'usuariosController');
		Route::resource('roles', 'rolesController');
	});

	Route::group(array('before' => array('auth','god')), function() {
		Route::resource('cancerbero/modulos', 'modulosController');
		Route::resource('cancerbero/permisos', 'permisosController');
		Route::resource('generarmodulopermisos','generarmodulopermisosController', array('only'=>array('index','store')));
	});