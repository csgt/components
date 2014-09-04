<?php 

namespace Csgt\Components;
use DB, Auth;

class Components {
	public static function getMenuForRole() {
		$query = 'SELECT * FROM authmenu WHERE ruta IN (
			SELECT 
				CONCAT(IF(m.nombre<>\'index\',m.nombre,\'/\'), IF(p.nombre<>\'index\',CONCAT(\'/\',p.nombre),\'\')) AS ruta 
			FROM
				authrolmodulopermisos rmp
				LEFT JOIN authmodulopermisos mp ON (mp.modulopermisoid=rmp.modulopermisoid)
				LEFT JOIN authmodulos m ON (m.moduloid=mp.moduloid)
				LEFT JOIN authpermisos p ON (p.permisoid=mp.permisoid)
			WHERE
				rmp.rolid=' . Auth::user()->rolid . '
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
				rmp.rolid=' . Auth::user()->rolid . '
			) AND padreid IS NOT NULL
			) ORDER BY padreid, orden';
		return DB::select(DB::raw($query));
	}

}