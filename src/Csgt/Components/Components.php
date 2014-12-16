<?php 

namespace Csgt\Components;
use DB, Auth;

class Components {	
	
	public static function getMenuForRole() {
		$usuarioroles = array();
		if(Config::get('components::multiplesroles')) {
			$usuarioroles = DB::table('authusuarioroles')
				->where('usuarioid', Auth::id())
				->lists('rolid');
		}

		else
			$usuarioroles[] = Auth::user()->rolid;

		$query = 'SELECT * FROM authmenu WHERE ruta IN (
			SELECT 
				CONCAT(IF(m.nombre<>\'index\',m.nombre,\'/\'), IF(p.nombre<>\'index\',CONCAT(\'/\',p.nombre),\'\')) AS ruta 
			FROM
				authrolmodulopermisos rmp
				LEFT JOIN authmodulopermisos mp ON (mp.modulopermisoid=rmp.modulopermisoid)
				LEFT JOIN authmodulos m ON (m.moduloid=mp.moduloid)
				LEFT JOIN authpermisos p ON (p.permisoid=mp.permisoid)
			WHERE
				rmp.rolid IN(' . $usuarioroles . ')
			)
			OR menuid IN (
			SELECT padreid FROM authmenu WHERE ruta IN (
			SELECT 
			 CONCAT(IF(m.nombre<>\'index\',m.nombre,\'/\'), IF(p.nombre<>\'index\',CONCAT(\'/\',p.nombre),\'\')) AS ruta 
			FROM
				authrolmodulopermisos rmp
				LEFT JOIN authmodulopermisos mp ON (mp.modulopermisoid=rmp.modulopermisoid)
				LEFT JOIN authmodulos m ON (m.moduloid=mp.moduloid)
				LEFT JOIN authpermisos p ON (p.permisoid=mp.permisoid)
			WHERE
				rmp.rolid IN(' . $usuarioroles . ')
			) AND padreid IS NOT NULL
			) ORDER BY padreid, orden';
		return DB::select(DB::raw($query));
	}

	public static function fechaHumanoAMysql($aFecha) {
		
		$fh = explode(' ', $aFecha);
		if (sizeof($fh)==2) 
			$laFecha = $fh[0];
		else
			$laFecha = $aFecha;

		$partes = explode('/', $laFecha);
		if (sizeof($partes)==1)
			$partes = explode('-', $laFecha);

		return $partes[2] . '-' . $partes[1] . '-' . $partes[0] . ((sizeof($fh)==2)?' ' . $fh[1]:'');
	}

	public static function fechaMysqlAHumano($aFecha) {

		$fh = explode(' ', $aFecha);
		if (sizeof($fh)==2)
			$laFecha = $fh[0];
		else
			$laFecha = $aFecha;

		$partes = explode('/', $laFecha);
		if (sizeof($partes)==1)
			$partes = explode('-', $laFecha);

		return $partes[2] . '-' . $partes[1] . '-' . $partes[0] . ((sizeof($fh)==2)?' ' . $fh[1]:'');
	}

}