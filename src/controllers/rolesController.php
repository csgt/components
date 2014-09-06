<?php

class rolesController extends BaseController {

	private $crud, $cancerbero;

	public function __construct() {
		$this->cancerbero = new Cancerbero;
		$this->crud       = new Crud;

		$this->crud->setExport(true);
		$this->crud->setTitulo('Roles');
		$this->crud->setTablaId('rolid');
		$this->crud->setTabla('authroles');
		
		$this->crud->setCampo(array('nombre'=>'Nombre','campo'=>'nombre','tipo'=>'string'));
		$this->crud->setCampo(array('nombre'=>'Descripci&oacute;n','campo'=>'descripcion','tipo'=>'string'));

		if(!$this->cancerbero->isGod()) {
			$this->crud->setPermisos(array('add'=>true, 'edit'=>true,'delete'=>true));
			$this->crud->setWhere('rolid', '<>', $this->cancerbero->getGodRol());
		}
		else
			$this->crud->setPermisos($this->cancerbero->tienePermisosCrud('catalogo'));

		$this->crud->setBotonExtra(array('url'=>'cancerbero/asignar', 'class'=>'warning', 'icon'=>'glyphicon glyphicon-lock', 'titulo'=>'Asignar Permisos'));
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