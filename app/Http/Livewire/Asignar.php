<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;    //trait para los permisos
use Spatie\Permission\Models\Role;  //trait para los permisos
use Livewire\WithPagination;    //trait para la paginacion
use Illuminate\Support\Facades\DB;  //helper

class Asignar extends Component
{
    use WithPagination; //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $role, $componentName, $permisosSelected = [], $old_permissions = [];
    private $pagination = 10;   //paginacion

    public function paginationView(){   //metodo para la paginacion personalizada
        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        $this->role = 'Elegir'; //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'Asignar Permisos';  //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
    }

    public function render()
    {   
        //se agrega una columna extra llamada checked con DB::raw
        $permisos = Permission::select('name', 'id', DB::raw("0 as checked"))   //obtencion de datos y guardado en variable
        ->orderBy('name', 'asc')    //ordenado por nombre de permiso
        ->paginate($this->pagination);  //llamado a la paginacion

        if($this->role != 'Elegir'){    //validar si el select tiene seleccionado un rol
            //union de permissions con roles_has_permissions
            $list = Permission::join('role_has_permissions as rp', 'rp.permission_id', 'permissions.id')    //listado de permisos y guardado en variable
            ->where('role_id', $this->role) //seleccion del rol por el id
            ->pluck('permissions.id')   //metodo pluck para obtener solo ciertos valores de la tabla
            ->toArray();   //formar un array
            $this->old_permissions = $list; //guardar en variable el listado de permisos
        }

        if($this->role != 'Elegir'){    //validar si el select tiene seleccionado un rol
            foreach($permisos as $permiso){ //iterar coleccion de permisos obtenidos anteriormente
                $role = Role::find($this->role);    //buscar rol y guardar en variable
                //metodo hasPermissionTo del paquete laravel permissions
                $tienePermiso = $role->hasPermissionTo($permiso->name); //verificar si el rol tiene algun permiso y guardar en variable
                if($tienePermiso){  //validar si se guardo algo en la variable
                    $permiso->checked = 1;  //se agrega el valor 1 a la columna checked
                }
            }
        }

        //retorna la vista con la informacion almacenada en variable
        //listado de roles y permisos
        return view('livewire.asignar.asignar', [
            'roles' => Role::orderBy('name', 'asc')->get(),
            'permisos' => $permisos
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public $listeners = [   //eventos provenientes del frontend a ser escuchados
        'revokeall' => 'RemoveAll'  //al escuchar el evento revokeall se hace llamado al metodo RemoveAll
    ];

    public function RemoveAll(){    //metodo para destickear todas las casillas de permisos

        if($this->role == 'Elegir'){    //validar si el select no tiene seleccionado un rol

            $this->emit('sync-error', 'Seleccione un rol valido');  //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        $role = Role::find($this->role);    //buscar rol y guardar en variable
        //metodo syncPermissions del paquete laravel permissions
        $role->syncPermissions([0]);    //revocar todos los permisos al rol seleccionado
        $this->emit('removeall', "Se revocaron todos los permisos al rol $role->name"); //evento a ser escuchado desde el frontend
    }

    public function SyncAll(){  //metodo para tickear todas las casillas de permisos

        if($this->role == 'Elegir'){    //validar si el select no tiene seleccionado un rol

            $this->emit('sync-error', 'Seleccione un rol valido');  //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        $role = Role::find($this->role);    //buscar rol y guardar en variable
        $permisos = Permission::pluck('id')->toArray(); //metodo pluck para obtener solo ciertos valores de la tabla y formar un array
        //metodo syncPermissions del paquete laravel permissions
        $role->syncPermissions($permisos);  //asignar todos los permisos al rol seleccionado
        $this->emit('syncall', "Se sincronizaron todos los permisos al rol $role->name");   //evento a ser escuchado desde el frontend
    }

    public function SyncPermiso($state, $permisoName){  //metodo para asignar permiso al clickear

        if($this->role != 'Elegir'){    //validar si el select tiene seleccionado un rol

            $roleName = Role::find($this->role);    //buscar rol y guardar en variable

            if($state){ //validar si el estado del checkbox no es activo

                //metodo givePermissionTo del paquete laravel permissions
                $roleName->givePermissionTo($permisoName);  //asignar permiso al rol seleccionado
                $this->emit('permi', 'Permiso asignado');   //evento a ser escuchado desde el frontend
            }else{

                //metodo revokePermissionTo del paquete laravel permissions
                $roleName->revokePermissionTo($permisoName);    //revoca permiso al rol seleccionado
                $this->emit('permi', 'Permiso revocado al rol');    //evento a ser escuchado desde el frontend
            }
        }else{
            $this->emit('permi', 'Elija un rol valido');    //evento a ser escuchado desde el frontend
        }
    }
}
