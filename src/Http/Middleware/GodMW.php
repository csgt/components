<?php

namespace Csgt\Components\Http\Middleware;

use Closure;
use Auth;
use Cancerbero;
use Route;

class GodMW
{
    public function handle($request, Closure $next)
    {
        $cancerbero = new Cancerbero();

        if (!$cancerbero->isGod()) {
            return view('csgtcancerbero::error')->with('mensaje', $request->error . ' (' . Route::currentRouteName() . ')');
        }
        return $next($request);
    }
}
