<?php

namespace Csgt\Components\Http\Controllers;

use Csgt\Components\Support\QueryCompatibility;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Response;

class generarmodulopermisosController extends Controller
{
    public function index()
    {
        $modulos = DB::table('authmodulos')
            ->select('nombre', 'moduloid')
            ->orderBy('nombre')
            ->get();
        $mpid = QueryCompatibility::value(
            DB::table('authmodulopermisos'),
            DB::raw('MAX(modulopermisoid)')
        );

        return view('csgtcomponents::generarmodulopermisos')
            ->with('modulos', $modulos)
            ->with('mpid', $mpid + 1);
    }

    public function store(Request $request)
    {
        $content = '';
        for ($i = 0;$i < 7;$i++) {
            $content .= "
				['modulopermisoid' => " . ($request->input('mpid') + $i) . ", 'moduloid' => " . $request->input('moduloid') . ", 'permisoid' => " . ($i + 1) . "],";
        }

        $response = Response::make($content);
        $response->header('Content-Type', 'text');
        return $response;
    }
}
