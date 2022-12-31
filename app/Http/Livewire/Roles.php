<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;    //trait para los permisos
use Spatie\Permission\Models\Role;  //trait para los permisos
use Livewire\WithPagination;    //trait para la paginacion
use App\Models\User;
use Illuminate\Support\Facades\DB;  //helper

class Roles extends Component
{
    use WithPagination; //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $roleName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 5;    //paginacion

    public function paginationView(){   //metodo para la paginacion personalizada

        return 'vendor.livewire.bootstrap'; //archivo de la paginacion

    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente

        $this->pageTitle = 'Listado';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'Roles'; //inicializar propiedades o informacion que se va renderizar en la vista principal del componente

    }

    public function render()
    {
        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen
            $roles = Role::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);   //busqueda a traves del nombre y guardado en variable
        else
            $roles = Role::orderBy('name', 'asc')->paginate($this->pagination); //caso contrario listado por nombre y guardado en variable

        //retorna la vista con la informacion almacenada en variable
        return view('livewire.role.roles', [   
            'roles' => $roles   //enviar en variable la coleccion de datos almacenadas anteriormente
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function CreateRole(){   //metodo para almacenar nuevos registros

        $rules = ['roleName' => 'required|min:2|unique:roles,name'];    //reglas de validacion para cada campo en especifico

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'roleName.required' => 'El nombre del rol es requerido',
            'roleName.unique' => 'El rol ya existe',
            'roleName.min' => 'El nombre del rol debe contener al menos 2 caracteres'
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        Role::create(['name' => $this->roleName]);  //registro del elemento

        $this->emit('item-added', 'Se registro el rol');    //evento a ser escuchado desde el frontend
        $this->resetUI();   //limpiar la informacion de los campos del formulario

    }

    public function Edit(Role $role){   //metodo para abrir modal edit pasandole el id del elemento seleccionado

        //llenado de los campos con los datos almacenados en variable
        $this->selected_id = $role->id;
        $this->roleName = $role->name;
        $this->emit('show-modal', 'Mostrar modal'); //evento a ser escuchado desde el frontend

    }

    public function UpdateRole(){   //metodo para actualizar registro

        //reglas de validacion para cada campo en especifico
        //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente
        $rules = ['roleName' => "required|min:2|unique:roles,name, {$this->selected_id}"];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'roleName.required' => 'El nombre del rol es requerido',
            'roleName.unique' => 'El rol ya existe',
            'roleName.min' => 'El nombre del rol debe contener al menos 2 caracteres'
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $role = Role::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable
        $role->name = $this->roleName;
        $role->save();  //volvemos a guardar el registro con la informacion actualizada

        $this->emit('item-updated', 'Rol actualizado'); //evento a ser escuchado desde el frontend
        $this->resetUI();   //limpiar la informacion de los campos del formulario

    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy($id){   //metodo para eliminar registros

        $permissionsCount = Role::find($id)->permissions->count();  //buscar conteo de permisos asociados con rol y guardar en variable

        if($permissionsCount > 0){  //validar si el rol seleccionado tiene permisos asociados
            $this->emit('role-error', 'No se puede eliminar debido a relacion');    //evento a ser escuchado desde el frontend
            return;     //detener el flujo del proceso
        }

        Role::find($id)->delete();  //eliminar registro
        $this->emit('item-deleted', 'Se elimino el rol');   //evento a ser escuchado desde el frontend

    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas

        $this->roleName = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }

}
