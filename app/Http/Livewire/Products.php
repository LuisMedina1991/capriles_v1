<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Status;
use Livewire\Component;
use App\Models\Product;
use App\Models\Value;
use App\Models\Prefix;
use Livewire\WithFileUploads;   //trait para subir imagenes
use Livewire\WithPagination;    //trait para la paginacion
use Exception;
use Illuminate\Support\Facades\DB;

class Products extends Component
{
    use WithPagination;     //llamado a los trait
    use WithFileUploads;    //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $search,$selected_id,$pageTitle,$componentName;
    public $description,$code,$brand,$ring,$threshing,$tarp,$comment,$cat_id,$sub_id,$image,$status,$prefix,$value,$statuses;
    private $pagination = 30;    //paginacion
    public $allValues = [];
    public $allCategories = [];
    public $allSubcategories = [];
    public $allStatuses = [];
    public $allPrefixes = [];
    public $productValues = [];

    public function paginationView(){   //metodo para la paginacion personalizada

        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente

        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->resetValidation();
        $this->resetPage();
        $this->pageTitle = 'LISTADO';
        $this->componentName = 'PRODUCTOS';
        $this->brand = '';
        $this->ring = '';
        $this->threshing = '';
        $this->tarp = '';
        $this->comment = '';
        $this->cat_id = 'Elegir';
        $this->sub_id = 'Elegir';
        $this->status = 'Elegir';
        $this->prefix = 'Elegir';
        $this->image = null;
        $this->allValues = Value::all();
        $this->allCategories = Category::all();
        $this->allSubcategories = Subcategory::all();
        $this->allStatuses = Status::all();
        $this->allPrefixes = Prefix::all();
        $this->statuses = $this->allStatuses->where('type','product');
        $this->productValues = [
            ['cost' => '', 'price' => '']
        ];
    }

