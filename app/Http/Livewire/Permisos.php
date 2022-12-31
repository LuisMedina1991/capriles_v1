<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;    //trait para los permisos
use Spatie\Permission\Models\Role;  //trait para los permisos
use Livewire\WithPagination;    //trait para la paginacion
use App\Models\User;
use Illuminate\Support\Facades\DB;  //helper

class Permisos extends Component
{
    use WithPagination; //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $permissionName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 10;   //paginacion

    public function paginationView(){   //metodo para la paginacion personalizada

        return 'vendor.livewire.bootstrap'; //archivo de la paginacion

    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente

        $this->pageTitle = 'Listado';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'Permisos';  //inicializar propiedades o informacion que se va renderizar en la vista principal del componente

    }

    public function render()
    {
        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen
            $permisos = Permission::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);  //busqueda a traves del nombre y guardado en variable
        else
            $permisos = Permission::orderBy('name', 'asc')->paginate($this->pagination);    //caso contrario listado por nombre y guardado en variable

        //retorna la vista con la informacion almacenada en variable
        return view('livewire.permiso.permisos', [
            'permisos' => $permisos     //enviar en variable la coleccion de datos almacenadas anteriormente
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');       //contenido del componente que se renderiza en esta seccion
    }

    public function CreatePermission(){     //metodo para almacenar nuevos registros

        $rules = ['permissionName' => 'required|min:2|unique:permissions,name'];    //reglas de validacion para cada campo en especifico

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'permissionName.required' => 'El nombre del permiso es requerido',
            'permissionName.unique' => 'El permiso ya existe',
            'permissionName.min' => 'El nombre del permiso debe contener al menos 2 caracteres'
        ];

        $this->validate($rules, $messages);     //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        Permission::create(['name' => $this->permissionName]);  //registro del elemento

        $this->emit('item-added', 'Se registro el permiso');    //evento a ser escuchado desde el frontend
        $this->resetUI();   //limpiar la informacion de los campos del formulario

    }

    public function Edit(Permission $permiso){  //metodo para abrir modal edit pasandole el id del elemento seleccionado

        //llenado de los campos con los datos almacenados en variable
        $this->selected_id = $permiso->id;
        $this->permissionName = $permiso->name;
        $this->emit('show-modal', 'Mostrar modal');     //evento a ser escuchado desde el frontend

    }

    public function UpdatePermission(){     //metodo para actualizar registro

        //reglas de validacion para cada campo en especifico
        //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente
        $rules = ['permissionName' => "required|min:2|unique:permissions,name, {$this->selected_id}"];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'permissionName.required' => 'El nombre del permiso es requerido',
            'permissionName.unique' => 'El permiso ya existe',
            'permissionName.min' => 'El nombre del permiso debe contener al menos 2 caracteres'
        ];

        $this->validate($rules, $messages);     //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $permiso = Permission::find($this->selected_id);    //seleccionar el registro del id seleccionado actualmente y guardar en variable
        $permiso->name = $this->permissionName;
        $permiso->save();   //volvemos a guardar el registro con la informacion actualizada

        $this->emit('item-updated', 'Permiso actualizado'); //evento a ser escuchado desde el frontend
        $this->resetUI();   //limpiar la informacion de los campos del formulario

    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy($id){   //metodo para eliminar registros

        $rolesCount = Permission::find($id)->getRoleNames()->count();   //buscar conteo de roles asociados con permiso y guardar en variable

        if($rolesCount > 0){    //validar si el permiso seleccionado tiene roles asociados
            $this->emit('permiso-error', 'No se puede eliminar debido a relacion'); //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        Permission::find($id)->delete();    //eliminar registro
        $this->emit('item-deleted', 'Se elimino el permiso');   //evento a ser escuchado desde el frontend

    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas

        $this->permissionName = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }
}
