<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Brand;
use Livewire\WithPagination;

class Brands extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$selected_id,$name;
    private $pagination = 30;

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'marcas';
        $this->search = '';
        $this->selected_id = 0;
        $this->name = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0){

            /*$data = Brand::withCount('products')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);*/

            $data = Brand::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);

        }else{

            /*$data = Brand::withCount('products')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);*/

            $data = Brand::orderBy('name', 'asc')
            ->paginate($this->pagination);

        }

        return view('livewire.brand.brands', ['brands' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store()
    {
        $rules = [

            'name' => 'required|unique:brands|min:3|max:45'
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        Brand::create([

            'name' => $this->name
        ]);

        $this->emit('item-added', 'Registrado correctamente');
        $this->mount();
    }

    public function Edit(Brand $brand)
    {
        $this->name = $brand->name;
        $this->selected_id = $brand->id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {
        $rules = [

            'name' => "required|min:3|max:45|unique:brands,name,{$this->selected_id}"
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $brand = Brand::find($this->selected_id);

        $brand->update([

            'name' => $this->name
        ]);

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    /*public function Destroy(Brand $brand,$products_count)
    {
        if ($products_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $brand->delete();
            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }
    }*/

    public function Destroy(Brand $brand)
    {
        $brand->delete();
        $this->emit('item-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI()
    {
        $this->mount();
    }
}
