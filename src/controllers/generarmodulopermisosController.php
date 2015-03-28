<?php

class generarmodulopermisosController extends BaseController {

	public function index() {
		$modulos = DB::table('authmodulos')
			->select('nombre','moduloid')
			->orderBy('nombre')
			->get();
		$mpid   = DB::table('authmodulopermisos')
			->pluck(DB::raw('MAX(modulopermisoid)'));

		return View::make('components::generarmodulopermisos')
			->with('modulos', $modulos)
			->with('mpid', $mpid+1);
	}

	public function store() {
		$content = '';
		for($i=0;$i<7;$i++) {
			$content .= "
			DB::table('authmodulopermisos')->insert(array(
				'modulopermisoid'	=> " . (Input::get('mpid') + $i) . ",
				'moduloid'				=> " . Input::get('moduloid') . ",
				'permisoid'				=> " . ($i+1) . "
			));";
		}

		$response = Response::make($content);
		$response->header('Content-Type','text');
		return $response;
	}
}