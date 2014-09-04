<?php 

namespace Csgt\Components;
use DB, Auth;

class CSGTMenu {
	public static function getMenuForRole() {
		$arr = array();
		$padres = array();
		$permisos = DB::table('authmenu AS m')
			->leftJoin('authrolmodulopermisos AS rmp', 'rmp.modulopermisoid','=','m.modulopermisoid')
			->leftJoin('authmodulopermisos AS mp', 'mp.modulopermisoid','=','rmp.modulopermisoid')
			->leftJoin('authmodulos AS mo','mo.moduloid','=','mp.moduloid')
			->leftJoin('authpermisos AS p','p.permisoid','=','mp.permisoid')
			->select('m.nombre',DB::raw('CONCAT(mo.nombre,".",p.nombre) AS ruta'),'m.padreid','m.menuid','m.icono')
			->where('rmp.rolid', Auth::user()->rolid)
			->orderBy('m.padreid')
			->orderBy('m.orden')
			->get();

		$i=0;
		foreach($permisos as $menu) {
			$arr[$i]['nombre']  = $menu->nombre;
			$arr[$i]['ruta']    = $menu->ruta;
			$arr[$i]['icono']   = $menu->icono;
			$arr[$i]['padreid'] = (int)$menu->padreid;
			$arr[$i]['menuid']  = (int)$menu->menuid;
			$i++;
			$temp = (int)$menu->padreid;
			if ($temp<>0) $padres[] = $temp;
		}
		//dd($padres);
		//Ahora hay que buscar los padres, abuelos, etc y enviarlos tambien en el array de retorno
		$papas = DB::table('authmenu AS m')
			->leftJoin('authmodulopermisos AS mp', 'mp.modulopermisoid','=','m.modulopermisoid')
			->leftJoin('authmodulos AS mo','mo.moduloid','=','mp.moduloid')
			->leftJoin('authpermisos AS p','p.permisoid','=','mp.permisoid')
			->select('m.nombre',DB::raw('CONCAT(mo.nombre,".",p.nombre) AS ruta'),'m.padreid','m.menuid','m.icono')
			->orderBy('m.padreid')
			->orderBy('m.orden')
			->whereIn('menuid', $padres)
			->get();

		$padres = [];
		foreach($papas as $menu) {
			$arr[$i]['nombre']  = $menu->nombre;
			$arr[$i]['ruta']    = $menu->ruta;
			$arr[$i]['icono']   = $menu->icono;
			$arr[$i]['padreid'] = (int)$menu->padreid;
			$arr[$i]['menuid']  = (int)$menu->menuid;
			$i++;
			$temp = (int)$menu->padreid;
			if ($temp<>0) $padres[] = $temp;
		}
		//dd($padres);

		$abuelos = DB::table('authmenu AS m')
			->leftJoin('authmodulopermisos AS mp', 'mp.modulopermisoid','=','m.modulopermisoid')
			->leftJoin('authmodulos AS mo','mo.moduloid','=','mp.moduloid')
			->leftJoin('authpermisos AS p','p.permisoid','=','mp.permisoid')
			->select('m.nombre',DB::raw('CONCAT(mo.nombre,".",p.nombre) AS ruta'),'m.padreid','m.menuid','m.icono')
			->orderBy('m.padreid')
			->orderBy('m.orden')
			->whereIn('menuid', $padres)
			->get();

		$padres = [];
		foreach($abuelos as $menu) {
			$arr[$i]['nombre']  = $menu->nombre;
			$arr[$i]['ruta']    = $menu->ruta;
			$arr[$i]['icono']   = $menu->icono;
			$arr[$i]['padreid'] = (int)$menu->padreid;
			$arr[$i]['menuid']  = (int)$menu->menuid;
			$i++;
		}
		//dd($arr);

		return $arr;
	}

}