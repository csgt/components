<?php
	Route::group(array('before' => array('auth','cancerbero')), function() {
		Route::resource ('usuarios', 'usuariosController');
	});