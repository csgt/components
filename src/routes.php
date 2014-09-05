<?php
	Route::group(array('before' => array('auth','cancerbero')), function() {
		Route::resource('usuarios', 'usuariosController');
		Route::resource('roles', 'rolesController');
		Route::resource('cancerbero/modulos', 'modulosController');
		Route::resource('cancerbero/permisos', 'permisosController');
	});