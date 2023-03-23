<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Paycheck;
use App\Models\Customer;
use App\Models\Bank;
use App\Models\Status;
use Livewire\WithPagination;

class Paychecks extends Component
{
    use WithPagination;

    public $search, $search_2, $selected_id, $pageTitle, $componentName;
    public $customers, $banks, $statuses;
    public $customer_id,$bank_id,$status_id,$description,$number,$amount;
    public $name,$alias,$entity_code,$phone,$fax,$email,$nit,$city,$country;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'cheques por cobrar';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->description = '';
        $this->number = '';
        $this->amount = '';
        $this->name = '';
        $this->alias = '';
        $this->entity_code = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->city = '';
        $this->country = '';
        $this->customer_id = 'Elegir';
        $this->bank_id = 'Elegir';
        $this->status_id = 'Elegir';
        $this->customers = Customer::select('id','name')->get();
        $this->banks = Bank::select('id','alias')->get();
        $this->statuses = Status::where('type','transaccion')->get();
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

                if (strlen($this->search) > 0) {

                    $data = Paycheck::with(['status','sale', 'bank', 'customer'])
                        ->where('status_id', 4)
                        ->where(function ($q1) {
                            $q1->where('number', 'like', '%' . $this->search . '%');
                            $q1->orWhere('description', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->whereHas('bank', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('customer', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('sale', function ($q3) {
                                    $q3->where('file_number', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('created_at', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Paycheck::with(['status','sale', 'bank', 'customer'])
                        ->where('status_id', 4)
                        ->orderBy('created_at', 'asc')
                        ->paginate($this->pagination);
                }

                break;

            case 1:

                if (strlen($this->search) > 0) {

                    $data = Paycheck::with(['status','sale', 'bank', 'customer'])
                        ->where('status_id','!=', 4)
                        ->where(function ($q1) {
                            $q1->where('number', 'like', '%' . $this->search . '%');
                            $q1->orWhere('description', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->whereHas('bank', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('customer', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('sale', function ($q3) {
                                    $q3->where('file_number', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('created_at', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Paycheck::with(['status','sale', 'bank', 'customer'])
                        ->where('status_id','!=', 4)
                        ->orderBy('created_at', 'asc')
                        ->paginate($this->pagination);
                }

                break;
        }

        return view('livewire.paycheck.paychecks', ['paychecks' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ShowCustomerModal(){

        $this->emit('show-customer-modal', 'Mostrando Modal');

    }

    public function CloseCustomerModal(){

        $this->name = '';
        $this->resetValidation($this->name = null);
        $this->phone = '';
        $this->resetValidation($this->phone);
        $this->fax = '';
        $this->resetValidation($this->fax);
        $this->email = '';
        $this->resetValidation($this->email = null);
        $this->nit = '';
        $this->resetValidation($this->nit);
        $this->city = '';
        $this->resetValidation($this->city = null);
        $this->country = '';
        $this->resetValidation($this->country = null);
        $this->emit('show-modal', 'Mostrando Modal');

    }

    public function StoreCustomer(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'phone' => 'digits_between:8,12',
            'fax' => 'digits_between:8,12',
            'email' => 'max:100',
            'nit' => 'digits_between:12,12',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'phone.digits_between' => 'Solo numeros enteros positivos, de 8 a 12 digitos',
            'fax.digits_between' => 'Solo numeros enteros positivos, de 8 a 12 digitos',
            'email.max' => 'Maximo 100 caracteres',
            'nit.digits_between' => 'Solo numeros enteros positivos, 12 digitos',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $customer = Customer::create([
            
            'name' => $this->name,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'city' => $this->city,
            'country' => $this->country,
            'status_id' => 1
        ]);

        if ($customer) {

            $this->name = '';
            $this->phone = '';
            $this->fax = '';
            $this->email = '';
            $this->nit = '';
            $this->city = '';
            $this->country = '';
            $this->customers = Customer::select('id','name')->get();
            $this->customer_id = $customer->id;
            $this->emit('customer-added', 'Registrado correctamente');
        } else {

            $this->emit('item-error', 'Error al Registrar');
            return;
        }

    }

    public function ShowBankModal(){

        $this->emit('show-bank-modal', 'Mostrando Modal');

    }

    public function CloseBankModal(){

        $this->name = '';
        $this->resetValidation($this->name = null);
        $this->alias = '';
        $this->resetValidation($this->alias = null);
        $this->entity_code = '';
        $this->resetValidation($this->entity_code = null);
        $this->emit('show-modal', 'Mostrando Modal');

    }

    public function StoreBank(){

        $rules = [

            'name' => 'required|min:3|max:100|unique:banks',
            'alias' => 'required|min:3|max:15|unique:banks',
            'entity_code' => 'required|digits_between:4,4|unique:banks',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'entity_code.required' => 'Campo requerido',
            'entity_code.unique' => 'Ya existe',
            'entity_code.digits_between' => 'Solo numeros enteros positivos, 4 digitos',
        ];

        $this->validate($rules, $messages);

        $bank = Bank::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code,
            'status_id' => 1
        ]);

        if ($bank) {

            $this->name = '';
            $this->alias = '';
            $this->entity_code = '';
            $this->banks = Bank::select('id','alias')->get();
            $this->bank_id = $bank->id;
            $this->emit('bank-added', 'Registrado correctamente');
        } else {

            $this->emit('item-error', 'Error al Registrar');
            return;
        }

    }


    public function Edit(Paycheck $paycheck)
    {
        $this->selected_id = $paycheck->id;
        $this->description = $paycheck->description;
        $this->number = $paycheck->number;
        $this->amount = number_format($paycheck->amount,2);
        $this->status_id = $paycheck->status_id;
        $this->bank_id = $paycheck->bank_id;
        $this->customer_id = $paycheck->customer_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {

        $rules = [

            'customer_id' => 'not_in:Elegir',
            'bank_id' => 'not_in:Elegir',
            'number' => 'required|digits_between:6,15',
        ];

        $messages = [

            'customer_id.not_in' => 'Seleccione una opcion',
            'bank_id.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos, de 6 a 15 digitos',
        ];

        $this->validate($rules, $messages);

        $paycheck = Paycheck::find($this->selected_id);
        
        $paycheck->update([

            'number' => $this->number,
            'customer_id' => $this->customer_id,
            'bank_id' => $this->bank_id

        ]);


        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();

    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Paycheck $paycheck)
    {
        $paycheck->update([

            'status_id' => 2

        ]);

        $this->emit('item-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI(){

        $this->mount();
    }
}
