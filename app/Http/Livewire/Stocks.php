<?php

namespace App\Http\Livewire;

use App\Models\Income;
use App\Models\IncomeDetail;
use Livewire\Component;
use App\Models\Office;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\TransferDetail;
use Livewire\WithPagination;    //trait para la paginacion
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Stocks extends Component
{
    use WithPagination;     //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $id_office,$id_product,$cant,$alerts,$search,$pageTitle,$componentName,$selected_id,$formTitle,$id_office2,$cant2,$my_total;
    private $pagination = 20;    //paginacion

    public function paginationView(){   //metodo para la paginacion personalizada
        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        $this->pageTitle = 'Listado';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'Stock'; //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->id_office = 'Elegir';    //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->id_product = 'Elegir';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->formTitle = 'Traspaso';  //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->id_office2 = 'Elegir';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->my_total = 0;
    }

    public function render()
    {   
        $vars = Product::with('offices')->get();
        
        foreach($vars as $var){

            foreach($var->offices as $office){

                $this->my_total += $var->cost * $office->pivot->stock;
            }
        }

        if(strlen($this->search) > 0){   //validar si la caja de busqueda tiene algo escrito con metodo php strlen

            $data = Product::join('office_product as stock','stock.product_id','products.id') //union entre products y office_product
            ->join('offices','offices.id','stock.office_id')    //union entre office_product y offices
            ->select('stock.*','products.code as code','products.brand as brand','products.threshing as threshing','products.tarp as tarp','products.cost as cost','offices.name as office')    //seleccion de columnas
            ->where('products.code', 'like', '%' . $this->search . '%') //filtrado de resultados
            ->orWhere('offices.name', 'like', '%' . $this->search . '%')    //filtrado de resultados
            ->orWhere('products.brand', 'like', '%' . $this->search . '%')    //filtrado de resultados
            ->orWhere('products.threshing', 'like', '%' . $this->search . '%')    //filtrado de resultados
            ->orWhere('products.tarp', 'like', '%' . $this->search . '%')    //filtrado de resultados
            ->orderBy('products.code', 'asc')   //orden de resultados
            ->paginate($this->pagination);  //llamado a la paginacion

        }else{

            $data = Product::join('office_product as stock','stock.product_id','products.id') //union entre products y office_product
            ->join('offices','offices.id','stock.office_id')    //union entre office_product y offices
            ->select('stock.*','products.code as code','products.brand as brand','products.threshing as threshing','products.tarp as tarp','products.cost as cost','offices.name as office')    //seleccion de columnas
            ->orderBy('products.code', 'asc')   //orden de resultados
            ->paginate($this->pagination);  //llamado a la paginacion
            
        }
        return view('livewire.stock.stocks', [
            'stocks' => $data,
            'offices' => Office::orderBy('name','asc')->get(),
            'offices2' => Office::orderBy('name','asc')->get(),
            'products' => Product::orderBy('code','asc')->get(),
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function resetUI(){
        $this->id_office = 'Elegir';
        $this->id_product = 'Elegir';
        $this->cant = '';
        $this->alerts = '';
        $this->search = '';
        $this->selected_id = '0';
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
        $this->id_office2 = 'Elegir';
        $this->cant2 = '';
    }

    public function Store(){    //metodo para almacenar nuevos registros

        $rules = [  //reglas de validacion para cada campo en especifico
            
            'id_product' => ['required','not_in:Elegir',Rule::unique('office_product','product_id')->where(function ($query) {
                return $query->where('office_id',$this->id_office);
            })],
            'id_office' => 'required|not_in:Elegir',
            'cant' => 'required',
            'alerts' => 'required'
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'id_product.required' => 'El codigo de producto es requerido',
            'id_product.not_in' => 'Elija el codigo de algun producto',
            'id_product.unique' => 'El producto ya existe en la sucursal',
            'id_office.required' => 'La sucursal es requerida',
            'id_office.not_in' => 'Elija una sucursal',
            'cant.required' => 'El stock es requerido',
            'alerts.required' => 'Ingrese un valor minimo en existencias'
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        DB::beginTransaction();     //iniciar transaccion

        try{
            
            $data = Product::find($this->id_product);  //obtener producto y guardar en variable
            //accedemos al metodo de la relacion entre products y offices
            //con el metodo attach se inserta un nuevo registro en la tabla intermedia office_product
            $data->offices()->attach($this->id_office,[
                'stock' => $this->cant,
                'alerts' => $this->alerts
            ]);
                
            //se crea un nuevo registro en la tabla incomes y se guarda en variable
            $income = Income::create([
                'total' => $data->cost * $this->cant,
                'items' => $this->cant,
                'office' => $data->offices->firstWhere('id',$this->id_office)->name,
                'user_id' => Auth()->user()->id //obtener id de usuario logueado en el sistema
            ]);
                
            if($income){    //validar si se guardo el registro en la tabla incomes

                //se crea un nuevo registro en la tabla income_details
                IncomeDetail::create([
                    'cost' => $data->cost,
                    'quantity' => $income->items,
                    'office' => $income->office,
                    'product_id' => $data->id,
                    'income_id' => $income->id
                ]);
            }

            DB::commit();   //confirmar la transaccion en la base de datos

            $this->resetUI();   //limpiar la informacion de los campos del formulario
            $this->emit('item-added', 'Producto Registrado');   //evento a ser escuchado desde el frontend

        }catch(Exception $e){   //capturar error en variable 
            DB::rollback();     //deshacer todas las operaciones en caso de error
            $this->emit('income-error', $e->getMessage());    //evento a ser escuchado desde el frontend
        }
    }

    public function Edit($id){ //metodo para abrir modal edit pasandole el id del elemento seleccionado
        
        $data = Product::join('office_product as stock','stock.product_id','products.id') //union entre products y office_product
        ->join('offices','offices.id','stock.office_id')    //union entre office_product y offices
        ->select('stock.*','products.code as product','offices.name as office')    //seleccion de columnas
        ->firstWhere('stock.id',$id);   //seleccion de la 1era coicidencia
        
        //llenado de los campos con los datos almacenados en variable
        $this->selected_id = $data->id;
        $this->id_office = $data->office_id;
        $this->id_product = $data->product_id;
        $this->cant = $data->stock;
        $this->alerts = $data->alerts;

        $this->emit('show-modal', 'Abrir Modal');   //evento a ser escuchado desde el frontend

    }

    public function Update(){   //metodo para actualizar registro
        
        $rules = [  //reglas de validacion para cada campo en especifico
            'id_product' => 'required|not_in:Elegir',
            'id_office' => 'required|not_in:Elegir',
            'cant' => 'required',
            'alerts' => 'required'
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'id_product.required' => 'El codigo de producto es requerido',
            'id_product.not_in' => 'Elija el codigo de algun producto',
            'id_office.required' => 'La sucursal es requerida',
            'id_office.not_in' => 'Elija una sucursal',
            'cant.required' => 'El stock es requerido',
            'alerts.required' => 'Ingrese un valor minimo en existencias'
        ];
        
        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $product = Product::find($this->id_product);  //obtener producto y guardar en variable
        $cant2 = $product->offices->firstWhere('pivot.office_id',$this->id_office)->pivot->stock;
        /*
        $verifi = $product->offices()->get()->firstWhere('pivot.office_id',$this->id_office);
        $income = $product->join('office_product as o_p','o_p.product_id','products.id')
        ->join('offices as o','o.id','o_p.office_id')
        ->join('income_details as i_d','i_d.product_id','products.id')
        ->join('incomes as i','i.id','i_d.income_id')
        ->select('i.id')
        ->where('i_d.product_id',$product->id)
        ->where('o.id',$office)
        ->first();
        */
        //dd($cant2);

        DB::beginTransaction();     //iniciar transaccion
        
        try{

            //if($verifi != null){    //verificar si la sucursal fue cambiada

                if($this->cant > $cant2){
                
                    //se crea un nuevo registro en la tabla incomes y se guarda en variable
                    $income = Income::create([
                        'total' => ($this->cant - $cant2) * $product->cost,
                        'items' => $this->cant - $cant2,
                        'office' => $product->offices->firstWhere('pivot.office_id',$this->id_office)->name,
                        'user_id' => Auth()->user()->id //obtener id de usuario logueado en el sistema
                    ]);
    
                    $product->offices()->updateExistingPivot($this->id_office,[  //actualizar el registro con metodo update de eloquent
                        'stock' => $this->cant,
                        'office_id' => $this->id_office,
                        'product_id' => $this->id_product,
                        'alerts' => $this->alerts
                    ]);
    
                    if($income){    //validar si se guardo el registro en la tabla incomes
    
                        //se crea un nuevo registro en la tabla income_details
                        IncomeDetail::create([
                            'cost' => $product->cost,
                            'quantity' => $income->items,
                            'office' => $income->office,
                            'product_id' => $product->id,
                            'income_id' => $income->id
                        ]);
                    }
    
                }else{
    
                    $product->offices()->updateExistingPivot($this->id_office,[  //actualizar el registro con metodo update de eloquent
                        'stock' => $this->cant,
                        'office_id' => $this->id_office,
                        'product_id' => $this->id_product,
                        'alerts' => $this->alerts
                    ]);
                }

            /*}else{

                $product->offices()->detach($office);

                $product->offices()->attach($this->id_office,[
                    'stock' => $this->cant,
                    'alerts' => $this->alerts
                ]);

            }*/

            DB::commit();   //confirmar la transaccion en la base de datos

            $this->resetUI();   //limpiar la informacion de los campos del formulario
            $this->emit('item-updated', 'Producto actualizado');    //evento a ser escuchado desde el frontend

        }catch(Exception $e){   //capturar error en variable 
            DB::rollback();     //deshacer todas las operaciones en caso de error
            $this->emit('income-error', $e->getMessage());    //evento a ser escuchado desde el frontend
        }
    }

    public function Charge($id){ //metodo para abrir modal edit pasandole el id del elemento seleccionado
        
        $data = Product::join('office_product as stock','stock.product_id','products.id') //union entre products y office_product
        ->join('offices','offices.id','stock.office_id')    //union entre office_product y offices
        ->select('stock.*','products.code as product','offices.name as office')    //seleccion de columnas
        ->firstWhere('stock.id',$id);   //seleccion de la 1era coicidencia
        
        //llenado de los campos con los datos almacenados en variable
        $this->selected_id = $data->id;
        $this->id_office = $data->office_id;
        $this->id_product = $data->product_id;
        $this->cant = $data->stock;
        $this->cant2 = $data->stock;

        $this->emit('show-modal2', 'Abrir Modal');   //evento a ser escuchado desde el frontend

    }

    public function Transfer(){   //metodo para actualizar registro
        $rules = [  //reglas de validacion para cada campo en especifico
            'id_product' => 'required|not_in:Elegir',
            'id_office' => 'required|not_in:Elegir',
            'id_office2' => "required|not_in:Elegir,$this->id_office",
            'cant' => 'required',
            'cant2' => "required|lte:$this->cant",
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'id_product.required' => 'El codigo de producto es requerido',
            'id_product.not_in' => 'Elija el codigo de algun producto',
            'id_office.required' => 'La sucursal de origen es requerida',
            'id_office.not_in' => 'Elija una sucursal de origen',
            'id_office2.required' => 'La sucursal de destino es requerida',
            'id_office2.not_in' => 'Elija una sucursal de destino diferente',
            'cant.required' => 'El stock es requerido',
            'cant2.required' => 'La cantidad a mover es requerida',
            'cant2.lte' => 'La cantidad es mayor al stock',
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $data = Product::find($this->id_product);  //obtener producto y guardar en variable
        
        DB::beginTransaction();     //iniciar transaccion
        
        try{
            //accedemos a la tabla pivote entre products y offices
            //validar si aun no existe un registro con el product_id y office_id seleccionados
            if($data->offices->firstWhere('pivot.office_id',$this->id_office2) == null){

                $data = Product::find($this->id_product);   //obtener producto y guardar en variable
                //con el metodo attach se inserta un nuevo registro en la tabla pivote dandole como stock la cantidad que le estamos transfiriendo
                $data->offices()->attach($this->id_office2,[
                    'stock' => $this->cant2,
                    'alerts' => 1
                ]);

                //con el metodo updateExistingPivot se actualiza el registro en la tabla pivote restandole al stock la cantidad transferida
                $data->offices()->updateExistingPivot($this->id_office,[ 
                    'stock' => $this->cant - $this->cant2
                ]);
            
            }else{  //caso contrario se actualiza el stock en ambos registros

                $data = Product::find($this->id_product);   //obtener producto y guardar en variable
                //con el metodo updateExistingPivot se actualiza el registro en la tabla pivote restandole al stock la cantidad transferida
                $data->offices()->updateExistingPivot($this->id_office,[
                    'stock' => $this->cant - $this->cant2
                ]);
                //con el metodo updateExistingPivot se actualiza el registro en la tabla pivote sumandole al stock la cantidad transferida
                $data->offices()->updateExistingPivot($this->id_office2,[
                    'stock' => $data->offices()->get()->firstWhere('pivot.office_id',$this->id_office2)->pivot->stock + $this->cant2
                ]);

            }
            //dd($data->offices->firstWhere('pivot.office_id',$this->id_office2)->pivot->stock + $this->cant2);
            //se crea un nuevo registro en la tabla transfers y se guarda en variable
            $transfer = Transfer::create([
                'total' => $data->cost * $this->cant2,
                'items' => $this->cant2,
                'from_office' => $data->offices->firstWhere('pivot.office_id',$this->id_office)->name,
                'to_office' => $data->offices->firstWhere('pivot.office_id',$this->id_office2)->name,
                'user_id' => Auth()->user()->id //obtener id de usuario logueado en el sistema
            ]);
            
            if($transfer){    //validar si se guardo el registro en la tabla transfers

                //se crea un nuevo registro en la tabla transfer_details
                TransferDetail::create([
                    'cost' => $data->cost,
                    'quantity' => $transfer->items,
                    'from_office' => $transfer->from_office,
                    'to_office' => $transfer->to_office,
                    'product_id' => $data->id,
                    'transfer_id' => $transfer->id
                ]);
            }
            
            DB::commit();   //confirmar la transaccion en la base de datos

            $this->resetUI();   //limpiar la informacion de los campos del formulario
            $this->emit('item-transfered', 'Producto Traspasado');   //evento a ser escuchado desde el frontend

        }catch(Exception $e){   //capturar error en variable 
            DB::rollback();     //deshacer todas las operaciones en caso de error
            $this->emit('income-error', $e->getMessage());    //evento a ser escuchado desde el frontend
        }
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Destroy($product,$office){  //metodo para eliminar registros con una instancia del modelo que contiene el id

        $data = Product::find($product);
        $data->offices()->detach($office);

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-deleted', 'Registro eliminado');  //evento a ser escuchado desde el frontend
    }
}
