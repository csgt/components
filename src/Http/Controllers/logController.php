<?php
namespace Csgt\Components\Http\Controllers;

class logController extends crudController {

	public function __construct() {
		Crud::setExport(true);
		Crud::setTitulo('Bitácora de acceso');
		Crud::setTablaId('accesoid');
		Crud::setTabla('logacceso');
		Crud::setLeftJoin('authusuarios AS u','logacceso.usuarioid','=','u.usuarioid');
		Crud::setTemplate(Config::get('components::config.template','template.template'));
		Crud::setWhere('u.rolid','<>',Cancerbero::getGodRol());

		Crud::setOrderBy(array('columna'=>0,'direccion'=>'desc'));
		
		Crud::setCampo(array('nombre'=>'Fecha','campo'=>'logacceso.fechalogin','tipo'=>'datetime'));
		Crud::setCampo(array('nombre'=>'Nombre','campo'=>'u.nombre','tipo'=>'string'));
		Crud::setCampo(array('nombre'=>'IP','campo'=>'logacceso.ip','tipo'=>'string'));
	}
}