<?php
	$routedata = ['namespace' => 'Csgt\Components\Http\Controllers', 'middleware'=>['auth','cancerbero','menu']];
	if(config('csgtlogin.routeextras')) {
		$routedata = array_merge($routedata, config('csgtlogin.routeextras',[]));
	}

	$routedatagod = ['namespace' => 'Csgt\Components\Http\Controllers', 'middleware'=>['auth','god','menu']];
	if(config('csgtlogin.routeextras')) {
		$routedatagod = array_merge($routedata, config('csgtlogin.routeextras',[]));
	}



	Route::group($routedata, function() {
		Route::resource('usuarios', 'usuariosController');
		Route::resource('logaccesos','logController');
	});

	Route::group($routedatagod, function() {
		Route::resource('cancerbero/generarmodulopermisos','generarmodulopermisosController',['only'=> ['index','store']] );
	});