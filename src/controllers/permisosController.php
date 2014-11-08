<?php

class permisosController extends crudController {

	public function __construct() {
		Crud::setExport(false);
		Crud::setTitulo('Permisos');
		Crud::setTablaId('permisoid');
		Crud::setTabla('authpermisos');
		
		Crud::setPermisos(array('add'=>true,'edit'=>true,'delete'=>true));

		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'Nombre Usuario','campo'=>'nombrefriendly','tipo'=>'string'));
	}
}