<?php

class crudController extends BaseController {

	public function index() {
		return Crud::index();
	}

	public function create() {
		return Crud::create(0);
	}

	public function store() {
		return Crud::store();
	}

	public function show($id) {
		return Crud::getData($id);
	}

	public function edit($id) {
		return Crud::create($id);
	}

	public function update($id) {
		return Crud::store($id);
	}

	public function destroy($id) {
		return Crud::destroy($id);
	}
	
}