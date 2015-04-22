<?php

class crudController extends BaseController {

	public static function index() {
		return Crud::index();
	}

	public static function create() {
		return Crud::create(0);
	}

	public static function store() {
		return Crud::store();
	}

	public static function show($id) {
		return Crud::getData($id);
	}

	public static function edit($id) {
		return Crud::create($id);
	}

	public static function update($id) {
		return Crud::store($id);
	}

	public static function destroy($id) {
		return Crud::destroy($id);
	}
	
}