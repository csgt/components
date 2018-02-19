<?php

namespace {{namespace}}Http\Controllers\Catalogos;

use Illuminate\Http\Request;

use Csgt\Crud\CrudController;
use Cancerbero;
use Crypt;
use DB;
use Exception;
use App\Models\Cancerbero\Role;
use App\Models\Cancerbero\ModulePermission;
use App\Models\Cancerbero\RoleModulePermission;

class RolesController extends CrudController
{
    public function __construct()
    {
        $this->setModelo(new Role);
        $this->setTitulo('Roles');

        $this->setCampo(['nombre' =>'Nombre', 'campo' => 'name']);
        $this->setCampo(['nombre' =>'Descripción', 'campo' => 'description']);

        $this->middleware(function ($request, $next) {
            if (!Cancerbero::isGod()) {
                $this->setWhere('id', '<>', Cancerbero::getGodRol());
            }
            return $next($request);
        });
        $this->setPermisos("\Cancerbero::tienePermisosCrud", 'catalogos.roles');
    }

    public function create(Request $request)
    {
        return $this->edit($request, 0);
    }

    public function edit(Request $request, $id)
    {
        if ($id !== 0) {
            try {
                $id = Crypt::decrypt($id);
            } catch (Exception $e) {
                abort(501, 'ID inválido');
            }
        }

        $roleName = 'Nuevo';

        $modulePermissions = Module::with(['module_permission.permission',
            'role_module_permission' => function($query) use ($id) {
                return $query->where('role_id', $id);
            }
        ])
        ->orderBy('name')
        ->get();

        $role = Role::find($id);

        if ($id !== 0) {
            $roleName = $role->name;
        }
        $breadcrumb = '<ol class="breadcrumb">
            <li>Catálogos</li>
            <li><a href="/catalogos/roles">Roles</a></li>
            <li class="active">' . $roleName . '</li>
        </ol>';

        return view('csgtcomponents::roles.edit')
            ->withData($role)
            ->withTitle($this->title)
            ->withBreadcrumb($breadcrumb)
            ->withTemplate($this->layout)
            ->withId(($id==0?0:Crypt::encrypt($id)))
            ->withModulePermission($modulePermissions);
    }

    public function store(Request $request)
    {
        return $this->update($request, 0);
    }

    public function update(Request $request, $id)
    {
        if ($id !== 0) {
            $rolid = Crypt::decrypt($request->id);
            $rol = Authrol::find($rolid);
            $rol->nombre      = $request->nombre;
            $rol->descripcion = $request->descripcion;
            $rol->save();
            Authrolmodulopermiso::where('rolid', $rolid)->delete();
        } else {
            $rol = new Authrol;
            $rol->nombre      = $request->nombre;
            $rol->descripcion = $request->descripcion;
            $rol->save();
            $rolid = $rol->rolid;
        }

        $modulopermisos = $request->modulopermisos;

        if ($modulopermisos) {
            foreach ($modulopermisos as $modulopermiso) {
                $authmodulopermiso = new Authrolmodulopermiso;
                $authmodulopermiso->rolid = $rolid;
                $authmodulopermiso->modulopermisoid = $modulopermiso;
                $authmodulopermiso->save();
            }
        }

        return redirect()->route('catalogos.roles.index')
            ->with('flashMessage', config('cancerbero::mensajerolmodulopermisoexitoso'))
            ->with('flashType', 'success');
    }
}