    public function render()
    {

        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen

            $data = Product::with(['category','subcategory','status','values'])    //union de tablas
            ->where('products.code', 'like', '%' . $this->search . '%')     //busqueda a traves del codigo del producto
            ->orWhere('products.description', 'like', '%' . $this->search . '%')    //busqueda a traves de la descripcion del producto
            ->orWhere('products.brand', 'like', '%' . $this->search . '%')  //busqueda a traves de la marca del producto
            ->orderBy('products.category_id', 'asc')   //ordenado por categoria de producto
            ->paginate($this->pagination);  //llamado a la paginacion

        else

            $data = Product::with(['category','subcategory','status','values'])    //union de tablas
            ->orderBy('products.category_id', 'asc')   //ordenado por categoria de producto
            ->paginate($this->pagination);  //llamado a la paginacion

        //retorna la vista con la informacion almacenada en variable
        return view('livewire.product.products', [
            'products' => $data,
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function addValue(){

        $this->productValues[] = ['cost' => '', 'price' => ''];
    }

    public function removeValue($index){

        unset($this->productValues[$index]);
        $this->productValues = array_values($this->productValues);
    }

    public function Store(){    //metodo para almacenar nuevos registros

        /*foreach($this->productValues as $value){
            
            dd($value['cost']);
        }*/

        //dd($this->brand);

        $rules = [  //reglas de validacion para cada campo en especifico

            //'description' => 'required|min:3|max:100',
            'prefix' => 'not_in:Elegir',
            //'code' => 'required|min:5|max:100|unique:products',
            'brand' => 'max:45',
            'ring' => 'max:45',
            'threshing' => 'max:45',
            'tarp' => 'max:45',
            'comment' => 'max:100',
            'productValues.*.cost' => 'required|numeric',
            'productValues.*.price' => 'required|numeric',
            'cat_id' => 'not_in:Elegir',
            'sub_id' => 'not_in:Elegir',
            'status' => 'not_in:Elegir',
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend

            //'description.required' => 'Campo requerido',
            //'description.min' => 'Minimo 3 caracteres',
            //'description.max' => 'Maximo 100 caracteres',
            'prefix.not_in' => 'Seleccione una opcion',
            //'code.required' => 'Campo requerido',
            //'code.min' => 'Minimo 5 caracteres',
            //'code.max' => 'Maximo 100 caracteres',
            //'code.unique' => 'Ya existe',
            'brand.max' => 'Maximo 45 caracteres',
            'ring.max' => 'Maximo 45 caracteres',
            'threshing.max' => 'Maximo 45 caracteres',
            'tarp.max' => 'Maximo 45 caracteres',
            'comment.max' => 'Maximo 100 caracteres',
            'productValues.*.cost.required' => 'Campo requerido',
            'productValues.*.cost.numeric' => 'Solo numeros',
            'productValues.*.price.required' => 'Campo requerido',
            'productValues.*.price.numeric' => 'Solo numeros',
            'cat_id.not_in' => 'Seleccione una opcion',
            'sub_id.not_in' => 'Seleccione una opcion',
            'status.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        DB::beginTransaction();

            try {

                $product = Product::create([    //guardar en variable el registro de este elemento

                    //'description' => $this->description,
                    'prefix_id' => $this->prefix,
                    //'code' => $this->code,
                    'brand' => $this->brand,
                    'ring' => $this->ring,
                    'threshing' => $this->threshing,
                    'tarp' => $this->tarp,
                    'comment' => $this->comment,
                    'category_id' => $this->cat_id,
                    'subcategory_id' => $this->sub_id,
                    'status_id' => $this->status,
                ]);


                if($product){

                    /*if($this->image){   //validar si se selecciono una imagen para el registro
                    //metodo de php uniqid() para asignar id automatico y unico
                    $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
                    //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
                    $this->image->storeAs('public/products', $customFileName);  //almacenar informacion
                    $product->image = $customFileName;  //actualizar la columna image del registro anteriormente guardado en variable
                    $product->save();   //volvemos a guardar el registro con la informacion actualizada
                    }*/

                    foreach($this->productValues as $value){

                        Value::create([

                            'cost' => $value['cost'],
                            'price' => $value['price'],
                            'product_id' => $product->id,
                        ]);
                    }

                }

                DB::commit();
                $this->emit('item-added', 'Registrado correctamente');   //evento a ser escuchado desde el frontend
                $this->mount();
            
            } catch (Exception) {
                    
                DB::rollback();
                $this->emit('record-error', 'Error. Cancelando registro');
            }
            
            /*} catch (\Throwable $th) {
                    
                DB::rollback();
                throw $th;
            }*/
            
    }

    public function Edit(Product $product){ //metodo para abrir modal edit pasandole el id del elemento seleccionado

        //$data = $product->values->find($product->id);
        //$data = $product->with('values')->find($product->id);
        //$values = $product->with(['values:id,product_id,cost,price'])->find($product->id);
        //$data = Value::where('product_id',$product->id)->get();
        //$data = $values->values->find($product->id);
        $products = $product->with('values')->get();
        $data = $products->pluck('values');
        //$data = $products->pluck('values')->flatten();
        dd($data);
        //llenado de los campos con los datos almacenados en variable
        /*foreach($this->valores as $valor){
            dd($valor->cost);
            $this->cost2 = $valor->id;
            $this->price2 = $valor->id;
        }*/
        $this->selected_id = $product->id;
        $this->description = $product->description;
        $this->code = $product->code;
        $this->brand = $product->brand;
        $this->ring = $product->ring;
        $this->threshing = $product->threshing;
        $this->tarp = $product->tarp;
        $this->comment = $product->comments;
        //$this->cost2 = $data->id;
        //$this->price2 = '';
        $this->cat_id = $product->category_id;
        $this->sub_id = $product->subcategory_id;
        $this->status = $product->status_id;
        $this->image = null;

        $this->emit('show-modal2', 'Abrir Modal');   //evento a ser escuchado desde el frontend

    }

    public function Update(){   //metodo para actualizar registro

        $rules = [  //reglas de validacion para cada campo en especifico

            'description' => 'required|min:3|max:100',
            'code' => "required|min:5|max:100|unique:products,code,{$this->selected_id}",
            'brand' => 'max:45',
            'ring' => 'max:45',
            'threshing' => 'max:45',
            'tarp' => 'max:45',
            'comment' => 'max:100',
            'cost' => 'required|numeric',
            'price' => 'numeric',
            'cat_id' => 'not_in:Elegir',
            'sub_id' => 'not_in:Elegir',
            'status' => 'not_in:Elegir',
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend

            'description.required' => 'Campo requerido',
            'description.min' => 'Minimo 3 caracteres',
            'description.max' => 'Maximo 100 caracteres',
            'code.required' => 'Campo requerido',
            'code.min' => 'Minimo 5 caracteres',
            'code.max' => 'Maximo 100 caracteres',
            'code.unique' => 'Ya existe',
            'brand.max' => 'Maximo 45 caracteres',
            'ring.max' => 'Maximo 45 caracteres',
            'threshing.max' => 'Maximo 45 caracteres',
            'tarp.max' => 'Maximo 45 caracteres',
            'comment.max' => 'Maximo 100 caracteres',
            'cost.required' => 'Campo requerido',
            'cost.numeric' => 'Solo numeros',
            'price.numeric' => 'Solo numeros',
            'cat_id.not_in' => 'Seleccione una opcion',
            'sub_id.not_in' => 'Seleccione una opcion',
            'status.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $product = Product::find($this->selected_id);   //seleccionar el registro del id seleccionado actualmente y guardar en variable

        $product->Update([  //actualizar el registro con metodo update de eloquent
            'measurement' => $this->medida,
            'brand' => $this->marca,
            'ring' => $this->aro,
            'threshing' => $this->trilla,
            'tarp' => $this->lona,
            'code' => $this->code,
            'cost' => $this->cost,
            'price' => $this->price,
            'category_id' => $this->cat_id,
            'subcategory_id' => $this->sub_id,
        ]);

        if($this->image){   //validar si se selecciono una imagen para el registro
            //metodo de php uniqid() para asignar id automatico y unico
            $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
            //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
            $this->image->storeAs('public/products', $customFileName);  //almacenar informacion
            $imageTemp = $product->image;   //recuperar y guardar en una variable temporal el archivo almacenado originalmente
            $product->image = $customFileName;  //actualizar la columna image con el archivo seleccionado actualmente
            $product->save();   //volvemos a guardar el registro con la informacion actualizada

            if($imageTemp != null){ //validar si variable temporal tiene algo almacenado dentro
                //metodo file_exists de php requiere un parametro (directorio + nombre de archivo) para verificar si un archivo existe fisicamente
                if(file_exists('storage/products/' . $imageTemp )){ //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                    //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                    unlink('storage/products/' . $imageTemp);   //si existe fisicamente se elimina ese archivo del directorio
                }
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-updated', 'Producto actualizado');    //evento a ser escuchado desde el frontend
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy(Product $product){  //metodo para eliminar registros con una instancia del modelo que contiene el id
        $imageTemp = $product->image;   //recuperar y guardar en una variable temporal el archivo almacenado originalmente
        $product->delete(); //eliminar registro con metodo delete de eloquent

        if($imageTemp != null){ //validar si el registro tenia una imagen guardada
            if(file_exists('storage/products/' . $imageTemp )){ //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                unlink('storage/products/' . $imageTemp);   //si existe fisicamente se elimina ese archivo del directorio
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-deleted', 'Registro eliminado');  //evento a ser escuchado desde el frontend
    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas
        
        /*$this->description = '';
        $this->code = '';
        $this->brand = '';
        $this->ring = '';
        $this->threshing = '';
        $this->tarp = '';
        $this->comment = '';
        $this->cost = '';
        $this->price = '';
        $this->cat_id = 'Elegir';
        $this->sub_id = 'Elegir';
        $this->status = 'Elegir';
        $this->value = '';
        $this->image = null;
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal*/
        $this->mount();
    }
}
