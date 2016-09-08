<?php 
namespace Csgt\Components\Http\Middleware;

use Closure, Csgt\Components\Authmenu, Menu, Cancerbero, Session;

class MenuMW {
	public function handle($request, Closure $next) {
	  if(!Session::has('menu-collection')){
			
			$elAuthMenu = new Authmenu;
			$elAuthMenu->getMenuForRole();
		}	
		$elMenu = new Menu;
		$menuCollection = Session::get('menu-collection');
		//$selected = Session::
		Session::put('menu', $elMenu->generarMenu($menuCollection));
		return $next($request);
	}
}