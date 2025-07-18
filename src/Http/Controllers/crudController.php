<?php
namespace Csgt\Components\Http\Controllers;

use Crud;
use Crypt;
use Illuminate\Routing\Controller;

class crudController extends Controller
{

    public function index()
    {
        return Crud::index();
    }

    public function create()
    {
        if (config('csgtcrud.usar_encripcion')) {
            return Crud::create(Crypt::encrypt(0));
        }

        return Crud::create(0);
    }

    public function store()
    {
        return Crud::store();
    }

    public function show($id)
    {
        return Crud::getData($id);
    }

    public function edit($id)
    {
        return Crud::create($id);
    }

    public function update($id)
    {
        return Crud::store($id);
    }

    public function destroy($id)
    {
        return Crud::destroy($id);
    }

}
