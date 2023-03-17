<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    public $search,$selected_id,$pageTitle,$componentName;
    public $name,$phone,$fax,$email,$nit,$city,$country;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'proveedores';
        $this->name = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->city = '';
        $this->country = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0){

            $data = Supplier::with(['incomes','debts'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('fax', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('nit', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->withCount(['incomes','debts'])
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);

        }else{

            $data = Supplier::with(['incomes','debts'])
            ->withCount(['incomes','debts'])
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);

        }   

        return view('livewire.supplier.suppliers', ['suppliers' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => 'max:12',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'nit.max' => 'Maximo 12 caracteres',
            //'nit.unique' => 'Otro proveedor ocupa este NIT',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::create([

            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'nit' => $this->nit,
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
        $this->phone = $supplier->phone;
        $this->fax = $supplier->fax;
        $this->email = $supplier->email;
        $this->nit = $supplier->nit;
        $this->city = $supplier->city;
        $this->country = $supplier->country;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){


        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => "max:12|unique:suppliers,nit,{$this->selected_id}",
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'nit.max' => 'Maximo 12 caracteres',
            'nit.unique' => 'Otro proveedor ocupa este NIT',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::find($this->selected_id);

        $supplier->update([

            'name' => $this->name,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'city' => $this->city,
            'country' => $this->country
        ]);

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Supplier $supplier,$incomes_count,$debts_count){

        if ($incomes_count > 0 || $debts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $supplier->delete();

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
