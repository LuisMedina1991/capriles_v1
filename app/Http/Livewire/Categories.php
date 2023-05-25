<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;    //trait para la paginacion
//use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Http\Request;

class Categories extends Component
{
    use WithPagination;     //llamado a los trait

    public $pageTitle,$componentName,$search,$search_2,$selected_id;    //propiedades publicas dentro del backend para accesar desde el frontend
    public $name;
    private $pagination = 20;    //propiedad privada para la paginacion paginacion

    public function mount() //metodo que se ejecuta en cuanto se monta este componente
    {    
        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->pageTitle = 'listado';   
        $this->componentName = 'categorias';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }

    public function paginationView()    //metodo para la paginacion personalizada
    {
        return 'vendor.livewire.bootstrap'; //estilos para la paginacion
    }

    public function render()
    {
        switch ($this->search_2){   //comprobacion para decidir cuales registros se van a mostrar

            case 0: //mostrar registros activos

                if (strlen($this->search) > 0){   //validar si la caja de busqueda contiene algo con metodo php strlen

                    $data = Category::withCount(['subcategories' => function ($query) {
                        $query->where('status_id',1);
                    }])
                    ->where('status_id',1)  //estado 1 = activo
                    ->where('name', 'like', '%' . $this->search . '%')  //busqueda por coincidencias
                    ->orderBy('name', 'asc')    //ordenamiento ascendente de acuerdo a cierta columna de la tabla
                    ->paginate($this->pagination);  //se toma la cantidad de registros definidos en el metodo mount
        
                }else{    //caso contrario devuelve todos los registros
        
                    $data = Category::withCount(['subcategories' => function ($query) {
                        $query->where('status_id',1);
                    }])
                    ->where('status_id',1)  //estado 1 = activo
                    ->orderBy('name', 'asc')    //ordenamiento ascendente de acuerdo a cierta columna de la tabla
                    ->paginate($this->pagination);  //se toma la cantidad de registros definidos en el metodo mount
                }

            break;

            case 1: //mostrar registros bloqueados/inactivos

                if (strlen($this->search) > 0){   //validar si la caja de busqueda contiene algo con metodo php strlen

                    $data = Category::withCount(['subcategories' => function ($query) {
                        $query->where('status_id',1);
                    }])
                    ->where('status_id',2)  //estado 2 = bloquedado
                    ->where('name', 'like', '%' . $this->search . '%')  //busqueda por coincidencias
                    ->orderBy('name', 'asc')    //ordenamiento ascendente de acuerdo a cierta columna de la tabla
                    ->paginate($this->pagination);  //se toma la cantidad de registros definidos en el metodo mount
        
                }else{    //caso contrario devuelve todos los registros
        
                    $data = Category::withCount(['subcategories' => function ($query) {
                        $query->where('status_id',1);
                    }])
                    ->where('status_id',2)  //estado 2 = bloquedado
                    ->orderBy('name', 'asc')    //ordenamiento ascendente de acuerdo a cierta columna de la tabla
                    ->paginate($this->pagination);  //se toma la cantidad de registros definidos en el metodo mount
                }

            break;

        }

        return view('livewire.category.categories', ['categories' => $data])    //retorna la vista con la informacion almacenada en variable
            ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
            ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Store() //metodo para almacenar nuevos registros
    {
        //reglas de validacion para cada campo en especifico
        $rules = [

            //campo requerido,con longitud minima y maxima,unico en la tabla especificada
            'name' => 'required|min:3|max:45|unique:categories',
        ];

        //mensajes para cada error de validacion para ser recibidos desde el frontend
        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        //crear nuevo registro en la db y guardarlo como objeto
        $category = Category::create([

            'name' => $this->name,
            'status_id' => 1
        ]);

        if($category){  //si la variable contiene al objeto significa que el registro fue exitoso

            $this->emit('record-added', 'Registrado correctamente');  //evento a ser escuchado desde el frontend
            $this->mount();   //reinicializamos todas las variables

        }else{

            $this->emit('record-error', 'Error al registrar');  //evento a ser escuchado desde el frontend
            return; //detener cualquier accion que se este realizando
        }

    }

    public function Edit(Category $category)    //metodo para cargar modal con los datos ya registrados
    {
        $this->name = $category->name;    //llenado del campo con los datos almacenados en variable
        $this->selected_id = $category->id;   //llenado del campo con los datos almacenados en variable
        $this->emit('show-modal'); //evento a ser escuchado desde el frontend
    }

    public function Update()    //metodo para actualizar registro
    {
        $rules = [

            //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente
            'name' => "required|min:3|max:45|unique:categories,name,{$this->selected_id}",
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $category = Category::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable

        //actualizar el registro con metodo update de eloquent
        $category->update([

            'name' => $this->name

        ]);

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(Category $category){   //metodo para desbloquear registros

        $category->update([

            'status_id' => 1

        ]);

        $this->emit('record-activated','Registro desbloqueado');
        $this->mount();
    }

    //metodo para ocultar registros con una instancia del modelo y conteo de tablas dependientes
    public function Destroy(Category $category,$subcategories_count)
    {
        if ($subcategories_count > 0) { //validar si existen tablas dependientes

            $this->emit('record-error', 'No se puede eliminar debido a relacion'); //evento a ser escuchado desde el frontend
            return;

        } else {

            $category->update([

                'status_id' => 2

            ]);

            $this->emit('record-deleted', 'Eliminado correctamente'); //evento a ser escuchado desde el frontend
            $this->mount();   //limpiar la informacion de los campos del formulario
        }
    }

    //metodo para ejecutar el metodo mount con cada accion
    public function resetUI()
    {   
        $this->mount();
    }
}
