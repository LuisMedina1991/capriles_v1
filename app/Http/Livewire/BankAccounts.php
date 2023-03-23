<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Bank;
use App\Models\Status;
use Livewire\WithPagination;

class BankAccounts extends Component
{
    use WithPagination;

    public $search, $search_2, $selected_id, $pageTitle, $componentName;
    public $companies, $banks, $statuses;
    public $company_id,$bank_id,$status_id,$number,$type,$currency,$balance;
    public $name,$alias,$phone,$fax,$email,$nit,$address,$entity_code;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'cuentas bancarias';
        $this->companies = Company::select('id','alias')->get();
        $this->banks = Bank::select('id','alias')->get();
        $this->statuses = Status::where('type','registro')->get();
        $this->company_id = 'Elegir';
        $this->bank_id = 'Elegir';
        $this->status_id = 'Elegir';
        $this->number = '';
        $this->type = 'Elegir';
        $this->currency = 'Elegir';
        $this->balance = '';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->address = '';
        $this->entity_code = '';
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

                    $data = BankAccount::with(['status', 'bank', 'company'])
                        ->where('status_id', 1)
                        ->where(function ($q1) {
                            $q1->where('number', 'like', '%' . $this->search . '%');
                            $q1->orWhere('type', 'like', '%' . $this->search . '%');
                            $q1->orWhere('currency', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->whereHas('bank', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('company', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('number', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = BankAccount::with(['status', 'bank', 'company'])
                        ->where('status_id', 1)
                        ->orderBy('number', 'asc')
                        ->paginate($this->pagination);
                }

                break;

            case 1:

                if (strlen($this->search) > 0) {

                    $data = BankAccount::with(['status', 'bank', 'company'])
                        ->where('status_id', 2)
                        ->where(function ($q1) {
                            $q1->where('number', 'like', '%' . $this->search . '%');
                            $q1->orWhere('type', 'like', '%' . $this->search . '%');
                            $q1->orWhere('currency', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->whereHas('bank', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('company', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                    $q3->orWhere('alias', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('number', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = BankAccount::with(['status', 'bank', 'company'])
                        ->where('status_id', 2)
                        ->orderBy('number', 'asc')
                        ->paginate($this->pagination);
                }

                break;
        }

        return view('livewire.bank_accounts.bank-accounts', ['accounts' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ShowCompanyModal(){

        $this->emit('show-company-modal', 'Mostrando Modal');

    }

    public function CloseCompanyModal(){

        $this->name = '';
        $this->resetValidation($this->name = null);
        $this->alias = '';
        $this->resetValidation($this->alias = null);
        $this->phone = '';
        $this->resetValidation($this->phone = null);
        $this->fax = '';
        $this->resetValidation($this->fax = null);
        $this->email = '';
        $this->resetValidation($this->email = null);
        $this->nit = '';
        $this->resetValidation($this->nit = null);
        $this->address = '';
        $this->resetValidation($this->address = null);
        $this->emit('show-modal', 'Mostrando Modal');

    }

    public function StoreCompany(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'alias' => 'required|min:3|max:15|unique:companies',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'email' => 'max:100',
            'nit' => 'required|digits_between:12,13|unique:companies',
            'address' => 'max:100',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'nit.required' => 'Campo requerido',
            'nit.unique' => 'Ya existe',
            'nit.digits_between' => 'Solo numeros enteros positivos, de 12 a 13 digitos',
            'address.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $company = Company::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'address' => $this->address,
            'status_id' => 1
        ]);

        if ($company) {

            $this->name = '';
            $this->alias = '';
            $this->phone = '';
            $this->fax = '';
            $this->email = '';
            $this->nit = '';
            $this->address = '';
            $this->companies = Company::select('id','alias')->get();
            $this->company_id = $company->id;
            $this->emit('company-added', 'Registrado correctamente');
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
            'entity_code' => 'required|digits_between:4,6|unique:banks',
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
            'entity_code.digits_between' => 'Solo numeros enteros positivos, de 4 a 6 digitos',
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

    public function Store(){

        $rules = [

            'company_id' => 'not_in:Elegir',
            'bank_id' => 'not_in:Elegir',
            'type' => 'not_in:Elegir',
            'currency' => 'not_in:Elegir',
            'number' => 'required|digits_between:11,14|unique:bank_accounts',
            'balance' => 'required|numeric|gte:0',
        ];

        $messages = [

            'company_id.not_in' => 'Seleccione una opcion',
            'bank_id.not_in' => 'Seleccione una opcion',
            'type.not_in' => 'Seleccione una opcion',
            'currency.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos entre 11 y 14 digitos',
            'number.unique' => 'Ya existe',
            'balance.required' => 'Campo requerido',
            'balance.numeric' => 'Solo numeros',
            'balance.gte' => 'Solo numeros positivos',
        ];

        $this->validate($rules, $messages);

        BankAccount::create([

            'number' => $this->number,
            'type' => $this->type,
            'currency' => $this->currency,
            'balance' => $this->balance,
            'company_id' => $this->company_id,
            'bank_id' => $this->bank_id,
            'status_id' => 1

        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
    }

    public function Edit(BankAccount $account)
    {
        $this->selected_id = $account->id;
        $this->company_id = $account->company_id;
        $this->bank_id = $account->bank_id;
        $this->type = $account->type;
        $this->currency = $account->currency;
        $this->number = $account->number;
        $this->balance = number_format($account->balance,2);
        $this->status_id = $account->status_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {

        $rules = [

            'company_id' => 'not_in:Elegir',
            'bank_id' => 'not_in:Elegir',
            'status_id' => 'not_in:Elegir',
            'type' => 'not_in:Elegir',
            'currency' => 'not_in:Elegir',
            'number' => "required|digits_between:11,14|unique:bank_accounts,number,{$this->selected_id}",
            /*'number' => ['required','digits_between:11,14', Rule::unique('bank_company', 'number')
                ->ignore($this->selected_id)
                ->where(function ($query) {
                    return $query->where('status_id', 2);
                })],*/
            'balance' => 'required|numeric|gte:0',
        ];

        $messages = [

            'company_id.not_in' => 'Seleccione una opcion',
            'bank_id.not_in' => 'Seleccione una opcion',
            'status_id.not_in' => 'Seleccione una opcion',
            'type.not_in' => 'Seleccione una opcion',
            'currency.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos entre 11 y 14 digitos',
            'number.unique' => 'Ya existe',
            'balance.required' => 'Campo requerido',
            'balance.numeric' => 'Solo numeros',
            'balance.gte' => 'Solo numeros positivos',
        ];

        $this->validate($rules, $messages);

        $account = BankAccount::find($this->selected_id);
        
        $account->update([

            'number' => $this->number,
            'type' => $this->type,
            'currency' => $this->currency,
            'balance' => $this->balance,
            'company_id' => $this->company_id,
            'bank_id' => $this->bank_id,
            'status_id' => $this->status_id

        ]);


        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();

    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(BankAccount $account)
    {
        $account->update([

            'status_id' => 2

        ]);

        $this->emit('item-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI(){

        $this->mount();
    }
}
