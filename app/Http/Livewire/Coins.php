<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
use Livewire\Component;
use Livewire\WithFileUploads;   //trait para subir imagenes
use Livewire\WithPagination;    //trait para la paginacion

class Coins extends Component
{

    use WithFileUploads;    //llamado a los trait
    use WithPagination;     //llamado a los trait

    public $type,$value,$search,$image,$selected_id,$pageTitle,$componentName;    //propiedades publicas dentro del backend para accesar desde el frontend
    private $pagination = 20;    //paginacion

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        $this->pageTitle = 'LISTADO';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'DENOMINACIONES';    //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->type = 'Elegir'; //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->image = null;
    }

    public function paginationView(){   //metodo para la paginacion personalizada
        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function render()
    {
        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen
            $data = Denomination::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);    //busqueda por tipo
        else
            $data = Denomination::orderBy('id', 'asc')->paginate($this->pagination);   //caso contrario devuelve el listado ordenado por id

        return view('livewire.denomination.coins', ['coins' => $data]) //retorna la vista con la informacion almacenada en variable
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Store(){    //metodo para almacenar nuevos registros
        $rules = [  //reglas de validacion para cada campo en especifico
            'type' => 'not_in:Elegir',
            'value' => 'required|unique:denominations|numeric',
        ];
    
        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.unique' => 'Ya existe',
            //'value.max' => 'Maximo 45 digitos',
            'value.numeric' => 'Este campo solo admite numeros',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $denomination = Denomination::create([  //guardar en variable el registro de este elemento
            'type' => $this->type,
            'value' => $this->value
        ]);

        if($denomination){

            if($this->image){
            
                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/denominations', $customFileName);
                $denomination->image()->create(['url' => $customFileName]);
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-added', 'Registrado correctamente');   //evento a ser escuchado desde el frontend
    }

    public function Edit(Denomination $denomination){  //metodo para abrir modal edit pasandole el id del elemento seleccionado
        //$record = Denomination::find($id, ['id', 'type', 'value', 'image']);    //obtener la informacion especifica del elemento seleccionado y almacenarlo en variable
        $this->type = $denomination->type;    //llenado del campo con los datos almacenados en variable
        $this->value = $denomination->value;  //llenado del campo con los datos almacenados en variable
        $this->selected_id = $denomination->id;   //llenado del campo con los datos almacenados en variable
        $this->image = null;    //no es necesario mostrar la imagen asi que dejamos el campo vacio
        $this->emit('show-modal', 'Mostrar modal!'); //evento a ser escuchado desde el frontend
    }

    public function Update(){   //metodo para actualizar registro
        
        $rules = [  //reglas de validacion para cada campo en especifico
          'type' => 'not_in:Elegir',
          'value' => "required|unique:denominations,value,{$this->selected_id}|numeric" 
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.unique' => 'Ya existe',
            //'value.max' => 'Maximo 45 digitos',
            'value.numeric' => 'Este campo solo admite numeros',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $denomination = Denomination::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable
        
        $denomination->update([ //actualizar el registro con metodo update de eloquent
            'type' => $this->type,
            'value' => $this->value
        ]);

        if($this->image){   //validar si se selecciono una imagen para el registro
            //metodo de php uniqid() para asignar id automatico y unico
            $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
            //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
            $this->image->storeAs('public/denominations', $customFileName); //almacenar informacion
            
            if($denomination->image != null){

                $imageTemp = $denomination->image->url;      //recuperar y guardar en una variable temporal el archivo almacenado originalmente
                $denomination->image()->update(['url' => $customFileName]);     //actualizar la columna image con el archivo seleccionado actualmente

                if($imageTemp != null){     //validar si variable temporal tiene algo almacenado dentro
                    //metodo file_exists de php requiere un parametro (directorio + nombre de archivo) para verificar si un archivo existe fisicamente
                    if(file_exists('storage/denominations/' . $imageTemp)){     //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                        //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                        unlink('storage/denominations/' . $imageTemp);      //si existe fisicamente se elimina ese archivo del directorio
                    }
                }

            }else{

                $denomination->image()->create(['url' => $customFileName]);
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-updated', 'Actualizado correctamente');    //evento a ser escuchado desde el frontend
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy(Denomination $denomination){    //metodo para eliminar registros con una instancia del modelo que contiene el id

        $denomination->delete();    //eliminar registro con metodo delete de eloquent

        if($denomination->image != null){

            $imageTemp = $denomination->image->url;     //recuperar y guardar en una variable temporal el archivo almacenado originalmente
            $denomination->image()->delete();

            if($imageTemp != null){ //validar si el registro tenia una imagen guardada

                if(file_exists('storage/denominations/' . $imageTemp)){     //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                    //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                    unlink('storage/denominations/' . $imageTemp);      //si existe fisicamente se elimina ese archivo del directorio
                }
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-deleted', 'Eliminado correctamente');  //evento a ser escuchado desde el frontend
    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas
        $this->type = 'Elegir';
        $this->value = '';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }
}
