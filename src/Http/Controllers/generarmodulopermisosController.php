<?php
namespace Csgt\Components\Http\Controllers;

use Illuminate\Routing\Controller, DB, Input, Response;

class generarmodulopermisosController extends Controller {

	public function index() {
		$modulos = DB::table('authmodulos')
			->select('nombre','moduloid')
			->orderBy('nombre')
			->get();
		$mpid   = DB::table('authmodulopermisos')
			->pluck(DB::raw('MAX(modulopermisoid)'));

		return view('csgtcomponents::generarmodulopermisos')
			->with('modulos', $modulos)
			->with('mpid', $mpid+1);
	}

	public function store() {
		$content = '';
		for($i=0;$i<7;$i++) {
			$content .= "
				[
					'modulopermisoid'	=> " . (Input::get('mpid') + $i) . ",
					'moduloid'				=> " . Input::get('moduloid') . ",
					'permisoid'				=> " . ($i+1) . "
				],";
		}

		$response = Response::make($content);
		$response->header('Content-Type','text');
		return $response;
	}
}