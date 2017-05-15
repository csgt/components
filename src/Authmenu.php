<?php 
namespace Csgt\Components;

use Auth, DB, Session;
use App\Models\Menu\Authmenu as Menu;

class Authmenu {
	protected $padres  = array();
	protected $menuIds = array(0);

	function getPadres($aMenuId){
		if($this->padres[$aMenuId]<>0) {
			$this->menuIds[] = $this->padres[$aMenuId];
			$this->getPadres($this->padres[$aMenuId]);
		}
	}

	function getMenuForRole() {

		$usuarioroles = Auth::user()->getRoles();

		$menus = Menu::select('padreid','menuid')->get();

		//Guardamos un array de padres para solo abrir el dataset una vez
		foreach ($menus as $menu) {
		 	$this->padres[$menu->menuid] = (int)$menu->padreid;
		}

		//Buscamos todos los permisos (sin padres) y agregamos los padres
		$permisos = DB::table('authmenu AS m')
			->leftJoin('authrolmodulopermisos AS rmp', 'rmp.modulopermisoid','=','m.modulopermisoid')
			->leftJoin('authmodulopermisos AS mp', 'mp.modulopermisoid','=','rmp.modulopermisoid')
			->leftJoin('authmodulos AS mo','mo.moduloid','=','mp.moduloid')
			->leftJoin('authpermisos AS p','p.permisoid','=','mp.permisoid')
			->select('m.menuid', DB::raw('coalesce(m.padreid,0) AS padreid'))
			->whereIn('rmp.rolid', $usuarioroles)
			->get();
		foreach ($permisos as $menu) {
			$this->menuIds[] = $menu->menuid;
			if ($menu->padreid<>0) {
				$this->menuIds[] = $menu->padreid;
				$this->getPadres($menu->padreid);
			}
		}

		//Ahora que ya tenemos todos los menuids que necesitamos, hacemos de nuevo el select IN
		$arr = [];
		$permisos = DB::table('authmenu AS m')
			->leftJoin('authmodulopermisos AS mp', 'mp.modulopermisoid','=','m.modulopermisoid')
			->leftJoin('authmodulos AS mo','mo.moduloid','=','mp.moduloid')
			->leftJoin('authpermisos AS p','p.permisoid','=','mp.permisoid')
			->select('m.nombre',DB::raw("CONCAT(mo.nombre,'.',p.nombre) AS ruta"),'m.padreid','m.menuid','m.icono')
			->whereIn('m.menuid', $this->menuIds)
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
		}
		Session::put('menu-collection', collect($arr));
	}

}