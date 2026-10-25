<?php

namespace Csgt\Components\Http\Middleware;

use Closure;
use Csgt\Components\Authmenu;
use Menu;
use Cancerbero;
use Session;

class MenuMW
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('menu')) {
            $elMenu     = new Menu();
            $elAuthMenu = new Authmenu();
            $menuItems  = $elAuthMenu->getMenuForRole();
            Session::put('menu', $elMenu->generarMenu($menuItems));
        }
        return $next($request);
    }
}
