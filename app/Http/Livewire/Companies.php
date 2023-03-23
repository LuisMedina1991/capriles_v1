<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Company;
use App\Models\Status;
use Livewire\WithPagination;

class Companies extends Component
{
    use WithPagination;

    public $search,$search_2,$selected_id,$pageTitle,$componentName;
    public $name,$alias,$phone,$fax,$email,$nit,$address,$statusId;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'empresas';
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->address = '';
        $this->statusId = 'Elegir';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
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

                    $data = Company::with('status')
                    ->withCount('accounts')
                    ->where('status_id',1)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('alias', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('fax', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nit', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Company::with('status')
                    ->withCount('accounts')
                    ->where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Company::with('status')
                    ->withCount('accounts')
                    ->where('status_id',2)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('alias', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('fax', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nit', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Company::with('status')
                    ->withCount('accounts')
                    ->where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }

            break;

        }   

        return view('livewire.company.companies', ['companies' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

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
            'nit.digits_between' => 'Solo numeros enteros positivos entre 12 y 13 digitos',
            'address.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        Company::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'address' => $this->address,
            'status_id' => 1
        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
    }

    public function Edit(Company $company){

        $this->selected_id = $company->id;
        $this->name = $company->name;
        $this->alias = $company->alias;
        $this->phone = $company->phone;
        $this->fax = $company->fax;
        $this->email = $company->email;
        $this->nit = $company->nit;
        $this->address = $company->address;
        $this->statusId = $company->status_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'alias' => "required|min:3|max:15|unique:companies,alias,{$this->selected_id}",
            'phone' => 'max:12',
            'fax' => 'max:12',
            'email' => 'max:100',
            'nit' => "required|digits_between:12,13|unique:companies,nit,{$this->selected_id}",
            'address' => 'max:100',
            'statusId' => 'not_in:Elegir',
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
            'nit.digits_between' => 'Solo numeros enteros positivos entre 12 y 13 digitos',
            'address.max' => 'Maximo 100 caracteres',
            'statusId.not_int' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $company = Company::find($this->selected_id);

        $company->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'address' => $this->address,
            'status_id' => $this->statusId
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Company $company,$accounts_count){

        if ($accounts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $company->update([

                'status_id' => 2
            ]);

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
