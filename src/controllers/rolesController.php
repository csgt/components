<?php

class rolesController extends crudController {

	public function __construct() {
		Crud::setExport(true);
		Crud::setTitulo('Roles');
		Crud::setTablaId('rolid');
		Crud::setTabla('authroles');
		Crud::setTemplate(Config::get('components::config.template','template.template'));
		
		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'Descripci&oacute;n','campo'=>'descripcion','tipo'=>'string'));

		if(!Cancerbero::isGod()) {
			Crud::setPermisos(Cancerbero::tienePermisosCrud('roles'));
			Crud::setWhere('rolid', '<>', Cancerbero::getGodRol());
		}
		else
			Crud::setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));
			
		Crud::setBotonExtra(array('url'=>'cancerbero/asignar/{id}', 'class'=>'warning', 'icon'=>'glyphicon glyphicon-lock', 'titulo'=>'Asignar Permisos'));
	}
}