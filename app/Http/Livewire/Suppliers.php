<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name,$alias,$phone,$email,$city,$country,$status_id;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'proveedores';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->email = '';
        $this->city = '';
        $this->country = '';
        $this->status_id = 'elegir';
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
        
                    $data = Supplier::where('status_id',1)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('email', 'like', '%' . $this->search . '%');
                        $query->orWhere('city', 'like', '%' . $this->search . '%');
                        $query->orWhere('country', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);

                }else{

                    $data = Supplier::where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

            case 1:

                if(strlen($this->search) > 0){
        
                    $data = Supplier::where('status_id',2)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('email', 'like', '%' . $this->search . '%');
                        $query->orWhere('city', 'like', '%' . $this->search . '%');
                        $query->orWhere('country', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                    
                }else{

                    $data = Supplier::where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

        }

        return view('livewire.supplier.suppliers', ['suppliers' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:45',
            'alias' => 'required|min:3|max:15|unique:suppliers',
            'phone' => 'digits_between:7,12',
            'email' => 'max:100',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'phone.digits_between' => 'Solo digitos enteros y positivos. De 7 a 12 digitos',
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
            'country' => $this->country,
            'status_id' => 1
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
        $this->status_id = $supplier->status_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {
        $rules = [

            'name' => 'required|min:3|max:45',
            'alias' => "required|min:3|max:15|unique:suppliers,alias,{$this->selected_id}",
            'phone' => 'digits_between:7,12',
            'email' => 'max:100',
            'city' => 'max:45',
            'country' => 'max:45',
            'status_id' => 'not_in:elegir',
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
            'phone.digits_between' => 'Solo digitos enteros y positivos. De 7 a 12 digitos',
            'email.max' => 'Maximo 100 caracteres',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
            'status_id.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::find($this->selected_id);

        $supplier->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'country' => $this->country,
            'status_id' => $this->status_id
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

        $supplier->update([

            'status_id' => 2

        ]);

        
        $this->emit('item-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI(){

        $this->mount();
    }
}
