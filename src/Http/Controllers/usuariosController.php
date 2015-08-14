<?php
namespace Csgt\Components\Http\Controllers;

use \Csgt\Components\Authusuario;
use Crud, Cancerbero, Crypt, DB, Input, Session, Hash, Redirect, Auth;
class usuariosController extends crudController {

	public function __construct() {

		Crud::setExport(true);
		Crud::setTitulo('Usuarios');
		Crud::setTablaId('usuarioid');
		Crud::setTabla('authusuarios');
		Crud::setTemplate(config('csgtcomponents.config.template','template.template'));

		if(config('csgtcomponents::usuariossoftdelete'))
			Crud::setSoftDelete(true);
		
		Crud::setLeftJoin('authroles AS r', 'authusuarios.rolid', '=', 'r.rolid');

		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'authusuarios.nombre'));
		Crud::setCampo(array('nombre'=>'Email','campo'=>'authusuarios.email'));
		Crud::setCampo(array('nombre'=>'Rol','campo'=>'r.nombre'));
		Crud::setCampo(array('nombre'=>'Creado','campo'=>'authusuarios.created_at', 'tipo'=>'datetime'));
		Crud::setCampo(array('nombre'=>'Activo','campo'=>'authusuarios.activo','tipo'=>'bool'));

		if(!Cancerbero::isGod()) {
			Crud::setPermisos(Cancerbero::tienePermisosCrud('usuarios'));
			Crud::setWhere('authusuarios.rolid', '<>', Cancerbero::getGodRol());
		}
		else
			Crud::setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));
	}

	public function edit($id) {
		$data = DB::table('authusuarios')
			->where('usuarioid', Crypt::decrypt($id))
			->first();

		$roles = DB::table('authroles')
			->select('nombre','rolid')
			->orderBy('nombre')
			->where('rolid','<>',config('csgtcancerbero.rolbackdoor'));
		if ($data) {
			$roles = $roles->orWhere('rolid', $data->rolid);
		}

		$roles = $roles->get();

		$usuarioroles = array();
		if(config('csgtcancerbero.multiplesroles')) {
			$usuarioroles = DB::table('authusuarioroles')
				->where('usuarioid', Auth::id())
				->lists('rolid');
		}

		return view('csgtcomponents::usuarioEdit')
			->with('template', config('csgtcomponents.config.template','template.template'))
			->with('roles', $roles)
			->with('data', $data)
			->with('uroles', $usuarioroles)
			->with('id', $id);
	}

	public function create() {
    return self::edit(Crypt::encrypt(0));
	}

	public function store() {
		$id = Input::get('id');

		$pass = Input::get('password');
		if ($id=='')
			$usuario = new Authusuario;
		else
			$usuario = Authusuario::find(Crypt::decrypt($id));

		$usuario->nombre = Input::get('nombre');
		$usuario->email = Input::get('email');

		if ($pass<>'')
			$usuario->password = Hash::make(Input::get('password'));
		$usuario->activo = (Input::has('activo')?1:0);

		if(!config('csgtcancerbero.multiplesroles')){
			$usuario->rolid  = Crypt::decrypt(Input::get('rolid'));
			$usuario->save();
		}

		else{
			$roles = Input::get('rolid');

			$usuario->rolid  = Crypt::decrypt($roles[0]);
			$usuario->save();

			foreach($roles as $rol) {
				DB::table('authusuarioroles')
					->insert(array('rolid'=>Crypt::decrypt($rol),'usuarioid'=>$usuario->usuarioid));
			}
		}		
		
		return Redirect::route('usuarios.index');
	}

	public function destroy($aId) {

		try{
			if (Crud::getSoftDelete()){
				$query = DB::table('authusuarios')
					->where('usuarioid', Crypt::decrypt($aId))
					->update(array('deleted_at'=>date_create(), config('csgtlogin.password.campo') =>''));
			}
			else
				$query = DB::table('authusuarios')
					->where('usuarioid', Crypt::decrypt($aId))
					->delete();

			Session::flash('message', 'Registro borrado exitosamente');
			Session::flash('type', 'warning');

		} catch (\Exception $e) {
			Session::flash('message', 'Error al borrar campo. Revisar datos relacionados.');
			Session::flash('type', 'danger');
		}

		return Redirect::to('/usuarios');
	}

}