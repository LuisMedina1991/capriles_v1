<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use App\Models\Subcategory;
use Livewire\WithPagination;

class Subcategories extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name,$categories,$category_id,$category_name;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'subcategorias';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->categories = Category::select('id','name')->where('status_id',1)->get();
        $this->category_id = 'elegir';
        $this->category_name = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        switch ($this->search_2){

            case 0:

                if(strlen($this->search) > 0){

                    $data = Subcategory::with('category')
                    ->withCount(['presentations' => function ($query) {
                        $query->where('presentation_subcategory.status_id',1);
                    }])
                    ->where('status_id',1)
                    ->where(function ($q1) {
                        $q1->where('name', 'like', '%' . $this->search . '%');
                        $q1->orWhere(function ($q2) {
                            $q2->whereHas('category', function ($q3) {
                                $q3->where('name', 'like', '%' . $this->search . '%');
                            });
                        });
                    })
                    ->orderBy(Category::select('name')->whereColumn('categories.id','subcategories.category_id'))
                    ->orderBy('name')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Subcategory::with('category')
                    ->withCount(['presentations' => function ($query) {
                        $query->where('presentation_subcategory.status_id',1);
                    }])
                    ->where('status_id',1)
                    ->orderBy(Category::select('name')->whereColumn('categories.id','subcategories.category_id'))
                    ->orderBy('name')
                    ->paginate($this->pagination);   
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Subcategory::with('category')
                    ->withCount(['presentations' => function ($query) {
                        $query->where('presentation_subcategory.status_id',1);
                    }])
                    ->where('status_id',2)
                    ->where(function ($q1) {
                        $q1->where('name', 'like', '%' . $this->search . '%');
                        $q1->orWhere(function ($q2) {
                            $q2->whereHas('category', function ($q3) {
                                $q3->where('name', 'like', '%' . $this->search . '%');
                            });
                        });
                    })
                    ->orderBy(Category::select('name')->whereColumn('categories.id','subcategories.category_id'))
                    ->orderBy('name')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Subcategory::with('category')
                    ->withCount(['presentations' => function ($query) {
                        $query->where('presentation_subcategory.status_id',1);
                    }])
                    ->where('status_id',2)
                    ->orderBy(Category::select('name')->whereColumn('categories.id','subcategories.category_id'))
                    ->orderBy('name')
                    ->paginate($this->pagination);   
                }

            break;

        }

        return view('livewire.subcategory.subcategories', ['subcategories' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:45|unique:subcategories',
            'category_id' => 'not_in:elegir',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'category_id.not_in' => 'Seleccione una opcion',
            'name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $subcategory = Subcategory::create([

            'name' => $this->name,
            'status_id' => 1,
            'category_id' => $this->category_id
        ]);

        if($subcategory){

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error', 'Error al registrar');
            return;
        }

    }

    public function ShowCategoryModal(){

        $this->emit('show-modal-2', 'Mostrando modal');
    }

    public function CloseCategoryModal(){

        $this->category_name = '';
        $this->resetValidation($this->category_name = null);
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function StoreCategory(){

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
            $this->categories = Category::select('id','name')->where('status_id',1)->get();
            $this->category_id = $category->id;
            $this->emit('record-added-2', 'Registrado correctamente');
            $this->emit('show-modal', 'Mostrando modal');

        }else{

            $this->emit('record-error','Error al registrar');
            return;
        }

    }

    public function Edit(Subcategory $subcategory){

        $this->name = $subcategory->name;
        $this->selected_id = $subcategory->id;
        $this->category_id = $subcategory->category->id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:45|unique:subcategories,name,{$this->selected_id}",
            'category_id' => 'not_in:elegir',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
            'category_id.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $subcategory = Subcategory::find($this->selected_id);

        $subcategory->update([

            'name' => $this->name,
            'category_id' => $this->category_id
        ]);

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(Subcategory $subcategory,$category_status){

        if($category_status != 1){

            $this->emit('record-error', 'La categoria de esta subcategoria se encuentra bloqueada. Debe activarla para continuar.');
            return;

        }else{

            $subcategory->update([

                'status_id' => 1
    
            ]);

            $this->emit('record-activated','Registro desbloqueado');
            $this->mount();

        }

    }

    public function Destroy(Subcategory $subcategory, $presentations_count){

        if($presentations_count > 0){

            $this->emit('record-error', 'No se puede eliminar debido a relacion');
            return;

        }else{

            $subcategory->update([

                'status_id' => 2

            ]);

            $this->emit('record-deleted', 'Eliminado correctamente');
            $this->mount();

        }
    }

    public function resetUI(){

        $this->mount();
    }
}
