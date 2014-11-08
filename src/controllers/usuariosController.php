<?php

class usuariosController extends crudController {

	public function __construct() {

		Crud::setExport(true);
		Crud::setTitulo('Usuarios');
		Crud::setTablaId('usuarioid');
		Crud::setTabla('authusuarios');
		
		Crud::setLeftJoin('authroles AS r', 'authusuarios.rolid', '=', 'r.rolid');

		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'authusuarios.nombre','reglas' => array('notEmpty'), 
			'reglasmensaje'=>'El nombre es requerido', 'tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'Email','campo'=>'authusuarios.email', 'reglas' => array('notEmpty','emailAddress'), 
			'reglasmensaje'=>'Formato de email inv&aacute;lido', 'tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'Rol','campo'=>'r.nombre','tipo'=>'combobox',
				'query'=>'SELECT nombre,rolid FROM authroles ORDER BY nombre','combokey'=>'rolid'));
		Crud::setCampo(array('nombre'=>'Creado','campo'=>'authusuarios.created_at','tipo'=>'datetime','editable'=>false));
		Crud::setCampo(array('nombre'=>'Activo','campo'=>'authusuarios.activo','tipo'=>'bool'));
		Crud::setCampo(array('nombre'=>'Password','campo'=>'authusuarios.password','tipo'=>'password','show'=>false));

		if(!Cancerbero::isGod()) {
			Crud::setPermisos(Cancerbero::tienePermisosCrud('usuarios'));
			Crud::setWhere('authusuarios.rolid', '<>', Cancerbero::getGodRol());
		}
		else
			Crud::setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));
	}
}