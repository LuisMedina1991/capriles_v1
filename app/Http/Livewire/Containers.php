<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Presentation;
use App\Models\Status;
use App\Models\Product;
use App\Models\PresentationSubcategory;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Containers extends Component
{
    use WithPagination;

    public $search, $search_2, $selected_id, $pageTitle, $componentName;
    public $categories, $subcategories, $presentations, $statuses,$products;
    public $categoryId, $subcategoryId, $presentationId, $prefix, $additional_info, $statusId;
    public $category_name, $presentation_name, $subcategory_name;
    public $modal_id;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'contenedores';
        $this->categories = Category::select('id', 'name')->get();
        $this->categoryId = 'Elegir';
        $this->subcategories = Subcategory::select('id', 'name')->get();
        $this->subcategoryId = 'Elegir';
        $this->presentations = Presentation::select('id', 'name')->get();
        $this->presentationId = 'Elegir';
        $this->statuses = Status::where('type', 'registro')->get();
        $this->statusId = 'Elegir';
        $this->products = Product::all();
        $this->prefix = '';
        $this->additional_info = '';
        $this->search = 'Elegir';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->modal_id = 0;
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

                if ($this->search != 'Elegir') {

                    $data = PresentationSubcategory::where('status_id', 1)
                        ->with('status', 'presentation', 'subcategory.category')
                        ->withCount('activeProducts')
                        ->WhereHas('subcategory', function ($query) {
                            $query->where('category_id', $this->search);
                        })
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = PresentationSubcategory::where('status_id', 1)
                        ->with('status', 'presentation', 'subcategory.category')
                        ->withCount('activeProducts')
                        ->orderBy('prefix', 'asc')
                        ->paginate($this->pagination);
                }

                break;


            case 1:

                if ($this->search != 'Elegir') {

                    $data = PresentationSubcategory::where('status_id', 2)
                        ->with('status', 'presentation', 'subcategory.category')
                        ->withCount('activeProducts')
                        ->WhereHas('subcategory', function ($query) {
                            $query->where('category_id', $this->search);
                        })
                        ->paginate($this->pagination);
                } else {

                    $data = PresentationSubcategory::where('status_id', 2)
                        ->with('status', 'presentation', 'subcategory.category')
                        ->withCount('activeProducts')
                        ->paginate($this->pagination);
                }

                break;
        }

        return view('livewire.container.containers', ['containers' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function updatedcategoryId($category_id)
    {
        $this->subcategoryId = 'Elegir';
        $this->subcategories = Subcategory::select('id', 'name')->where('category_id', $category_id)->get();
    }

    public function Store()
    {

        $rules = [

            'subcategoryId' => ['not_in:Elegir', Rule::unique('presentation_subcategory', 'subcategory_id')
                ->where(function ($query) {
                    return $query->where('presentation_id', $this->presentationId);
                })],
            'presentationId' => ['not_in:Elegir', Rule::unique('presentation_subcategory', 'presentation_id')
                ->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategoryId);
                })],
            'categoryId' => 'not_in:Elegir',
            'prefix' => 'required|unique:presentation_subcategory|min:3|max:45',
            'additional_info' => 'max:100',
        ];

        $messages = [

            'subcategoryId.not_in' => 'Seleccione una opcion',
            'subcategoryId.unique' => 'Esta subcategoria ya tiene esa presentacion',
            'presentationId.not_in' => 'Seleccione una opcion',
            'presentationId.unique' => 'Esta presentacion ya tiene esa subcategoria',
            'categoryId.not_in' => 'Seleccione una opcion',
            'prefix.required' => 'Campo requerido',
            'prefix.unique' => 'Ya existe',
            'prefix.min' => 'Minimo 3 caracteres',
            'prefix.max' => 'Maximo 45 caracteres',
            'additional_info.max' => 'Maximo 100 caracteres',
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
        }

        $this->emit('item-added', 'Registrado correctamente');
        $this->mount();
    }

    public function ShowCategoryModal($modal)
    {

        if ($modal < 1) {

            $this->emit('show-modal-2', 'Mostrar Modal');
        } else {

            $this->emit('show-modal-5', 'Mostrar Modal');
        }
    }

    public function CloseCategoryModal($modal)
    {
        $this->category_name = '';
        $this->resetValidation($this->category_name = null);

        if ($modal < 1) {

            $this->emit('show-modal', 'Mostrando modal');
        } else {

            $this->emit('show-modal-3', 'Mostrando modal');
        }
    }

    public function StoreCategory($modal)
    {

        $rules = [

            'category_name' => 'required|unique:categories,name|min:3|max:100'
        ];

        $messages = [

            'category_name.required' => 'Campo requerido',
            'category_name.unique' => 'Ya existe',
            'category_name.min' => 'Minimo 3 caracteres',
            'category_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::create([

            'name' => $this->category_name
        ]);

        $this->category_name = '';
        $this->resetValidation($this->category_name = null);
        $this->categoryId = $category->id;
        $this->categories = Category::select('id', 'name')->get();
        $this->subcategoryId = 'Elegir';
        $this->subcategories = Subcategory::select('id', 'name')->where('category_id', $category->id)->get();
        $this->emit('item-added-2', 'Registrado correctamente');

        if ($modal < 1) {

            $this->emit('show-modal', 'Mostrando modal');
        } else {

            $this->emit('show-modal-3', 'Mostrando modal');
        }
    }

    public function ShowSubcategoryModal()
    {
        $this->modal_id = 1;
        $this->emit('show-modal-3', 'Mostrando modal');
    }

    public function CloseSubcategoryModal()
    {
        $this->subcategory_name = '';
        $this->resetValidation($this->subcategory_name = null);
        $this->modal_id = 0;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function StoreSubcategory()
    {

        $rules = [

            'subcategory_name' => 'required|unique:subcategories,name|min:3|max:100',
            'categoryId' => 'not_in:Elegir'
        ];

        $messages = [

            'subcategory_name.required' => 'Campo requerido',
            'subcategory_name.unique' => 'Ya existe',
            'subcategory_name.min' => 'Minimo 3 caracteres',
            'subcategory_name.max' => 'Maximo 100 caracteres',
            'categoryId.not_in' => 'Seleccione una opcion'
        ];

        $this->validate($rules, $messages);

        $subcategory = Subcategory::create([

            'name' => $this->subcategory_name,
            'category_id' => $this->categoryId
        ]);

        $this->subcategory_name = '';
        $this->resetValidation($this->subcategory_name = null);
        $this->categoryId = $subcategory->category_id;
        $this->modal_id = 0;
        $this->categories = Category::select('id', 'name')->get();
        $this->subcategoryId = $subcategory->id;
        $this->subcategories = Subcategory::select('id', 'name')->where('category_id', $subcategory->category_id)->get();
        $this->emit('item-added-3', 'Registrado correctamente');
    }

    public function ShowPresentationModal()
    {

        $this->emit('show-modal-4', 'Mostrando modal');
    }

    public function ClosePresentationModal()
    {

        $this->presentation_name = '';
        $this->resetValidation($this->presentation_name = null);
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function StorePresentation()
    {

        $rules = [

            'presentation_name' => 'required|unique:presentations,name|min:3|max:100'
        ];

        $messages = [

            'presentation_name.required' => 'Campo requerido',
            'presentation_name.unique' => 'Ya existe',
            'presentation_name.min' => 'Minimo 3 caracteres',
            'presentation_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $presentation = Presentation::create([

            'name' => $this->presentation_name
        ]);

        $this->presentation_name = '';
        $this->resetValidation($this->presentation_name = null);
        $this->presentationId = $presentation->id;
        $this->presentations = Presentation::select('id', 'name')->get();
        $this->emit('item-added-4', 'Registrado correctamente');
    }

    public function Edit(PresentationSubcategory $container)
    {
        $this->selected_id = $container->id;
        $this->categoryId = $container->subcategory->category_id;
        $this->subcategoryId = $container->subcategory_id;
        $this->presentationId = $container->presentation_id;
        $this->prefix = $container->prefix;
        $this->statusId = $container->status_id;
        $this->additional_info = $container->additional_info;
        $this->subcategories = $this->subcategories->where('category_id', $this->categoryId);
        $this->emit('show-edit-modal', 'Mostrando modal');
    }

    public function Update()
    {
        $rules = [

            /*'subcategoryId' => ['not_in:Elegir', Rule::unique('presentation_subcategory', 'subcategory_id')
                ->ignore($this->selected_id)
                ->where(function ($query) {
                    return $query->where('presentation_id', $this->presentationId);
                })],
            'presentationId' => ['not_in:Elegir', Rule::unique('presentation_subcategory', 'presentation_id')
                ->ignore($this->selected_id)
                ->where(function ($query) {
                    return $query->where('subcategory_id', $this->subcategoryId);
                })],*/
            'prefix' => "required|min:3|max:100|unique:presentation_subcategory,prefix,{$this->selected_id}",
            'additional_info' => 'max:100',
            'statusId' => 'not_in:Elegir',
        ];

        $messages = [

            'prefix.required' => 'Campo requerido',
            'prefix.unique' => 'Ya existe',
            'prefix.min' => 'Minimo 3 caracteres',
            'prefix.max' => 'Maximo 45 caracteres',
            'additional_info.max' => 'Maximo 100 caracteres',
            'statusId.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $subcategory = $this->subcategories->find($this->subcategoryId);
        $presentation = $this->presentations->find($this->presentationId);

        $subcategory->presentations()->updateExistingPivot($presentation->id, [

            'prefix' => $this->prefix,
            'status_id' => $this->statusId,
            'additional_info' => $this->additional_info

        ]);

        $products = $this->products->where('presentation_subcategory_id',$this->selected_id);

        if(count($products) > 0){

            foreach($products as $product){

                $number = $product->number;

                $product->Update([

                    'code' => $this->prefix . '-' . str_pad($number, 4, 0, STR_PAD_LEFT)

                ]);
            }
        }

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();

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

        'destroy' => 'Destroy'
    ];

    public function Destroy(PresentationSubcategory $container, $active_products_count)
    {
        $subcategory = $this->subcategories->find($container->subcategory_id);

        if ($active_products_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;
        } else {

            $subcategory->presentations()->updateExistingPivot($container->presentation_id, [

                'status_id' => 2,

            ]);

            $this->emit('item-deleted', 'Eliminado correctamente');
            $this->mount();
        }
    }

    public function resetUI()
    {
        $this->mount();
    }
}
