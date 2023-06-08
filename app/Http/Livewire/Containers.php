<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Presentation;
use App\Models\PresentationSubcategory;
use App\Models\Product;
use Exception;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class Containers extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $categories,$subcategories,$presentations,$products;
    public $categoryId,$subcategoryId,$presentationId,$prefix,$additional_info;
    public $category_name,$presentation_name,$subcategory_name;
    public $aux_1,$aux_2,$aux_3;
    public $modal_id;
    private $pagination = 20;

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'contenedores';
        $this->search = 'elegir';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->categories = Category::select('id', 'name')->where('status_id',1)->orderBy('name')->get();
        $this->categoryId = 'elegir';
        $this->subcategories = Subcategory::select('id', 'name')->where('status_id',1)->orderBy('name')->get();
        $this->subcategoryId = 'elegir';
        $this->presentations = Presentation::select('id', 'name')->where('status_id',1)->orderBy('name')->get();
        $this->presentationId = 'elegir';
        $this->products = Product::all();
        $this->prefix = '';
        $this->additional_info = '';
        $this->modal_id = 0;
        $this->aux_1 = '';
        $this->aux_2 = '';
        $this->aux_3 = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        switch ($this->search_2) {

            case 0:

                if ($this->search != 'elegir') {

                    $data = PresentationSubcategory::where('status_id', 1)
                        ->with('presentation','subcategory.category')
                        ->withCount(['products' => function ($query) {
                            $query->where('products.status_id',1);
                        }])
                        ->WhereHas('subcategory', function ($query) {
                            $query->where('category_id', $this->search);
                        })
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);

                } else {

                    $data = PresentationSubcategory::where('status_id', 1)
                        ->with('presentation','subcategory.category')
                        ->withCount(['products' => function ($query) {
                            $query->where('products.status_id',1);
                        }])
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);
                }

                break;


            case 1:

                if ($this->search != 'elegir') {

                    $data = PresentationSubcategory::where('status_id', 2)
                        ->with('presentation','subcategory.category')
                        ->withCount(['products' => function ($query) {
                            $query->where('products.status_id',1);
                        }])
                        ->WhereHas('subcategory', function ($query) {
                            $query->where('category_id', $this->search);
                        })
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = PresentationSubcategory::where('status_id', 2)
                        ->with('presentation','subcategory.category')
                        ->withCount(['products' => function ($query) {
                            $query->where('products.status_id',1);
                        }])
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);
                }

                break;
        }

        return view('livewire.container.containers', ['containers' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function updatedcategoryId($category_id){

        $this->subcategoryId = 'elegir';
        $this->subcategories = Subcategory::select('id', 'name')->where('status_id',1)->where('category_id', $category_id)->orderBy('name')->get();
    }

    public function Store(){

        $rules = [

            'categoryId' => 'not_in:elegir',
            'subcategoryId' => ['not_in:elegir', Rule::unique('presentation_subcategory', 'subcategory_id')
                ->where(function ($query) {
                    return $query->where('presentation_id', $this->presentationId);
                })],
            'presentationId' => ['not_in:elegir', Rule::unique('presentation_subcategory', 'presentation_id')
                ->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategoryId);
                })],
            'prefix' => 'required|min:3|max:15|unique:presentation_subcategory',
            'additional_info' => 'max:45',
        ];

        $messages = [

            'categoryId.not_in' => 'Seleccione una opcion',
            'subcategoryId.not_in' => 'Seleccione una opcion',
            'subcategoryId.unique' => 'Esta subcategoria ya tiene esa presentacion',
            'presentationId.not_in' => 'Seleccione una opcion',
            'presentationId.unique' => 'Esta presentacion ya tiene esa subcategoria',
            'prefix.required' => 'Campo requerido',
            'prefix.min' => 'Minimo 3 caracteres',
            'prefix.max' => 'Maximo 15 caracteres',
            'prefix.unique' => 'Ya existe',
            'additional_info.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $subcategory = $this->subcategories->find($this->subcategoryId);
        $presentation = $this->presentations->find($this->presentationId);

        if ($subcategory && $presentation) {

            $subcategory->presentations()->attach($presentation->id, [

                'prefix' => $this->prefix,
                'additional_info' => $this->additional_info,
                'status_id' => 1
            ]);

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error','Error al registrar');
            return;
        }
    }

    public function ShowCategoryModal($modal){

        if ($modal < 1) {

            $this->emit('show-modal-2', 'Mostrar Modal');

        } else {

            $this->emit('show-modal-5', 'Mostrar Modal');
        }
    }

    public function CloseCategoryModal($modal){

        $this->category_name = '';
        $this->resetValidation($this->category_name = null);

        if ($modal < 1) {

            $this->emit('show-modal', 'Mostrando modal');

        } else {

            $this->emit('show-modal-3', 'Mostrando modal');
        }
    }

    public function StoreCategory($modal){

        $rules = [

            'category_name' => 'required|min:3|max:45|unique:categories,name'
        ];

        $messages = [

            'category_name.required' => 'Campo requerido',
            'category_name.min' => 'Minimo 3 caracteres',
            'category_name.max' => 'Maximo 45 caracteres',
            'category_name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $category = Category::create([

            'name' => $this->category_name,
            'status_id' => 1
        ]);

        if($category){

            $this->category_name = '';
            $this->resetValidation($this->category_name = null);
            $this->categoryId = $category->id;
            $this->categories = Category::select('id', 'name')->where('status_id',1)->orderBy('name')->get();
            $this->subcategoryId = 'elegir';
            $this->subcategories = Subcategory::select('id', 'name')->where('status_id',1)->where('category_id', $category->id)->orderBy('name')->get();
            $this->emit('record-added-2', 'Registrado correctamente');

            if ($modal < 1) {

                $this->emit('show-modal', 'Mostrando modal');

            } else {

                $this->emit('show-modal-3', 'Mostrando modal');
            }

        }else{

            $this->emit('record-error','Error al registrar');
            return;
        }

    }

    public function ShowSubcategoryModal(){

        $this->modal_id = 1;
        $this->emit('show-modal-3', 'Mostrando modal');
    }

    public function CloseSubcategoryModal(){

        $this->subcategory_name = '';
        $this->resetValidation($this->subcategory_name = null);
        $this->modal_id = 0;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function StoreSubcategory(){

        $rules = [

            'subcategory_name' => 'required|min:3|max:45|unique:subcategories,name',
            'categoryId' => 'not_in:elegir'
        ];

        $messages = [

            'subcategory_name.required' => 'Campo requerido',
            'subcategory_name.min' => 'Minimo 3 caracteres',
            'subcategory_name.max' => 'Maximo 45 caracteres',
            'subcategory_name.unique' => 'Ya existe',
            'categoryId.not_in' => 'Seleccione una opcion'
        ];

        $this->validate($rules, $messages);

        $subcategory = Subcategory::create([

            'name' => $this->subcategory_name,
            'category_id' => $this->categoryId,
            'status_id' => 1
        ]);

        if($subcategory){

            $this->subcategory_name = '';
            $this->resetValidation($this->subcategory_name = null);
            $this->modal_id = 0;
            $this->subcategoryId = $subcategory->id;
            $this->subcategories = Subcategory::select('id', 'name')->where('status_id',1)->where('category_id', $subcategory->category_id)->orderBy('name')->get();
            $this->emit('record-added-3', 'Registrado correctamente');

        }else{

            $this->emit('record-error','Error al registrar');
            return;
        }

    }

    public function ShowPresentationModal(){

        $this->emit('show-modal-4', 'Mostrando modal');
    }

    public function ClosePresentationModal(){

        $this->presentation_name = '';
        $this->resetValidation($this->presentation_name = null);
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function StorePresentation(){

        $rules = [

            'presentation_name' => 'required|min:3|max:45|unique:presentations,name'
        ];

        $messages = [

            'presentation_name.required' => 'Campo requerido',
            'presentation_name.min' => 'Minimo 3 caracteres',
            'presentation_name.max' => 'Maximo 45 caracteres',
            'presentation_name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $presentation = Presentation::create([

            'name' => $this->presentation_name,
            'status_id' => 1
        ]);

        if($presentation){

            $this->presentation_name = '';
            $this->resetValidation($this->presentation_name = null);
            $this->presentationId = $presentation->id;
            $this->presentations = Presentation::select('id', 'name')->where('status_id',1)->orderBy('name')->get();
            $this->emit('record-added-4', 'Registrado correctamente');

        }else{

            $this->emit('record-error','Error al registrar');
            return;
        }
        
    }

    public function Edit(PresentationSubcategory $container){

        $this->selected_id = $container->id;
        $this->categoryId = $container->subcategory->category_id;
        $this->subcategoryId = $container->subcategory_id;
        $this->aux_1 = $this->subcategories->find($container->subcategory_id);
        $this->presentationId = $container->presentation_id;
        $this->aux_2 = $this->presentations->find($container->presentation_id);
        $this->prefix = $container->prefix;
        $this->aux_3 = $container->prefix;
        $this->additional_info = $container->additional_info;
        $this->subcategories = $this->subcategories->where('category_id', $this->categoryId);
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'categoryId' => 'not_in:elegir',
            'subcategoryId' => ['not_in:elegir', Rule::unique('presentation_subcategory', 'subcategory_id')
                ->ignore($this->selected_id)
                ->where(function ($query) {
                    return $query->where('presentation_id', $this->presentationId);
                })],
            'presentationId' => ['not_in:elegir', Rule::unique('presentation_subcategory', 'presentation_id')
                ->ignore($this->selected_id)
                ->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategoryId);
                })],
            'prefix' => "required|min:3|max:15|unique:presentation_subcategory,prefix,{$this->selected_id}",
            'additional_info' => 'max:45',
        ];

        $messages = [

            'categoryId.not_in' => 'Seleccione una opcion',
            'subcategoryId.not_in' => 'Seleccione una opcion',
            'subcategoryId.unique' => 'Esta subcategoria ya tiene esa presentacion',
            'presentationId.not_in' => 'Seleccione una opcion',
            'presentationId.unique' => 'Esta presentacion ya tiene esa subcategoria',
            'prefix.required' => 'Campo requerido',
            'prefix.unique' => 'Ya existe',
            'prefix.min' => 'Minimo 3 caracteres',
            'prefix.max' => 'Maximo 15 caracteres',
            'additional_info.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $actual_subcategory = $this->subcategories->find($this->subcategoryId);
            $actual_presentation = $this->presentations->find($this->presentationId);
            $previus_subcategory = $this->aux_1;
            $previus_presentation = $this->aux_2;
            $previus_prefix = $this->aux_3;

            if(($this->subcategoryId != $previus_subcategory->id) || ($this->presentationId != $previus_presentation->id)){
                
                if($this->prefix == $previus_prefix){

                    $this->addError('prefix', 'Ya existe');
                    return;

                }else{

                    $actual_subcategory->presentations()->attach($actual_presentation->id, [

                        'prefix' => $this->prefix,
                        'additional_info' => $this->additional_info,
                        'status_id' => 1
                    ]);

                    $previus_subcategory->presentations()->updateExistingPivot($previus_presentation->id, [

                        'status_id' => 2
        
                    ]);

                    $products = $this->products->where('presentation_subcategory_id',$this->selected_id);

                    if(count($products) > 0){

                        $actual = $actual_subcategory->presentations->firstWhere('pivot.presentation_id', $actual_presentation->id);

                        foreach($products as $product){

                            $number = $product->number;

                            $product->Update([

                                'code' => $this->prefix . '-' . str_pad($number, 4, 0, STR_PAD_LEFT),
                                'presentation_subcategory_id' => $actual->pivot->id

                            ]);
                        }
                    }

                }

            }else{
                
                $actual_subcategory->presentations()->updateExistingPivot($actual_presentation->id, [

                    'prefix' => $this->prefix,
                    'additional_info' => $this->additional_info

                ]);

                if($this->prefix != $previus_prefix){

                    $products = $this->products->where('presentation_subcategory_id',$this->selected_id);

                    if(count($products) > 0){

                        foreach($products as $product){

                            $number = $product->number;

                            $product->Update([

                                'code' => $this->prefix . '-' . str_pad($number, 4, 0, STR_PAD_LEFT)

                            ]);
                        }
                    }

                }

            }

            DB::commit();
            $this->emit('record-updated', 'Actualizado correctamente');
            $this->mount();

        } catch (Exception $e) {
                        
            DB::rollback();
            $this->emit('record-error', 'Error al actualizar');
        }

        /*$subcategory_1 = $this->aux_1->find($this->aux_2);
        $subcategory_2 = $this->subcategories->find($this->subcategoryId);
        $presentation_1 = $this->presentations->find($this->aux_3);
        $presentation_2 = $this->presentations->find($this->presentationId);
        $previus = $subcategory_1->presentations->firstWhere('pivot.id', $this->selected_id);
        $products = $this->products->where('presentation_subcategory_id', $previus->pivot->id);
        //dd($subcategory_1,$subcategory_2,$presentation_1,$presentation_2,$previus,$products);


        DB::beginTransaction();

        try {

            if (($previus->pivot->subcategory_id != $this->subcategoryId) && ($previus->pivot->presentation_id != $this->presentationId)) {
                //dd('ambos cambiaron');

                $subcategory_2->presentations()->attach($presentation_2->id, [

                    'prefix' => $this->aux_5,
                    'additional_info' => $this->additional_info,
                    'status_id' => 1

                ]);

                if(count($products) > 0){

                    $actual = $subcategory_2->presentations->firstWhere('pivot.presentation_id', $presentation_2->id);

                    foreach ($products as $product) {

                        $product->Update([
    
                            'presentation_subcategory_id' => $actual->pivot->id,
    
                        ]);
                    }
                }

                $subcategory_1->presentations()->updateExistingPivot($presentation_1->id, [

                    'status_id' => 2,

                ]);

                //crea registro sin eliminar los demas
                $subcategory_2->presentations()
                ->syncWithoutDetaching([$presentation_2->id => [

                    'prefix' => $this->prefix,
                    'additional_info' => $this->additional_info,
                    'status_id' => 1

                    ]]);

                //crea registro si no existe, caso contrario lo elimina
                $presentation_2->subcategories()->toggle([$subcategory_1->id, $subcategory_2->id => [

                    'prefix' => $this->prefix,
                    'additional_info' => $this->additional_info,
                    'status_id' => 1

                ]]);

            }

            if (($previus->pivot->subcategory_id != $this->subcategoryId) && ($previus->pivot->presentation_id == $this->presentationId)) {
                //dd('cambio la subcategoria');

                $presentation_2->subcategories()->attach($subcategory_2->id, [

                    'prefix' => $this->aux_5,
                    'additional_info' => $this->additional_info,
                    'status_id' => 1

                ]);


                if(count($products) > 0){

                    $actual = $presentation_2->subcategories->firstWhere('pivot.subcategory_id', $subcategory_2->id);

                    foreach ($products as $product) {

                        $product->Update([
    
                            'presentation_subcategory_id' => $actual->pivot->id,
    
                        ]);
                    }
                }

                $presentation_1->subcategories()->updateExistingPivot($subcategory_1->id, [

                    'status_id' => 2,

                ]);

            }

            if (($previus->pivot->presentation_id != $this->presentationId) && ($previus->pivot->subcategory_id == $this->subcategoryId)) {
                //dd('cambio la presentacion');

                $subcategory_2->presentations()->attach($presentation_2->id, [

                    'prefix' => $this->aux_5,
                    'additional_info' => $this->additional_info,
                    'status_id' => 1

                ]);

                if(count($products) > 0){

                    $actual = $subcategory_2->presentations->firstWhere('pivot.presentation_id', $presentation_2->id);

                    foreach ($products as $product) {

                        $product->Update([
    
                            'presentation_subcategory_id' => $actual->pivot->id,
    
                        ]);
                    }
                }

                $subcategory_1->presentations()->updateExistingPivot($presentation_1->id, [

                    'status_id' => 2,

                ]);

            }

            if (($previus->pivot->subcategory_id == $this->subcategoryId) && ($previus->pivot->presentation_id == $this->presentationId)) {
                //dd('nada cambio');
                
                $subcategory_1->presentations()->updateExistingPivot($presentation_1->id, [

                    'prefix' => $this->prefix,
                    'status_id' => $this->statusId,
                    'additional_info' => $this->additional_info

                ]);
            }

            DB::commit();
            $this->emit('item-updated', 'Actualizado correctamente');
            $this->mount();
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }*/
    }

    protected $listeners = [

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(PresentationSubcategory $container,$subcategory_status,$presentation_status){

        if($subcategory_status != 1){

            $this->emit('record-error', 'La subcategoria de este contenedor se encuentra bloqueada. Debe activarla para continuar.');
            return;

        }

        if($presentation_status != 1){

            $this->emit('record-error', 'La presentacion de este contenedor se encuentra bloqueada. Debe activarla para continuar.');
            return;
            
        }

        $container->update([

            'status_id' => 1

        ]);

        $this->emit('record-activated','Registro desbloqueado');
        $this->mount();
    }

    public function Destroy(PresentationSubcategory $container,$products_count){
        
        $subcategory = $this->subcategories->find($container->subcategory_id);

        if ($products_count > 0) {

            $this->emit('record-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $subcategory->presentations()->updateExistingPivot($container->presentation_id, [

                'status_id' => 2,

            ]);

            $this->emit('record-deleted', 'Eliminado correctamente');
            $this->mount();
        }
    }

    public function resetUI(){
        
        $this->mount();
    }
}
