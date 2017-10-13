<?php
namespace Csgt\Components\Http\Middleware;

use Closure;
use Csgt\Components\Authmenu;
use Menu;
use Session;
use Auth;

class MenuMW
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (!Session::has('menu-collection')) {
                $elAuthMenu = new Authmenu;
                $elAuthMenu->getMenuForRole();
            }
            $elMenu = new Menu;
            $menuCollection = Session::get('menu-collection');
            $route = $request->route()->getName();
            $route = substr($route, 0, strrpos($route, '.')) . '.index';

            Session::put('menu-selected', $route);
            Session::put('menu', $elMenu->generarMenu($menuCollection));
        }
        return $next($request);
    }
}
