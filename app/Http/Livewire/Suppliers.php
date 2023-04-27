<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$selected_id;
    public $name,$alias,$phone,$email,$city,$country;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'proveedores';
        $this->search = '';
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->email = '';
        $this->city = '';
        $this->country = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0){

            /*$data = Supplier::with(['incomes','debts'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('alias', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->withCount(['incomes','debts'])
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);*/

            $data = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('alias', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);

        }else{

            /*$data = Supplier::with(['incomes','debts'])
            ->withCount(['incomes','debts'])
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);*/

            $data = Supplier::orderBy('name', 'asc')
            ->paginate($this->pagination);

        }   

        return view('livewire.supplier.suppliers', ['suppliers' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:100|unique:suppliers',
            'alias' => 'required|min:3|max:45|unique:suppliers',
            'phone' => 'max:12',
            'email' => 'max:100',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 45 caracteres',
            'alias.unique' => 'Ya existe',
            'phone.max' => 'Maximo 12 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::create([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'country' => $this->country
        ]);

        if ($supplier) {

            $this->emit('item-added', 'Registrado correctamente');
            $this->mount();

        } else {

            $this->emit('item-error', 'Error al Registrar');
            return;
        }
    }

    public function Edit(Supplier $supplier){

        $this->selected_id = $supplier->id;
        $this->name = $supplier->name;
        $this->alias = $supplier->alias;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->city = $supplier->city;
        $this->country = $supplier->country;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {
        $rules = [

            'name' => "required|min:3|max:100|unique:suppliers,name,{$this->selected_id}",
            'alias' => "required|min:3|max:45|unique:suppliers,alias,{$this->selected_id}",
            'phone' => 'max:12',
            'email' => 'max:100',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 45 caracteres',
            'alias.unique' => 'Ya existe',
            'phone.max' => 'Maximo 12 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::find($this->selected_id);

        $supplier->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'country' => $this->country
        ]);

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    /*public function Destroy(Supplier $supplier,$incomes_count,$debts_count){

        if ($incomes_count > 0 || $debts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $supplier->delete();

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }*/

    public function Destroy(Supplier $supplier){

        $supplier->delete();
        $this->emit('item-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI(){

        $this->mount();
    }
}
