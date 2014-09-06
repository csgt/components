<?php

Route::filter('menu', function(){
	if(!Session::has('menu')){
			$elMenu    = new Menu;
			$menuItems = Authmenu::getMenuForRole();
			Session::put('menu', $elMenu->generarMenu($menuItems));
	}		
});
