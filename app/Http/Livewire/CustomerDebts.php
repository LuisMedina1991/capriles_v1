<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\CustomerDebt;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade as PDF;

class CustomerDebts extends Component
{
    use WithPagination;

    public $search,$search_2,$selected_id,$pageTitle,$componentName;
    public $name,$phone,$fax,$email,$nit,$city,$country,$statusId;
    public $description,$amount,$income,$supplier,$details;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'deudas de';
        $this->componentName = 'clientes';
        $this->name = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->city = '';
        $this->country = '';
        $this->statusId = 'Elegir';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->details = [];
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        switch ($this->search_2) {

            case 0:

                if(strlen($this->search) > 0){

                    $data = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',1)
                    ->whereHas('sale', function ($query) {
                        $query->where('file_number', 'like', '%' . $this->search . '%');
                        $query->orWhereHas('customer', function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                        $query->orWhereHas('debt', function($query){
                            $query->where('description', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',1)
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->paginate($this->pagination);
        
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',2)
                    ->whereHas('sale', function ($query) {
                        $query->where('file_number', 'like', '%' . $this->search . '%');
                        $query->orWhereHas('customer', function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                        $query->orWhereHas('debt', function($query){
                            $query->where('description', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',2)
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->paginate($this->pagination);
        
                }   

            break;

        }


        return view('livewire.customer_debts.customer-debts', ['debts' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => 'max:12|unique:suppliers',
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
            'nit.unique' => 'Ya existe',
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
            'nit.unique' => 'Ya existe',
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

    public function Details(CustomerDebt $debt){

        $this->details = $debt->details->where('status_id',1);
        $this->emit('show-detail-modal', 'Mostrando modal');
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
