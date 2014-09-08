<?php

class modulosController extends BaseController {

	private $crud, $cancerbero;

	public function __construct() {
		$this->cancerbero = new Cancerbero;
		$this->crud       = new Crud;

		$this->crud->setExport(true);
		$this->crud->setTitulo('M&oacute;dulos');
		$this->crud->setTablaId('moduloid');
		$this->crud->setTabla('authmodulos');
		
		$this->crud->setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));

		$this->crud->setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Nombre Usuario','campo'=>'nombrefriendly','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Descripci&oacute;n','campo'=>'descripcion','tipo'=>'string'));

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