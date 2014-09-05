<?php

class usuariosController extends BaseController {

	private $crud, $cancerbero;

	public function __construct() {
		$this->cancerbero = new Cancerbero;
		$this->crud       = new Crud;

		$this->crud->setExport(true);
		$this->crud->setTitulo('Usuarios');
		$this->crud->setTablaId('usuarioid');
		$this->crud->setTabla('authusuarios');
		
		$this->crud->setPermisos(array('add'=>true,'edit'=>true,'delete'=>true));
		$this->crud->setLeftJoin('authroles AS r', 'authusuarios.rolid', '=', 'r.rolid');

		$this->crud->setCampo(array('nombre'=>'Nombre','campo'=>'authusuarios.nombre','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Email','campo'=>'authusuarios.email','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Rol','campo'=>'r.nombre','tipo'=>'combobox','query'=>'SELECT nombre,rolid FROM authroles ORDER BY nombre','combokey'=>'rolid'));
		$this->crud->setCampo(array('nombre'=>'Creado','campo'=>'authusuarios.created_at','tipo'=>'datetime','editable'=>false));
		$this->crud->setCampo(array('nombre'=>'Activo','campo'=>'authusuarios.activo','tipo'=>'bool'));

		if(!$this->cancerbero->isGod())
			$this->crud->setWhere('authusuarios.rolid', '<>', $this->cancerbero->getGodRol());
	}

	public function index() {
		return $this->crud->index();
	}

	public function create() {
		return $this->crud->create(0);
	}

	public function store() {
		return $this->crud->store();
	}

	public function show($id) {
		return $this->crud->getData($id);
	}

	public function edit($id) {
		return $this->crud->create($id);
	}

	public function update($id) {
		return $this->crud->store($id);
	}

	public function destroy($id) {
		return $this->crud->destroy($id);
	}
}