<?php 
namespace Csgt\Components\Http\Middleware;

use Closure, Csgt\Components\Authmenu, Menu, Cancerbero, Session, Log;

class MenuMW {
	public function handle($request, Closure $next) {
	  if(!Session::has('menu-collection')){
			$elAuthMenu = new Authmenu;
			$elAuthMenu->getMenuForRole();
		}	
		$elMenu = new Menu;
		$menuCollection = Session::get('menu-collection');
		$route = $request->route()->getName();
		$route = substr($route,0,strrpos($route,'.')) . '.index';
		
		Session::put('menu-selected', $route);
		Session::put('menu', $elMenu->generarMenu($menuCollection));
		return $next($request);
	}
}