<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;    //trait para la paginacion
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Http\Request;

class Categories extends Component
{
    use WithPagination;     //llamado a los trait

    public $category_name, $search, $selected_id, $pageTitle, $componentName;    //propiedades publicas dentro del backend para accesar desde el frontend
    private $pagination = 20;    //paginacion

    public function mount()
    {    //metodo que se ejecuta en cuanto se monta este componente

        $this->pageTitle = 'listado';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'categorias';    //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->category_name = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }

    public function paginationView()
    {   //metodo para la paginacion personalizada

        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function render()
    {
        if (strlen($this->search) > 0)   //validar si la caja de busqueda contiene algo con metodo php strlen

            $data = Category::withCount('subcategories')
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->pagination);    //busqueda por coincidencias

        else    //caso contrario devuelve todos los registros

            $data = Category::withCount('subcategories')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);


        return view('livewire.category.categories', ['categories' => $data])    //retorna la vista con la informacion almacenada en variable
            ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
            ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Store()
    {    //metodo para almacenar nuevos registros

        $rules = [  //reglas de validacion para cada campo en especifico

            'category_name' => 'required|unique:categories,name|min:3|max:100'
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend

            'category_name.required' => 'Campo requerido',
            'category_name.unique' => 'Ya existe',
            'category_name.min' => 'Minimo 3 caracteres',
            'category_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        Category::create([  //guardar en variable el registro de este elemento

            'name' => $this->category_name
        ]);

        $this->mount();   //limpiar la informacion de los campos del formulario
        $this->emit('item-added', 'Registrado correctamente');  //evento a ser escuchado desde el frontend
    }

    public function Edit(Category $category)
    {  //metodo para cargar modal con los datos ya registrados

        $this->category_name = $category->name;    //llenado del campo con los datos almacenados en variable
        $this->selected_id = $category->id;   //llenado del campo con los datos almacenados en variable
        $this->emit('show-modal', 'Mostrando modal'); //evento a ser escuchado desde el frontend
    }

    public function Update()
    {   //metodo para actualizar registro

        $rules = [  //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente

            'category_name' => "required|min:3|max:100|unique:categories,name,{$this->selected_id}"
        ];

        $messages = [

            'category_name.required' => 'Campo requerido',
            'category_name.unique' => 'Ya existe',
            'category_name.min' => 'Minimo 3 caracteres',
            'category_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable

        $category->update([     //actualizar el registro con metodo update de eloquent

            'name' => $this->category_name
        ]);

        $this->mount();   //limpiar la informacion de los campos del formulario
        $this->emit('item-updated', 'Actualizado correctamente');   //evento a ser escuchado desde el frontend
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados

        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy(Category $category,$subcategories_count)
    {    //metodo para eliminar registros con una instancia del modelo que contiene el id

        if ($subcategories_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion'); //evento a ser escuchado desde el frontend
            return;
        } else {

            $category->delete();    //eliminar registro con metodo delete de eloquent
            $this->mount();   //limpiar la informacion de los campos del formulario
            $this->emit('item-deleted', 'Eliminado correctamente'); //evento a ser escuchado desde el frontend
        }
    }

    public function resetUI()
    {  //metodo para limpiar la informacion de las propiedades publicas

        $this->mount();
    }
}
