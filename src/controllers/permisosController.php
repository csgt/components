<?php

class permisosController extends BaseController {

	private $crud, $cancerbero;

	public function __construct() {
		$this->cancerbero = new Cancerbero;
		$this->crud       = new Crud;

		$this->crud->setExport(true);
		$this->crud->setTitulo('Permisos');
		$this->crud->setTablaId('permisoid');
		$this->crud->setTabla('authpermisos');
		
		$this->crud->setPermisos($this->cancerbero->tienePermisosCrud('catalogo'));

		$this->crud->setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Nombre Usuario','campo'=>'nombrefriendly','tipo'=>'string'));
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