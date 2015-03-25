<?php

class modulosController extends crudController {

	public function __construct() {
		Crud::setExport(true);
		Crud::setTitulo('M&oacute;dulos');
		Crud::setTablaId('moduloid');
		Crud::setTabla('authmodulos');
		Crud::setTemplate(Config::get('components::config.template','template.template'));
		
		Crud::setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));

		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'Nombre Usuario','campo'=>'nombrefriendly','tipo'=>'string'));
	}
}