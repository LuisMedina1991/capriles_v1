<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Office;
use Livewire\WithPagination;    //trait para la paginacion

class Offices extends Component
{
    use WithPagination;     //llamado a los trait

    public $name,$address,$phone,$search,$selected_id,$pageTitle,$componentName;    //propiedades publicas dentro del backend para accesar desde el frontend
    private $pagination =20;    //paginacion

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        $this->pageTitle = 'LISTADO';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'SUCURSALES';    //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
    }

    public function paginationView(){   //metodo para la paginacion personalizada
        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function render()
    {
        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen
            $data = Office::where('name', 'like', '%' . $this->search . '%')    //busqueda por nombre
            ->orWhere('address', 'like', '%' . $this->search . '%') //busqueda por direccion
            ->orWhere('phone', 'like', '%' . $this->search . '%') //busqueda por telefono
            ->paginate($this->pagination);
        else    //caso contrario devuelve el listado ordenado por id
            $data = Office::orderBy('id', 'asc')->paginate($this->pagination);

        return view('livewire.office.offices', ['offices' => $data])    //retorna la vista con la informacion almacenada en variable
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas
        
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->selected_id = '0';
        $this->search = '';
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }

    public function Store(){    //metodo para almacenar nuevos registros

        $rules = [  //reglas de validacion para cada campo en especifico
            'name' => 'required|unique:offices|min:3|max:255',
            'address' => 'max:500',
            'phone' => 'max:12',
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 255 caracteres',
            'address.max' => 'Maximo 500 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        Office::create([  //guardar en variable el registro de este elemento
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone
        ]);

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-added', 'Registrado correctamente');  //evento a ser escuchado desde el frontend
    }

    public function Edit($id){  //metodo para abrir modal edit pasandole el id del elemento seleccionado

        $record = Office::find($id, ['id','name','address','phone']); //obtener la informacion especifica del elemento seleccionado y almacenarlo en variable
        $this->name = $record->name;    //llenado del campo con los datos almacenados en variable
        $this->address = $record->address;    //llenado del campo con los datos almacenados en variable
        $this->phone = $record->phone;    //llenado del campo con los datos almacenados en variable
        $this->selected_id = $record->id;   //llenado del campo con los datos almacenados en variable
        $this->emit('show-modal', 'Mostrando modal'); //evento a ser escuchado desde el frontend
    }

    public function Update(){   //metodo para actualizar registro

        $rules = [  //reglas de validacion para cada campo en especifico
            //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente
          'name' => "required|min:3|max:255|unique:offices,name,{$this->selected_id}",
          'address' => 'max:500',
          'phone' => 'max:12',
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 255 caracteres',
            'address.max' => 'Maximo 500 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $office = Office::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable
        $office->update([     //actualizar el registro con metodo update de eloquent
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone
        ]);

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-updated', 'Actualizado correctamente');   //evento a ser escuchado desde el frontend
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy(Office $office){    //metodo para eliminar registros con una instancia del modelo que contiene el id
        
        $office->delete();    //eliminar registro con metodo delete de eloquent
        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-deleted', 'Eliminado correctamente'); //evento a ser escuchado desde el frontend
    }
}
