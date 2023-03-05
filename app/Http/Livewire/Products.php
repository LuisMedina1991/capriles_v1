<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\PresentationSubcategory;
use App\Models\Status;
use Livewire\Component;
use App\Models\Product;
use App\Models\Value;
use App\Models\Office;
use App\Models\OfficeValue;
use App\Models\Income;
use App\Models\Transfer;
use App\Models\Provider;
use App\Models\Costumer;
use App\Models\Sale;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Products extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search, $search_2, $selected_id, $pageTitle, $componentName, $comment, $value, $image;
    public $brandId,$statusId, $containerId,$brand_name,$status_name,$providerId,$costumerId;
    public $allBrands,$allStatuses, $allContainers, $allValues,$allOffices,$allProducts;
    public $allContainers_2,$allProviders,$allStatuses_2,$allCostumers;
    public $my_total,$stock_details,$aux_1, $aux_2;
    public $product_id,$office_id_1,$office_id_2,$value_id,$cant_1,$cant_2;
    private $pagination = 30;
    public $productValues = [];

    public function paginationView()
    {

        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {

        $this->resetValidation();
        $this->resetPage();
        $this->pageTitle = 'listado';
        $this->componentName = 'productos';
        $this->selected_id = 0;
        $this->search = '';
        $this->search_2 = 0;
        $this->comment = '';
        $this->image = null;
        $this->value = 'Elegir';
        $this->statusId = 'Elegir';
        $this->brandId = 'Elegir';
        $this->containerId = 'Elegir';
        $this->providerId = 'Elegir';
        $this->costumerId = 'Elegir';
        $this->status_name = '';
        $this->brand_name = '';
        $this->aux_1 = '';
        $this->aux_2 = '';
        $this->my_total = 0;
        $this->stock_details = [];
        $this->allValues = Value::where('status_id', 1)->get();
        $this->allOffices = Office::select('id', 'name')->get();
        $this->allStatuses = Status::select('id', 'name', 'type')->where('type', 'registro')->get();
        $this->allStatuses_2 = Status::select('id', 'name', 'type')->where('type', 'transaccion')->get();
        $this->allBrands = Brand::select('id', 'name')->get();
        $this->allContainers = PresentationSubcategory::where('status_id',1)->with('presentation','subcategory.category')->get();
        $this->allContainers_2 = PresentationSubcategory::with('presentation','subcategory.category')->get();
        $this->allProducts = Product::all();
        $this->allProviders = Provider::select('id', 'name')->get();
        $this->allCostumers = Costumer::select('id', 'name')->get();
        $this->productValues = [
            ['id' => '', 'cost' => '', 'price' => '', 'is_saved' => false]
        ];
    }

    public function render()
    {

        switch ($this->search_2) {

            case 0:

                if (strlen($this->search) > 0){

                    $data = Product::where('status_id', 1)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->where('code', 'like', '%' . $this->search . '%')
                    /*->orWhereHas('brand', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })*/
                    ->orderBy('code', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Product::where('status_id', 1)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->orderBy('code', 'asc')
                    ->paginate($this->pagination);
        
                    /*$data = Product::where('status_id',1)
                    ->with(['values' => function($query){
                        $query->where('status_id',1);
                    }])
                    ->orderBy('id','asc')
                    ->paginate($this->pagination);*/
        
                    /*$data = Product::where('status_id',1)
                    ->with(['values' => function($query){
                        $query->has('offices');
                    }])
                    ->orderBy('id','asc')
                    ->paginate($this->pagination);*/        
                }

            break;

            case 1:

                if (strlen($this->search) > 2){

                    $data = Product::where('status_id', 2)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->where('code', 'like', '%' . $this->search . '%')
                    ->orderBy('code', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Product::where('status_id', 2)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->orderBy('code', 'asc')
                    ->paginate($this->pagination);
                }

            break;
        }

        /*foreach($data as $product){

            foreach($product->activeValues as $value){

                foreach($value->offices as $office){
    
                    $this->my_total += $value->cost * $office->pivot->stock;
                }
            }
        }*/


        return view('livewire.product.products', [
            'products' => $data,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function updatedsearch()
    {
        $this->value = 'Elegir';
    }

    public function addValue()
    {   
        
        foreach($this->productValues as $key => $productValue){

            $this->resetErrorBag(['productValues.' . $key . '.cost','productValues.' . $key . '.price']);

            if(!$productValue['is_saved']){
                
                $this->addError('productValues.' . $key . '.cost', 'Debe terminar de editar');
                $this->addError('productValues.' . $key . '.price', 'Debe terminar de editar');
                return;
            }

            if($key == 3){
                
                $this->addError('productValues.' . $key . '.cost', 'Maximo 4 costos');
                $this->addError('productValues.' . $key . '.price', 'Maximo 4 precios');
                return;
            }

        }
        
        $this->productValues[] = ['id' => '', 'cost' => '', 'price' => '', 'is_saved' => false];
    }

    public function saveValue($index){

        $this->resetErrorBag(['productValues.' . $index . '.cost','productValues.' . $index . '.price']);
        $this->productValues[$index]['is_saved'] = true;
    }

    public function editValue($index){

        foreach($this->productValues as $key => $productValue){

            $this->resetErrorBag(['productValues.' . $key . '.cost','productValues.' . $key . '.price']);

            if(!$productValue['is_saved']){
                
                $this->addError('productValues.' . $key . '.cost', 'Debe terminar de editar');
                $this->addError('productValues.' . $key . '.price', 'Debe terminar de editar');
                return;
            }
        }

        $this->productValues[$index]['is_saved'] = false;
    }

    public function removeValue($index)
    {

        $this->resetErrorBag(['productValues.' . $index . '.cost','productValues.' . $index . '.price']);

        if($index > 0){

            unset($this->productValues[$index]);
            $this->productValues = array_values($this->productValues);

        }else{

            $this->addError('productValues.' . $index . '.cost', 'Minimo 1 costo');
            $this->addError('productValues.' . $index . '.price', 'Minimo 1 precio');
            return;
        }
    }

    public function Stock_Detail($product_id){

        $product = Product::find($product_id);
        $this->stock_details = $product->activeStocks;
        $this->emit('show-stock-detail','mostrando modal');
    }

    public function ShowSaleModal(OfficeValue $stock){

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->office_id_1 = $stock->office_id;
        $this->value_id = $stock->value_id;
        $this->statusId = 'Elegir';
        $this->costumerId = 'Elegir';
        $this->cant_1 = $stock->stock;
        $this->cant_2 = '';
        $this->aux_1 = number_format($stock->value->cost,2);
        $this->aux_2 = '';
        $this->emit('show-sale-modal', 'Abrir Modal');
    }

    public function CloseSaleModal(){

        $this->resetValidation($this->cant_2 = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Sale(){

        $rules = [

            'costumerId' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'cant_2' => "required|lte:$this->cant_1|integer|gt:0",
            'aux_2' => 'required|numeric',
        ];

        $messages = [

            'costumerId.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'cant_2.required' => 'Campo requerido',
            'cant_2.lte' => 'La cantidad es mayor al stock',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
            'aux_2.required' => 'Campo Requerido',
            'aux_2.numeric' => 'Solo Numeros',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);

            $sale = Sale::create([

                'quantity' => $this->cant_2,
                'sale_price' => $this->aux_2,
                'total' => $this->aux_2 * $this->cant_2,
                'utility' => ($this->aux_2 * $this->cant_2) - ($value->cost * $this->cant_2),
                'status_id' => $this->statusId,
                'user_id' => Auth()->user()->id,
                'costumer_id' => $this->costumerId,
                'office_value_id' => $this->selected_id

            ]);

            if($sale){

                $value->offices()->updateExistingPivot($this->office_id_1,[

                    'stock' => $this->cant_1 - $this->cant_2
    
                ]);
            }

            DB::commit();
            $this->emit('item-saled', 'Venta Exitosa');
            $this->value = 'Elegir';
            $this->Stock_Detail($this->product_id);

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowIncomeModal(OfficeValue $stock){

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->office_id_1 = $stock->office_id;
        $this->value_id = $stock->value_id;
        $this->statusId = 'Elegir';
        $this->cant_1 = $stock->stock;
        $this->cant_2 = 0;
        $this->emit('show-income-modal', 'Abrir Modal');
    }

    public function CloseIncomeModal(){

        $this->resetValidation($this->cant_2 = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Income(){

        $rules = [

            'providerId' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'cant_2' => "required|integer|gt:0",
        ];

        $messages = [

            'providerId.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'cant_2.required' => 'Campo requerido',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);

            $income = Income::create([

                'type' => 'compra',
                'quantity' => $this->cant_2,
                'total' => $value->cost * $this->cant_2,
                'status_id' => $this->statusId,
                'user_id' => Auth()->user()->id,
                'provider_id' => $this->providerId,
                'office_value_id' => $this->selected_id

            ]);

            if($income){

                $value->offices()->updateExistingPivot($this->office_id_1,[

                    'stock' => $this->cant_1 + $this->cant_2
    
                ]);
            }

            DB::commit();
            $this->emit('item-entered', 'Ingreso Exitoso');
            $this->value = 'Elegir';
            $this->Stock_Detail($this->product_id);

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowTransferModal(OfficeValue $stock){

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->office_id_1 = $stock->office_id;
        $this->office_id_2 = 'Elegir';
        $this->value_id = $stock->value_id;
        $this->cant_1 = $stock->stock;
        $this->cant_2 = $stock->stock;
        $this->emit('show-transfer-modal', 'Abrir Modal');
    }

    public function CloseTransferModal(){

        $this->resetValidation($this->cant_2 = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Transfer(){

        $rules = [

            'office_id_2' => "not_in:Elegir,$this->office_id_1",
            'cant_2' => "required|lte:$this->cant_1|integer|gt:0",
        ];

        $messages = [

            'office_id_2.not_in' => 'Elija una sucursal de destino diferente',
            'cant_2.required' => 'Campo requerido',
            'cant_2.lte' => 'La cantidad es mayor al stock',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $from_office = $this->allOffices->find($this->office_id_1);
            $to_office = $this->allOffices->find($this->office_id_2);
            //$pivot = $value->offices()->firstWhere('office_id',$this->office_id_2);
            //$pivot = $value->offices()->get()->firstWhere('pivot.office_id',$this->office_id_2);
            $pivot = $value->offices->firstWhere('pivot.office_id',$this->office_id_2);

            $transfer = Transfer::create([

                'quantity' => $this->cant_2,
                'from_office' => $from_office->name,
                'to_office' => $to_office->name,
                'status_id' => 3,
                'user_id' => Auth()->user()->id,
                'office_value_id' => $this->selected_id

            ]);

            if($transfer){

                $value->offices()->updateExistingPivot($this->office_id_1,[

                    'stock' => $this->cant_1 - $this->cant_2
    
                ]);
                
                $value->offices()->updateExistingPivot($this->office_id_2,[
    
                    'stock' => $pivot->pivot->stock + $this->cant_2
    
                ]);
            }

            DB::commit();
            $this->emit('item-transfered', 'Traspaso Exitoso');
            $this->Stock_Detail($this->product_id);

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function Store()
    {
        
        $rules = [

            'containerId' => 'not_in:Elegir',
            'brandId' => 'not_in:Elegir',
            'comment' => 'max:100',
            'productValues.*.cost' => 'required|numeric',
            'productValues.*.price' => 'required|numeric',
        ];

        $messages = [

            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 100 caracteres',
            'productValues.*.cost.required' => 'Campo requerido',
            'productValues.*.cost.numeric' => 'Solo numeros',
            'productValues.*.price.required' => 'Campo requerido',
            'productValues.*.price.numeric' => 'Solo numeros',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $product = Product::create([

                'status_id' => 1,
                'presentation_subcategory_id' => $this->containerId,
                'brand_id' => $this->brandId,
                'comment' => $this->comment
            ]);


            if ($product) {

                /*if($this->image){   //validar si se selecciono una imagen para el registro
                    //metodo de php uniqid() para asignar id automatico y unico
                    $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
                    //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
                    $this->image->storeAs('public/products', $customFileName);  //almacenar informacion
                    $product->image = $customFileName;  //actualizar la columna image del registro anteriormente guardado en variable
                    $product->save();   //volvemos a guardar el registro con la informacion actualizada
                    }*/

                foreach ($this->productValues as $value_1) {

                    $value_2 = Value::create([

                        'cost' => $value_1['cost'],
                        'price' => $value_1['price'],
                        'product_id' => $product->id,
                        'status_id' => 1
                    ]);

                    $stocks = [];

                    foreach($this->allOffices as $office){

                        $stocks[$office->id] = ['alerts' => 1, 'stock' => 0];
                        
                    }

                    $value_2->offices()->attach($stocks);
                }
            }

            DB::commit();
            $this->emit('item-added', 'Registrado correctamente');
            $this->mount();

            /*} catch (Exception) {
                    
                DB::rollback();
                $this->emit('record-error', 'Error. Cancelando registro');
            }*/
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowBrandModal()
    {
        $this->emit('show-modal-2', 'Mostrando Modal');
    }

    public function CloseBrandModal()
    {
        $this->brand_name = '';
        $this->resetValidation($this->brand_name = null);
        $this->emit('show-modal', 'Mostrando Modal');
    }

    public function StoreBrand()
    {

        $rules = [

            'brand_name' => 'required|unique:brands,name|min:3|max:100'
        ];

        $messages = [

            'brand_name.required' => 'Campo requerido',
            'brand_name.unique' => 'Ya existe',
            'brand_name.min' => 'Minimo 3 caracteres',
            'brand_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $brand = Brand::create([

            'name' => $this->brand_name
        ]);

        if ($brand) {

            $this->brand_name = '';
            $this->allBrands = Brand::select('id', 'name')->get();
            $this->brandId = $brand->id;
            $this->emit('item-added-2', 'Registrado correctamente');

        } else {

            $this->emit('record-error', 'Error al Registrar');
            return;
        }
    }

    public function Edit(Product $product)
    {
        $this->productValues = [];
        $this->selected_id = $product->id;
        $this->statusId = $product->status_id;
        $this->containerId = $product->presentation_subcategory_id;
        $this->brandId = $product->brand_id;
        $this->comment = $product->comment;
        $this->aux_1 = $this->allValues->where('product_id', $product->id);

        foreach ($this->aux_1 as $value) {

            $this->productValues[] = [

                'id' => $value->id,
                'cost' => number_format($value->cost, 2),
                'price' => number_format($value->price, 2),
                'is_saved' => true
            ];
        }

        $this->emit('show-modal', 'Abrir Modal');
    }

    public function Update()
    {
        $rules = [

            'statusId' => 'not_in:Elegir',
            'containerId' => 'not_in:Elegir',
            'brandId' => 'not_in:Elegir',
            'comment' => 'max:100',
            'productValues.*.cost' => 'required|numeric',
            'productValues.*.price' => 'required|numeric',
        ];

        $messages = [

            'statusId.not_in' => 'Seleccione una opcion',
            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 100 caracteres',
            'productValues.*.cost.required' => 'Campo requerido',
            'productValues.*.cost.numeric' => 'Solo numeros',
            'productValues.*.price.required' => 'Campo requerido',
            'productValues.*.price.numeric' => 'Solo numeros',
        ];

        $this->validate($rules, $messages);

        $product = $this->allProducts->find($this->selected_id);
        $this->aux_2 = collect($this->productValues);

        DB::beginTransaction();

        try {

            $container = $this->allContainers_2->find($this->containerId);

            if($product->presentation_subcategory_id == $this->containerId){

                $product->Update([

                    'status_id' => $this->statusId,
                    'brand_id' => $this->brandId,
                    'comment' => $this->comment
                ]);

            }else{

                $products = $this->allProducts->where('presentation_subcategory_id',$product->presentation_subcategory_id);
                $previus_max_number = $products->max('number');
                $previus_number = $product->number;
                $previus_code = $product->code;                               
                $number = $this->allProducts->where('presentation_subcategory_id', $this->containerId)->max('number') + 1;
                $code = $container->prefix . '-' . str_pad($number, 4, 0, STR_PAD_LEFT);

                $product->Update([

                    'number' => $number,
                    'code' => $code,
                    'status_id' => $this->statusId,
                    'presentation_subcategory_id' => $this->containerId,
                    'brand_id' => $this->brandId,
                    'comment' => $this->comment
                ]);


                if(count($products) > 1){

                    if($previus_number != $previus_max_number){

                        $last_product = $products->firstWhere('number',$previus_max_number);

                        $last_product->Update([

                            'number' => $previus_number,
                            'code' => $previus_code

                        ]);

                    }

                }
                
            }

            if($this->statusId == 1 && $container->status_id == 2){

                $container->Update([

                    'status_id' => 1
                ]);
            }

            foreach ($this->aux_2 as $actual) {

                if ($actual['id']) {

                    $value = $this->aux_1->find($actual['id']);

                    $value->Update([

                        'cost' => $actual['cost'],
                        'price' => $actual['price']

                    ]);
                } else {

                    $value = Value::create([

                        'cost' => $actual['cost'],
                        'price' => $actual['price'],
                        'product_id' => $product->id,
                        'status_id' => 1
                    ]);

                    $stocks = [];

                    foreach($this->allOffices as $office){

                        $stocks[$office->id] = ['alerts' => 1, 'stock' => 0];
                        
                    }

                    $value->offices()->attach($stocks);
                }
            }

            foreach ($this->aux_1 as $previus) {

                if ($this->aux_2->doesntContain('id', $previus->id)) {

                    $previus->Update([

                        'status_id' => 2

                    ]);
                }
            }

            DB::commit();
            $this->emit('item-updated', 'Registro Actualizado');
            $this->mount();
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

        /*if ($this->image) {
            
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $imageTemp = $product->image;
            $product->image = $customFileName;
            $product->save();

            if ($imageTemp != null) {
                
                if (file_exists('storage/products/' . $imageTemp)) {
                    
                    unlink('storage/products/' . $imageTemp);
                }
            }
        }*/
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Product $product)
    {
        /*$imageTemp = $product->image;

        if ($imageTemp != null) {

            if (file_exists('storage/products/' . $imageTemp)) {
                
                unlink('storage/products/' . $imageTemp);
            }
        }*/

        //$status = $this->allStatuses->firstWhere('id', 2);
        //dd($status);
        $product->Update([

            'status_id' => 2
        ]);

        $this->emit('item-deleted', 'Registro eliminado');
        $this->mount();
    }

    public function resetUI()
    {
        $this->mount();
    }
}
