<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use App\Models\Subcategory;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
//use Illuminate\Database\Eloquent\Builder;

class Subcategories extends Component
{
    use WithPagination;

    public $search,$selected_id,$pageTitle,$componentName,$name,$categories,$category_id,$category_name;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'subcategorias';
        $this->search = '';
        $this->selected_id = 0;
        $this->name = '';
        $this->categories = Category::select('id','name')->get();
        $this->category_id = 'Elegir';
        $this->category_name = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0)

            $data = Subcategory::with('category')
            ->withCount('presentations')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('category', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->pagination);

        else

            $data = Subcategory::with('category')
            ->withCount('presentations')
            ->orderBy(Category::select('name')->whereColumn('categories.id','subcategories.category_id'))
            ->paginate($this->pagination);
            

        return view('livewire.subcategory.subcategories', ['subcategories' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function Store(){

        $rules = [

            'name' => 'required|unique:subcategories|min:3|max:100',
            'category_id' => 'not_in:Elegir'
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'category_id.not_in' => 'Seleccione una opcion'
        ];

        $this->validate($rules, $messages);

        Subcategory::create([

            'name' => $this->name,
            'category_id' => $this->category_id
        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
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

            'category_name' => 'required|unique:categories,name|min:3|max:100'
        ];

        $messages = [

            'category_name.required' => 'Campo requerido',
            'category_name.unique' => 'Ya existe',
            'category_name.min' => 'Minimo 3 caracteres',
            'category_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        Category::create([

            'name' => $this->category_name
        ]);

        $this->category_name = '';
        $this->resetValidation($this->category_name = null);
        $this->category_id = 'Elegir';
        $this->categories = Category::select('id','name')->get();
        $this->emit('item-added-2', 'Registrado correctamente');
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Edit(Subcategory $subcategory){

        
        $this->name = $subcategory->name;
        $this->selected_id = $subcategory->id;
        $this->category_id = $subcategory->category->id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:100|unique:subcategories,name,{$this->selected_id}",
            'category_id' => 'not_in:Elegir'
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'category_id.not_in' => 'Seleccione una opcion'
        ];

        $this->validate($rules, $messages);

        $subcategory = Subcategory::find($this->selected_id);

        $subcategory->update([

            'name' => $this->name,
            'category_id' => $this->category_id
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Subcategory $subcategory,$presentations_count)
    {
        
        if ($presentations_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $subcategory->delete();
            $this->emit('item-deleted', 'Eliminado correctamente');
            $this->mount();

            /*DB::beginTransaction();

            try {

                if($presentations_count > 0){

                    $subcategory->presentations()->detach();
                    $subcategory->delete();

                }else{

                    $subcategory->delete();
                }

                DB::commit();
                $this->emit('item-deleted', 'Eliminado correctamente');
                $this->mount();

            } catch (\Throwable $th) {

                DB::rollback();
                throw $th;
            }*/
        }
    }

    public function resetUI(){

        $this->mount();
    }
}
